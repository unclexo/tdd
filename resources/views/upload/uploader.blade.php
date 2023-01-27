@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <div>{{ __('Image Uploader') }}</div>
                    </div>

                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if (session('failed'))
                            <div class="alert alert-warning">
                                {{ session('failed') }}
                            </div>
                        @endif

                        @if (session('message'))
                            <div class="alert alert-success">
                                {{ session('message') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('upload.resize.via.queue') }}" enctype="multipart/form-data" class="flex items-center space-x-6">
                            @csrf

                            <div class="input-group">
                                <input type="file" name="image" class="form-control" id="inputGroupFile04" aria-describedby="inputGroupFileAddon04" aria-label="Upload">
                                <button class="btn btn-primary" type="submit" id="inputGroupFileAddon04">{{ __('Upload') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
