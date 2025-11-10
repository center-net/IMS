@extends('layouts.app')

@section('title', __('menu.offers'))

@section('content')
<div class="container py-3">
    <div class="row g-3 align-items-start">
        <div class="col-12 col-lg-8">
            @livewire('offers.offer-list')
        </div>
        @canany(['create-offers','edit-offers'])
        <div class="col-12 col-lg-4">
            @livewire('offers.offer-form')
        </div>
        @endcanany
    </div>
</div>
@endsection
