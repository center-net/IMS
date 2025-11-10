@extends('layouts.app')

@section('title', __('menu.suppliers'))

@section('content')
<div class="container py-3">
    <div class="row g-3 align-items-start">
        <div class="col-12 col-lg-8">
            @livewire('suppliers.supplier-list')
        </div>
        @canany(['create-suppliers','edit-suppliers'])
        <div class="col-12 col-lg-4">
            @livewire('suppliers.supplier-form')
        </div>
        @endcanany
    </div>
    @if(session('message'))
        <div class="alert alert-success mt-3">{{ session('message') }}</div>
    @endif
@endsection

