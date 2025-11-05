@extends('layouts.master')
@section('title') {{ $pagename }} @endsection
@section('css')
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
<link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
@endsection
@section('content')

@component('components.breadcrumb')
@slot('li_1') Mobilizers @endslot
@slot('title') {{ $pagename }} @endslot
@endcomponent

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">a
                <h5 class="card-title mb-0">{{ $committee_name }} Dashboard (Total Members: {{ number_format($total_members) }})</h5>
            </div>
            <div class="card-body">
                <table id="committee-dashboard-table" class="display dt-responsive align-middle table-hover table table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Market Name</th>
                            <th>Ward</th>
                            <th>Chair Name</th>
                            <th>Chair Number</th>
                            <th>Male Traders</th>
                            <th>Female Traders</th>
                            <th>Total Traders</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($marketData as $market)
                        <tr>
                            <td>{{ $market->market_name }}</td>
                            <td>{{ $market->ward_name ?? 'N/A' }}</td>
                            <td>{{ $market->chair_name }}</td>
                            <td>{{ $market->chair_phone }}</td>
                            <td>{{ $market->male_traders }}</td>
                            <td>{{ $market->female_traders }}</td>
                            <td>{{ $market->total_traders }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
@section('script')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        $('#committee-dashboard-table').DataTable({
            responsive: true,
            // Additional DataTable options if needed
        });
    });
</script>
@endsection