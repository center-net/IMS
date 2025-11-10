@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-3">
                <div class="card-header">{{ __('representatives.title') }}</div>
                <div class="card-body">
                    @livewire('representatives.representative-list')
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            @canany(['create-representatives', 'edit-representatives'])
            <div class="card">
                <div class="card-header">{{ __('representatives.title') }}</div>
                <div class="card-body">
                    @livewire('representatives.representative-form')
                </div>
            </div>
            @endcanany
        </div>
    </div>
</div>
@endsection

