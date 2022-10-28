@extends('layouts.app')

@section('body')
    <header class="sticky-top shadow-sm">
        @include('partials.navbar.backend')
    </header>
    @yield('content')
@endsection
