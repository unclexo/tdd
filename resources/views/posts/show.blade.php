@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <div>{{ __('Post Details') }}</div>
                        <div>
                            <a href="{{ route('posts.edit', $post->id) }}">{{ __('Edit') }}</a>
                            &nbsp;

                            <a
                                href="{{ route('posts.delete', $post->id) }}"
                                onclick="event.preventDefault(); document.getElementById('post-delete-form').submit();"
                            >
                                {{ __('Delete') }}
                            </a>

                            <form id="post-delete-form" action="{{ route('posts.delete', $post->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                    </div>

                    <div class="card-body">
                        <h5 class="card-title">{{ $post->title }}</h5>
                        <p class="card-text">{{ $post->description }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
