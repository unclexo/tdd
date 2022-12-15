@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Posts List') }}</div>

                    <div class="card-body">
                        @foreach($posts as $post)
                            <div class="mb-4">
                                <h5 class="card-title">
                                    <a href="{{ $post->path() }}">{{ $post->title }}</a>
                                </h5>
                                <p class="card-text">{{ $post->description }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
