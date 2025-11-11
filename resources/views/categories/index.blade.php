@extends('layouts.app')

@section('title', __('menu.categories'))

@section('content')
<div class="container py-3">
    <div class="row g-3">
        <div class="col-12">
            @livewire('categories.category-list')
        </div>
    </div>
    @if(session('message'))
        <div class="alert alert-success mt-3">{{ session('message') }}</div>
    @endif
</div>
@endsection
