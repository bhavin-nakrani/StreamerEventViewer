<?php

namespace App\Http\Controllers;

use App\Models\Stream;
use Illuminate\Http\Request;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Config;
use Validator;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $client = new Client();
        $result = $client->get('https://api.twitch.tv/kraken/streams/featured?limit=5', [
            'headers' => [
                'Accept' => 'application/vnd.twitchtv.v5+json',
                'Client-ID' => \Config::get('config.TWITCH_KEY')
            ]
        ]);

        $jsonObj = \GuzzleHttp\json_decode($result->getBody()->getContents());
        $features = $jsonObj->featured;

        $streams = [];

        foreach ($features as $feature)
        {
            $stream = $feature->stream;
            $channel = $stream->channel;

            $streams[] = [
                'title' => $channel->display_name,
                'stream_id' => $stream->_id,
                'channel_id' => $channel->_id,
                'channel_name' => $channel->name,
                'image' => $stream->preview->medium,
                'url' => $channel->url,
            ];
        }

        return view('home', ['streams' => $streams]);
    }

    /**
     * Save stream in system
     *
     * @param Request $request
     * @return $this
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'stream_id' => 'required',
            'channel_id' => 'required',
            'title' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $streamId = $request->input('stream_id');
        $existStream = Stream::where('stream_id', $streamId)->first();
        if ($existStream) {
            return redirect()->route('stream')->with('flash_info', "The channel [{$existStream->title}] is exist in your list.");
        }

        $s = new Stream;
        $s->title = $request->input('title');
        $s->stream_id = $streamId;
        $s->channel_id = $request->input('channel_id');
        $s->channel_name = $request->input('channel_name');
        $s->image = $request->input('image');
        $s->url = $request->input('url');
        $s->save();

        return redirect()->route('stream')->with('flash_success', "The channel [{$s->title}] is created successfully.");
    }

    /**
     * All favourite stream list
     *
     * @return mixed
     */
    public function stream()
    {
        $streams = Stream::all();

        return view('stream', ['streams' => $streams]);
    }

    /**
     * Stream detail page
     *
     * @param $id
     * @return mixed
     */
    public function detail($id)
    {
        $videos = [];
        $stream = Stream::find($id);
        $client = new Client();

        $headers = [
            'Accept' => 'application/vnd.twitchtv.v5+json',
            'Client-ID' => \Config::get('config.TWITCH_KEY'),
        ];
        // Get stream events
        $eventResult = $client->get('https://api.twitch.tv/v5/channels/'.$stream->stream_id.'/events', [
            'headers' => $headers
        ]);
        $eventRes = \GuzzleHttp\json_decode($eventResult->getBody()->getContents());
        $event = empty($eventRes->events) ? 'No Events found' :$eventRes->events;

        // Get stream videos
        $result = $client->get('https://api.twitch.tv/kraken/channels/'.$stream->channel_id.'/videos?limit=6', [
            'headers' => $headers
        ]);

        $res = \GuzzleHttp\json_decode($result->getBody()->getContents());
        foreach ($res->videos as $video) {
            $videos[] = [
                'id' => $video->_id,
                'game' => $video->game
            ];
        }

        return view('stream_detail', ['videos' => $videos, 'stream' => $stream, 'event' => $event]);
    }
}