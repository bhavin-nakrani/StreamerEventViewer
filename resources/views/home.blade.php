@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Featured Channels</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>Image</th>
                                <th>Title</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(!empty($streams))

                                @foreach($streams as $stream)
                                    <tr>
                                        <td><img src="{{ $stream['image'] }}" alt="image" width="100"></td>
                                        <td>{{ $stream['title'] }}</td>
                                        <td>
                                            <form id="form-{{$stream['stream_id']}}" action="{{ route('stream_create') }}" method="post">
                                                {{ csrf_field() }}
                                                <input type="hidden" name="title" value="{{$stream['title']}}">
                                                <input type="hidden" name="stream_id" value="{{$stream['stream_id']}}">
                                                <input type="hidden" name="channel_id" value="{{$stream['channel_id']}}">
                                                <input type="hidden" name="channel_name" value="{{$stream['channel_name']}}">
                                                <input type="hidden" name="image" value="{{$stream['image']}}">
                                                <input type="hidden" name="url" value="{{$stream['url']}}">
                                                <button type="submit" class="btn btn-primary btn-sm mt-3">Favourite</button>
                                            </form>

                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
