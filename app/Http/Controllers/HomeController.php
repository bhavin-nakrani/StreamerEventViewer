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
     * @return void
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
                'Client-ID' => 'r9m4afraos9fztk6om80ku8492yqws'
            ]
        ]);

        $res = $result->getBody()->getContents();

        $jsonObj = \GuzzleHttp\json_decode($res);
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

        $s = new Stream;
        $s->title = $request->input('title');
        $s->stream_id = $request->input('stream_id');
        $s->channel_id = $request->input('channel_id');
        $s->channel_name = $request->input('channel_name');
        $s->image = $request->input('image');
        $s->url = $request->input('url');
        $s->save();

        return redirect()->route('stream')->with('flash_success', "The channel [{$s->title}] is created successfully.");
    }

    public function stream()
    {
        $streams = Stream::all();

        return view('stream', ['streams' => $streams]);
    }

    public function detail($id)
    {

        $stream = Stream::find($id);

        $client = new Client();

        $eventResult = $client->get('https://api.twitch.tv/v5/channels/'.$stream->stream_id.'/events', [
            'headers' => [
                'Accept' => 'application/vnd.twitchtv.v5+json',
                'Client-ID' => 'r9m4afraos9fztk6om80ku8492yqws',
            ]
        ]);
        $eventRes = \GuzzleHttp\json_decode($eventResult->getBody()->getContents());
        $event = empty($eventRes->events) ? 'No Events found' :$eventRes->events;

        $result = $client->get('https://api.twitch.tv/kraken/channels/'.$stream->channel_id.'/videos?limit=6', [
            'headers' => [
                'Accept' => 'application/vnd.twitchtv.v5+json',
                'Client-ID' => 'r9m4afraos9fztk6om80ku8492yqws',
            ]
        ]);

        $res = \GuzzleHttp\json_decode($result->getBody()->getContents());
        $videos = [];

        foreach ($res->videos as $video) {

                $videos[] = [
                    'id' => $video->_id,
                    'game' => $video->game
                ];
        }

        return view('stream_detail', ['videos' => $videos, 'stream' => $stream, 'event' => $event]);
    }
}
