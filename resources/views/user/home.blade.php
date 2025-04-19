@extends('user.layouts.master')

@section('content')
    <nav>
        <a href="{{ route('user.profile.show', Auth::user()->id) }}">Your Profile</a>
    </nav>
    <h1>Welcme to NewFeed</h1>
    <form action="{{ route('logout') }}" method="post">
        @csrf
        <button type="submit">Logout</button>
    </form>
@endsection
