@extends('layouts.app')

@section('content')
    @include('layouts.baseLayout') <!-- It Contains 2 <div> tags -->

        <h1 class="text-white text-3xl pt-8">Oh no!</h1>
        <h2 class="text-blue-300">Enter your reset token to reset password</h2>

        <form class="pt-8" method="POST" action="{{ route('check') }}">

            @csrf

            <div class="relative">
                    <label class="text-blue-500 text-xs font-bold pl-3 pt-2 absolute uppercase" for="user_name">user name</label>
                    <input id="user_name" type="text" class="p-3 pt-8 w-full bg-blue-800 text-gray-100  rounded focus:outline-none focus:bg-blue-700 @error('user_name') border border-red-600 @enderror" name="user_name">
        
                    @error('user_name')
                        <span class="text-red-600 text-xs" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
            </div>

            <div class="relative pt-3">
                <label class="text-blue-500 text-xs font-bold pl-3 pt-2 absolute uppercase" for="reset_token">reset token</label>
                <input id="reset_token" type="text" class="p-3 pt-8 w-full bg-blue-800 text-gray-100  rounded focus:outline-none focus:bg-blue-700 @error('reset_token') border border-red-600 @enderror" name="reset_token">

                @error('reset_token')
                    <span class="text-red-600 text-xs" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            
            <div class="pt-6">
                <button type="submit" class="py-2 px-3 w-full text-left bg-gray-400 text-blue-800 font-bold rounded uppercase">send request</button>
            </div>

            <div class="pt-8 flex justify-between text-white text-sm font-bold">
                <a class="hover:text-blue-200" href="{{ route('login') }}">
                    Login
                </a>
                    
                <a class="hover:text-blue-200" href="{{ route('register') }}">
                    Register
                </a>
            </div>

        </form>

    </div>
</div>
@endsection
