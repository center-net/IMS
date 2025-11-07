@extends('layouts.app')

@section('title', __('villages.palestine_title'))

@section('content')
<div class="container py-3">
    <div class="row g-3 align-items-start">
        <div class="col-12 col-lg-8">
            @livewire('villages.palestine-village-list')
        </div>
        <div class="col-12 col-lg-4">
            @canany(['create-villages', 'edit-villages'])
                @livewire('villages.palestine-village-form')
            @endcanany
        </div>
    </div>
</div>
@endsection
