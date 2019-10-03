@extends('layouts.app')

@section('content')
    <App v-bind:user="{{ auth()->user() }}"></App>
    {{-- SPA application: laravel home.blade.php calls vue's App Component --}}
@endsection
