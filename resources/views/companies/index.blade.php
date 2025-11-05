@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            @livewire('companies.company-list')
        </div>
    </div>
</div>
@endsection
