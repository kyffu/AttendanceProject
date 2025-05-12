@extends('errors::layout')

@section('title', '429 - Too Many Request')
@section('titleM')
<h1 class="mb-2 mx-2" style="line-height: 6rem;font-size: 6rem;">429</h1>
<h4 class="mb-2 mx-2">Terlalu Banyak Permintaan</h4>
@stop
@section('message','Terlalu Banyak Permintaan, Silakan coba lagi nanti!')
@section('url',asset('assets/img/illustrations/error.png'))

