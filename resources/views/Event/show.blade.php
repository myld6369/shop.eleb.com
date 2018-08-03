@extends('default')
@section('contents')
    @include('_nav')
    @include('_errors')
    @include('_msg')
    <div class="container">
   <h1>{{$event->title}}</h1>
        <br>
    {!! $event->content !!}
    </div>
@endsection
