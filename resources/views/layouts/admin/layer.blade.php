@extends('layouts.admin.base')

@section('head')
    <title>@if (isset($siteName)) {{$siteName}} @else "南海青商会" @endif</title>
@endsection

@section('body')
    @yield('content')
@endsection