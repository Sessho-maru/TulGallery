@extends('layouts.app')

@section('content')
    @include('Layouts.baseLayout')

        <h2 class="text-blue-300 pt-8">Enter new password</h2>

        <form class="pt-8" method="POST" action="{{ route('reset', ['user_id' => $user_id]) }}">
                
            @csrf

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
                <label class="text-blue-500 text-xs pl-3 pt-2 absolute uppercase font-bold" for="password_confirm">re-enter password</label>
                <input id="password_confirm" type="password" class="w-full p-3 pt-8 bg-blue-800 text-gray-100 focus:outline-none focus:bg-blue-700 rounded" name="password_confirm" placeholder="Confirm">
            </div>

            <div class="pt-6">
                <button type="submit" class="w-full py-2 px-3 text-left bg-gray-400 text-blue-800 font-bold rounded uppercase font-bold">Register</button>
            </div>
        
        </form>

    </div>
</div>
@endsection