@extends('layouts.app', [
    'html_class' => 'h-100',
    'body_class' => 'h-100 d-flex',
])

@section('body')
    <div class="container my-auto py-3">
        <div class="row justify-content-end">
            <div class="col-md-6 col-xl-4">
                <div class="mb-3">
                    @yield('content')
                </div>
                <p class="text-right mb-0">
                    <a href="{{ url('/') }}">{{ config('app.name', 'Laravel') }}</a> &copy; {{ date('Y') }}
                </p>
            </div>
        </div>
    </div>
@endsection
