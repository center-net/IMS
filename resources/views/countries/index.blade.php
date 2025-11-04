@extends('layouts.app')

@section('title', __('menu.countries'))

@section('content')
<div class="container py-3">
    <div class="row g-3">
        <div class="col-12 col-lg-8">
            @livewire('countries.country-list')
        </div>
        <div class="col-12 col-lg-4">
            @livewire('countries.country-form')
        </div>
    </div>
@endsection

