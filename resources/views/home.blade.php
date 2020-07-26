@extends('layouts.app')

@section('content')
    @if (auth()->user())
        <App v-bind:user="{{ auth()->user() }}"></App>
    @else
        <App></App>
    @endif
    {{-- SPA application: laravel home.blade.php calls vue's App Component --}}
@endsection
