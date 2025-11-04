@extends('layouts.app')

@section('title', __('menu.permissions'))

@section('content')
<div class="container py-3">
    <div class="row g-3">
        <div class="col-12 col-lg-8">
            @livewire('permissions.permission-list')
        </div>
        <div class="col-12 col-lg-4">
            @livewire('permissions.permission-form')
        </div>
    </div>
</div>
@endsection

