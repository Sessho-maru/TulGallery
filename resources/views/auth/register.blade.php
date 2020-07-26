@extends('layouts.app')

@section('content')
    @include('layouts.baseLayout') <!-- It Contains 2 <div> tags -->

        <h1 class="text-white text-3xl pt-8">Join</h1>
        <h2 class="text-blue-300">Enter your information below</h2>

        <form class="pt-8" method="POST" action="{{ route('register') }}">
            
            @csrf

            <div class="relative">
                <label class="text-blue-500 text-xs pl-3 pt-2 absolute uppercase font-bold" for="user_name">User name</label>
                <input id="user_name" type="text" class="w-full p-3 pt-8 bg-blue-800 text-gray-100 focus:outline-none focus:bg-blue-700 rounded 
                                                @error('user_name') border border-red-600 @enderror" name="user_name" placeholder="Your Name">
                @error('user_name')
                    <span class="text-red-600 text-xs" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="relative pt-3">
                <label class="text-blue-500 text-xs pl-3 pt-2 absolute uppercase font-bold" for="reset_token">reset token</label>
                <input id="reset_token" type="text" class="w-full p-3 pt-8 bg-blue-800 text-gray-100 focus:outline-none focus:bg-blue-700 rounded 
                                                @error('reset_token') border border-red-600 @enderror" name="reset_token" placeholder="Requires to reset password, 6 digits number">
                @error('reset_token')
                    <span class="text-red-600 text-xs" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="relative pt-3">
                <label class="text-blue-500 text-xs pl-3 pt-2 absolute uppercase font-bold" for="password">password</label>
                <input id="password" type="password" class="w-full p-3 pt-8 bg-blue-800 text-gray-100 focus:outline-none focus:bg-blue-700 rounded 
                                                @error('password') border border-red-600 @enderror" name="password" placeholder="Password">
                @error('password')
                    <span class="text-red-600 text-xs" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="relative pt-3">
                <label class="text-blue-500 text-xs pl-3 pt-2 absolute uppercase font-bold" for="password-confirm">re-enter password</label>
                <input id="password-confirm" type="password" class="w-full p-3 pt-8 bg-blue-800 text-gray-100 focus:outline-none focus:bg-blue-700 rounded" name="password_confirmation" placeholder="Confirm">
            </div>

            <div class="pt-6">
                <button type="submit" class="w-full py-2 px-3 text-left bg-gray-400 text-blue-800 font-bold rounded uppercase font-bold">Register</button>
            </div>

            <div class="pt-8 flex justify-between text-white text-sm font-bold">
                <a class="hover:text-blue-200" href="{{ route('password.request') }}">
                    Forgot Your Password?
                </a>

                <a class="hover:text-blue-200" href="{{ route('login') }}">
                    Login
                </a>
            </div>
        
        </form>

    </div>
</div>
@endsection