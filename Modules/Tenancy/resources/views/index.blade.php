@extends('tenancy::layouts.master')

@section('content')
    <h1>Hello World</h1>

    <p>Module: {!! config('tenancy.name') !!}</p>
@endsection
