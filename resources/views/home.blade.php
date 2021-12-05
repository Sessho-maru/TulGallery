@extends('layouts.app')

@section('content')
    <?php $user = auth()->user(); ?>
    @if ($user)
        <App v-bind:user="{{ $user }}"></App>
    @else
        <App></App>
    @endif
@endsection
