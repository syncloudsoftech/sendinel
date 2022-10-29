@extends('layouts.main')

@section('meta')
    <title>{{ __('Send & Share Large Files, Quickly!') }} - {{ config('app.name', 'Laravel') }}</title>
@endsection

@section('content')
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <h1 class="h4 card-title text-primary mb-3">
                {{ __('Upload') }}
            </h1>
            <p class="card-text">
                {{ __(':app lets you upload and share large files securely.', ['app' => config('app.name')]) }}
                {{ __('You can also add password protection or make files expire after a certain period.') }}
            </p>
            <form enctype="multipart/form-data" id="form-upload" method="post">
                @csrf
                <div class="mb-3">
                    <div class="custom-file">
                        <label class="form-label" for="upload-attachment">
                            {{ __('Select a file') }} <span class="text-danger">&ast;</span>
                        </label>
                        <input class="form-control @error('attachment') is-invalid @enderror" id="upload-attachment" name="attachment" required type="file">
                        @error('attachment')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="send-attachment">
                        {{ __("Your email") }} <span class="text-danger">&ast;</span>
                    </label>
                    <!--suppress HtmlFormInputWithoutLabel -->
                    <input autofocus class="form-control @error('sender') is-invalid @enderror" id="upload-sender" name="sender" placeholder="{{ __('Enter your email') }}" type="email" required value="{{ old('sender') }}">
                    @error('sender')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <!--suppress HtmlFormInputWithoutLabel -->
                    <textarea class="form-control @error('comments') is-invalid @enderror" name="comments" placeholder="{{ __('Enter some comments (optional)') }}">{{ old('comments') }}</textarea>
                    @error('comments')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="collapse" id="upload-options">
                    <div class="mb-3">
                        <!--suppress HtmlFormInputWithoutLabel -->
                        <input class="form-control @error('recipient') is-invalid @enderror" name="recipient" placeholder="{{ __('Enter recipient\'s email (optional)') }}" type="email" value="{{ old('recipient') }}">
                        @error('recipient')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <!--suppress HtmlFormInputWithoutLabel -->
                        <input class="form-control @error('password') is-invalid @enderror" name="password" placeholder="{{ __('Enter password (optional)') }}" type="password">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <!--suppress HtmlFormInputWithoutLabel -->
                        <select class="form-select @error('expiry') is-invalid @enderror" name="expiry" required>
                            <option value="1">Expires after 1 day</option>
                            <option value="7">Expires after 7 days</option>
                            <option value="30" selected>Expires after 30 days</option>
                        </select>
                        @error('expiry')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="btn-toolbar">
                    <button class="btn btn-success" data-wait-text="{{ __('%d%% Uploaded') }}">
                        <i class="fas fa-upload me-1"></i> <span>{{ __('Upload') }}</span>
                    </button>
                    <button class="btn btn-link ms-1" data-bs-target="#upload-options" data-bs-toggle="collapse" type="button">
                        {{ __('Options') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
