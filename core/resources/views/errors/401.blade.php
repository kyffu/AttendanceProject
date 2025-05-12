@extends('errors::layout')

@section('title', '401 - Unauthorized')
@section('titleM')
<h1 class="mb-2 mx-2" style="line-height: 6rem;font-size: 6rem;">401</h1>
<h4 class="mb-2 mx-2">Anda tidak memiliki otorisasi halaman ini! 🔐</h4>
@stop
@section('message','Anda tidak memiliki otorisasi untuk mengakses halaman ini. Silakan kembali!')
@section('url',asset('assets/img/illustrations/forbidden.png'))
