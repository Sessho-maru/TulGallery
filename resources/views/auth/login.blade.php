@extends('layouts.app')

@section('content')
    @include('layouts.baseLayout') <!-- It Contains 2 <div> tags -->

        <h1 class="text-white text-3xl pt-8">tekaluk</h1>
        <h2 class="text-blue-300">Enter your credential below</h2>

        <form class="pt-8" method="POST" action="{{ route('login') }}" >
            @csrf

            <div class="relative">
                <label for="user_name" class="uppercase text-blue-500 text-xs font-bold absolute pl-3 pt-2">User name</label>

                <div>
                    <input id="user_name" type="text" class="pt-8 w-full rounded p-3 bg-blue-800 text-gray-100 outline-none focus:bg-blue-700" name="user_name" value="{{ old('user_name') }}" autocomplete="user_name" autofocus placeholder="user name">

                    @error('user_name')
                        <span class="text-red-600 text-sm pl-1" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="relative pt-3">
                <label for="password" class="uppercase text-blue-500 text-xs font-bold absolute pl-3 pt-2">Password</label>

                <div>
                    <input id="password" type="password" class="pt-8 w-full rounded p-3 bg-blue-800 text-gray-100 outline-none focus:bg-blue-700" name="password" value="{{ old('password') }}" autocomplete="password" autofocus placeholder="password">

                    @error('password')
                        <span class="text-red-600 text-sm pl-1" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="pt-8">
                <button type="submit" class="w-full bg-gray-400 py-2 px-3 text-left uppercase rounded text-gray-100 text-blue-800 font-bold">
                        Login
                </button>
            </div>

            <div class="flex justify-between pt-8 text-white text-sm font-bold">
                <a href="{{ route('password.request') }}">Forgot Your Password?</a> <!-- It calls viwes/auth/passwords/email.blade.php -->
                <a href="{{ route('register') }}">Register</a>
            </div>
    
        </form>

    </div>
</div>
@endsection
