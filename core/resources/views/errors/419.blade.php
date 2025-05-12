@extends('errors::layout')

@section('title', '419 - Page Expired')
@section('titleM')
<h1 class="mb-2 mx-2" style="line-height: 6rem;font-size: 6rem;">419</h1>
<h4 class="mb-2 mx-2">Halaman Kedaluwarsa</h4>
@stop
@section('message','Maaf sesi Anda telah kedaluwarsa, silakan muat ulang halaman ini!')
@section('url',asset('assets/img/illustrations/error.png'))
