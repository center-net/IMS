@extends('layouts.app')

@section('title', __('cities.palestine_title'))

@section('content')
<div class="container py-3">
    <div class="row g-3 align-items-start">
        <div class="col-12 col-lg-8">
            @livewire('cities.palestine-city-list')
        </div>
        <div class="col-12 col-lg-4">
            @livewire('cities.palestine-city-form')
        </div>
    </div>
</div>
@endsection
