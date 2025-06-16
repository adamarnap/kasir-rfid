@extends('layouts.operator')
@section('title', 'Home')
@section('breadcrumb')
@php
$breadcrumb = [
    [
    'title' => 'Home',
    'active' => true,
    'url' => ''
    ],
];
@endphp
@foreach ($breadcrumb as $item)
<li class="breadcrumb-item {{ $item['active'] ? 'active' : '' }}"><a href="{{$item['url']}}">{{ $item['title'] }}</a></li>
@endforeach
@endsection
@section('content')

@endsection
