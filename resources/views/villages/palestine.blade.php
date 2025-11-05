@extends('layouts.app')

@section('title', 'قرى فلسطين')

@section('content')
<div class="container py-3">
    <div class="row g-3 align-items-start">
        <div class="col-12 col-lg-8">
            @livewire('villages.palestine-village-list')
        </div>
        <div class="col-12 col-lg-4">
            @livewire('villages.palestine-village-form')
        </div>
    </div>
</div>
@endsection
