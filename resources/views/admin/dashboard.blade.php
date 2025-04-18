@extends('admin.layouts.master')

@section('content')
    <h1 class="text-3xl text-red-500">Admin Dashboard</h1>
    <form action="{{ route('logout') }}" method="post">
        @csrf
        <button type="submit">Logout</button>
    </form>
@endsection
