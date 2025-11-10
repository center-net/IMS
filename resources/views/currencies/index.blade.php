@extends('layouts.app')

@section('title', __('currencies.title'))

@section('content')
<div class="container py-3">
    <div class="row g-3">
        <div class="col-12 col-lg-8">
            @livewire('currencies.currency-list')
        </div>
        <div class="col-12 col-lg-4">
            @livewire('currencies.currency-form')
        </div>
    </div>
</div>
@endsection
