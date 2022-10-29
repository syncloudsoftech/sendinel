@extends('layouts.main')

@php
    $upload_size = ByteUnits\Metric::kilobytes($upload->size)->format(null, ' ');
@endphp

@section('meta')
    <title>{{ __('Download :name - :size', ['name' => $upload->name, 'size' => $upload_size]) }} - {{ config('app.name', 'Laravel') }}</title>
@endsection

@section('content')
    <div class="modal" id="modal-report" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Report this file') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('Close') }}"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-0">{{ __('If you believe this file infringes your copyright, please send an email to ":email" requesting for immediate removal.', ['email' => config('app.dmca_email')]) }}</p>
                </div>
            </div>
        </div>
    </div>
    <div class="card border-0 shadow-sm">
        <form action="{{ route('download.submit', $upload) }}" id="form-download" method="post">
            @csrf
            <div class="card-body">
                <h1 class="h4 card-title text-primary mb-3">
                    {{ __('Download') }}
                </h1>
                <p class="card-text">
                    {{ __('You can download your file below or share it on your favorite social networks.') }}
                </p>
                <div class="input-group mb-3">
                    <span class="input-group-text"><i class="fas fa-link"></i></span>
                    <!--suppress HtmlFormInputWithoutLabel -->
                    <input class="form-control" readonly type="url" value="{{ $upload->url }}">
                    <button class="btn btn-dark" id="button-copy-link" type="button">
                        <i class="fas fa-clipboard"></i>
                    </button>
                </div>
                <div class="table-responsive mb-3">
                    <table class="table table-borderless table-sm mb-0">
                        <tbody>
                        <tr>
                            <th class="align-text-top">{{ __('File') }}:</th>
                            <td>{{ $upload->name }}</td>
                        </tr>
                        @if ($upload->comments)
                            <tr>
                                <th class="align-text-top">{{ __('Comments') }}:</th>
                                <td>{{ $upload->comments }}</td>
                            </tr>
                        @endif
                        <tr>
                            <th class="align-text-top">{{ __('Size') }}:</th>
                            <td>{{ $upload_size }}</td>
                        </tr>
                        <tr>
                            <th class="align-text-top">{{ __('Downloads') }}:</th>
                            <td>{{ $upload->downloads->count() }}</td>
                        </tr>
                        <tr>
                            <th class="align-text-top">{{ __('Uploaded') }}:</th>
                            <td>{{ $upload->created_at->diffForHumans() }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                @if ($upload->password)
                    <div class="mb-3">
                        <!--suppress HtmlFormInputWithoutLabel -->
                        <input class="form-control @error('password') is-invalid @enderror" name="password" placeholder="{{ __('Enter password') }}" required type="password">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                @endif
                @php
                    $download_wait_time = config('app.download_wait_time');
                @endphp
                <div class="btn-toolbar mb-3">
                    <button class="btn btn-success" @if ($download_wait_time > 0) data-wait-time="{{ $download_wait_time }}" data-wait-text="{{ __('Downloading in %d seconds...') }}" @endif>
                        <i class="fas fa-download me-1"></i> <span>{{ __('Download') }}</span>
                    </button>
                    <button class="btn btn-link text-dark ms-1" data-bs-target="#modal-report" data-bs-toggle="modal" type="button">
                        {{ __('Report') }}
                    </button>
                </div>
                <p class="text-muted"><small>{{ __('Share this file quickly to:') }}</small></p>
                <div class="btn-toolbar">
                    <a class="btn btn-light" href="https://www.facebook.com/sharer.php?u={{ $upload->url }}" role="button" target="_blank">
                        <i class="fab fa-facebook"></i>
                    </a>
                    <a class="btn btn-light ms-1" href="https://twitter.com/intent/tweet?url={{ $upload->url }}" role="button" target="_blank">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a class="btn btn-light ms-1" href="whatsapp://send?text={{ $upload->url }}" role="button">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                    <a class="btn btn-light ms-1" href="sms:?body={{ $upload->url }}" role="button">
                        <i class="fas fa-mobile"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>
@endsection
