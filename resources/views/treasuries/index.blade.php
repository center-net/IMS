@extends('layouts.app')

@section('content')
<div class="container-xxl">
    <h4 class="mb-3">{{ __('treasuries.title') }}</h4>
    <div class="row g-3">
        <div class="col-lg-7">
            @livewire('treasuries.treasury-list')
        </div>
        @canany(['create-treasuries','edit-treasuries'])
        <div class="col-lg-5">
            @livewire('treasuries.treasury-form')
        </div>
        @endcanany
    </div>
@endsection
