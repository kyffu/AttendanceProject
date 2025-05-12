@extends('errors::layout')

@section('title', '503 - Service Unavailable')
@section('titleM')
<h1 class="mb-2 mx-2" style="line-height: 6rem;font-size: 6rem;">503</h1>
<h4 class="mb-2 mx-2">Layanan tidak tersedia</h4>
@stop
@section('message','Server sedang sibuk, coba lagi nanti!')
@section('url',asset('assets/img/illustrations/girl-doing-yoga-light.png'))