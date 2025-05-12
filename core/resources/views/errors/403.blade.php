@extends('errors::layout')

@section('title', '403 - Forbidden')
@section('titleM')
<h1 class="mb-2 mx-2" style="line-height: 6rem;font-size: 6rem;">403</h1>
<h4 class="mb-2 mx-2">Anda tidak memiliki izin Akses! 🔐</h4>
@stop
@section('message','Anda tidak memiliki izin untuk mengakses halaman ini. Silakan kembali!')
@section('url',asset('assets/img/illustrations/forbidden.png'))
