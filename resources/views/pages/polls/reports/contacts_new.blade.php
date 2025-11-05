@extends('layouts.master')

@section('content')
@component('components.breadcrumb')
@slot('li_1') Reports @endslot
@slot('title') Contacts @endslot
@endcomponent

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">All Contacts</h5>
    </div>

    <div class="card-body">

        <div class="row mb-3">
            <div class="col-md-3">
                <select class="form-select" id="filter_agent">
                    <option value="">Filter by Agent</option>
                    @foreach($agents as $a)
                        <option value="{{ $a->id }}">{{ $a->first_name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <select class="form-select" id="filter_status">
                    <option value="">Filter by Status</option>
                    <option value="0">Not Contacted</option>
                    <option value="1">Picked</option>
                    <option value="2">Declined / Unreachable</option>
                    <option value="3">Missed Call</option>
                    <option value="7">Call Back</option>
                    <option value="8">Invalid Number</option>
                </select>
            </div>
        </div>

        <table id="contactsTable" class="table table-bordered table-hover w-100">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Phones</th>
                    <th>Status</th>
                    <th>Agent</th>
                    <th>Action</th>
                </tr>
            </thead>
        </table>

    </div>
</div>
@endsection

@section('script')
<script>
$(function () {
    let table = $('#contactsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('contacts.data') }}",
            type: "POST",
            data: function (d) {
                d._token = "{{ csrf_token() }}";
                d.agent_id = $('#filter_agent').val();
                d.call_status = $('#filter_status').val();
            }
        },
        columns: [
            {data: 'DT_RowIndex', name:'DT_RowIndex', orderable:false, searchable:false},
            {data: 'name', name: 'name'},
            {data: 'phones', name: 'phone_no'},
            {data: 'status', name: 'call_status'},
            {data: 'agent', name: 'called_by'},
            {data: 'action', orderable:false, searchable:false},
        ]
    });

    $('#filter_agent, #filter_status').change(function() {
        table.draw();
    });
});
</script>
@endsection
