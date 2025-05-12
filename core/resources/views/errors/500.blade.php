@extends('errors::layout')

@section('title', '500 - Server Error')
@section('titleM')
<h1 class="mb-2 mx-2" style="line-height: 6rem;font-size: 6rem;">500</h1>
<h4 class="mb-2 mx-2">Server Error</h4>
@stop
@section('message','Oops, Server mengalami kesalahan internal dan tidak dapat menyelesaikan permintaan Anda')
@section('url',asset('assets/img/illustrations/girl-doing-yoga-light.png'))

