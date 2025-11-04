@extends('layouts.app')

@section('title', __('menu.cities'))

@section('content')
<div class="container py-3">
    <div class="row g-3 align-items-start">
        <div class="col-12 col-lg-8">
            @livewire('cities.city-list')
        </div>
        <div class="col-12 col-lg-4">
            @livewire('cities.city-form')
        </div>
    </div>
</div>
@endsection
