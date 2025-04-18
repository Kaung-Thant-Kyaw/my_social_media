@extends('user.layouts.master')

@section('content')
    <h1>Welcme to NewFeed</h1>
    <form action="{{ route('logout') }}" method="post">
        @csrf
        <button type="submit">Logout</button>
    </form>
@endsection
