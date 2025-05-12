@extends('errors::layout')

@section('title','404 - Not Found')
@section('titleM')
<h1 class="mb-2 mx-2" style="line-height: 6rem;font-size: 6rem;">404</h1>
<h4 class="mb-2 mx-2">Halaman tidak ditemukan! ⚠️</h4>
@stop
@section('message','Tidak dapat menemukan halaman yang Anda cari')
@section('url',asset('assets/img/illustrations/page-misc-error-light.png'))
