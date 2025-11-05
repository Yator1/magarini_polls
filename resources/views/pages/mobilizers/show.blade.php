{{-- resources/views/pages/mobilizers/show.blade.php --}}
{{-- Simple show page for single mobilizer details. --}}

@extends('layouts.master')
@section('title') Mobilizer Details @endsection
@section('content')

@component('components.breadcrumb')
@slot('li_1') Mobilizers @endslot
@slot('title') Mobilizer Details @endslot
@endcomponent

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Mobilizer: {{ $mobilizer->first_name }} {{ $mobilizer->middle_name }} {{ $mobilizer->last_name }}</h5>
            </div>
            <div class="card-body">
                <p><strong>Role:</strong> {{ $mobilizer->role->name ?? $mobilizer->role_id }}</p>
                <p><strong>User Type:</strong> {{ $mobilizer->user_type }}</p>
                <p><strong>Gender:</strong> {{ $mobilizer->gender == 'M' ? 'Male' : 'Female' }}</p>
                <p><strong>Market:</strong> {{ $mobilizer->market->name ?? 'N/A' }}</p>
                <p><strong>Ward:</strong> {{ $mobilizer->Ward->name ?? 'N/A' }}</p>
                @php
                    use Carbon\Carbon;
                @endphp

                <p><strong>Age:</strong> 
                    {{ $mobilizer->d_o_b ? Carbon::parse($mobilizer->d_o_b)->age : 'N/A' }}
                </p>

                <p><strong>Phone No:</strong> {{ $mobilizer->phone_no }}</p>
                <p><strong>ID No:</strong> {{ $mobilizer->id_no }}</p>
                <!-- Add more fields as needed -->
            </div>
        </div>
    </div>
</div>

@endsection