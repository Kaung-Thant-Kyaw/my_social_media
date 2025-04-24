@extends('user.layouts.master')

@section('content')
    @include('partials.post', ['post' => $post, 'fullView' => true, 'showComments' => true])
@endsection
