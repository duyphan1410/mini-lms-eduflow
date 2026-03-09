@extends('layouts.app')

@section('title', 'Trang chủ')

@push('styles')
    @vite(['resources/css/home.css'])
@endpush

@push('scripts')
    @vite(['resources/js/home.js'])
@endpush

@section('content')

@endsection