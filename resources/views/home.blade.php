@extends('layouts.app')

@section('content')
    <?php $user = auth()->user(); ?>
    @if ($user)
        <App v-bind:user="{{ $user }}"></App>
    @else
        <App></App>
    @endif
    {{-- SPA application: laravel home.blade.php calls vue's App Component --}}
@endsection
