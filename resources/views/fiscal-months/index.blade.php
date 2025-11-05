@extends('layouts.app')

@section('content')
    <div class="container-xxl">
        <h4 class="mb-3">{{ __('fiscal_months.title') }}</h4>
        <div class="row g-3">
            <div class="col-lg-7">
                @livewire('fiscal-months.fiscal-month-list')
            </div>
            <div class="col-lg-5">
                @livewire('fiscal-months.fiscal-month-form')
            </div>
        </div>
    </div>
@endsection
