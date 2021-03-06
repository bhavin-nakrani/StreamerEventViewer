@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{$stream->title}} Detail</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <div id="twitch-embed"></div>
                        <div class="row">
                            @if(!empty($videos))
                                @foreach($videos as $video)
                                    <div class="col-md-4">
                                        <iframe
                                                src="https://player.twitch.tv/?video={{$video['id']}}&autoplay=false"
                                                height="250"
                                                width="350"
                                                frameborder="0"
                                                scrolling="no"
                                                allowfullscreen="true">
                                        </iframe>
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        <div class="row offset-5">
                            <p>{{ $event }}</p>

                        </div>
                            <div class="spacer20"></div>
                        <div class="row offset-5">

                            <a href="{{ route('stream') }}" class="btn btn-info">Back to List</a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script src="https://embed.twitch.tv/embed/v1.js"></script>

    <!-- Create a Twitch.Embed object that will render within the "twitch-embed" root element. -->
    <script type="text/javascript">
        var embed = new Twitch.Embed("twitch-embed", {
            width: 1024,
            height: 480,
            channel: "{{ $stream->channel_name }}"
        });

        embed.addEventListener(Twitch.Embed.VIDEO_READY, () => {
            var player = embed.getPlayer();
        player.play();
        });
    </script>
@endsection

