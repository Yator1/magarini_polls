{{-- resources/views/pages/mobilizers/index.blade.php --}}
{{-- Customized from provided users index. Added filter form at top. Table populated via AJAX on load and on filter submit. --}}
{{-- Filters: user_type, role_id, gender, ward_id, market_id, min_age, max_age. --}}
{{-- Table columns: Name, Role, User Type, Gender, Market, Ward, Age (calculated), Phone, ID No, Actions (view/edit/delete based on gates). --}}

@extends('layouts.master')
@section('title') Mobilizers @endsection
@section('css')
<link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
<link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
@endsection
@section('content')

@component('components.breadcrumb')
@slot('li_1') Tables @endslot
@slot('title') Mobilizers @endslot
@endcomponent

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">All Mobilizers</h5>
            </div>
            <div class="card-body">
                <!-- Filter Form -->
                <form id="filter-form">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label>User Type</label>
                            <select name="user_type" class="form-control">
                                <option value="">All</option>
                                @foreach($userTypes as $type)
                                <option value="{{ $type }}">{{ $type }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Role</label>
                            <select name="role_id" class="form-control">
                                <option value="">All</option>
                                @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Gender</label>
                            <select name="gender" class="form-control">
                                <option value="">All</option>
                                <option value="M">Male</option>
                                <option value="F">Female</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Ward</label>
                            <select name="ward_id" class="form-control">
                                <option value="">All</option>
                                @foreach($wards as $ward)
                                <option value="{{ $ward->id }}">{{ $ward->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Market</label>
                            <select name="market_id" class="form-control">
                                <option value="">All</option>
                                @foreach($markets as $market)
                                <option value="{{ $market->id }}">{{ $market->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Min Age</label>
                            <input type="number" name="min_age" class="form-control" placeholder="Min Age">
                        </div>
                        <div class="col-md-3">
                            <label>Max Age</label>
                            <input type="number" name="max_age" class="form-control" placeholder="Max Age">
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-primary mt-4">Filter</button>
                        </div>
                    </div>
                </form>

                <!-- Table -->
                <table id="mobilizers-table" class="display dt-responsive align-middle table-hover table table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Role</th>
                            <th>User Type</th>
                            <th>Gender</th>
                            <th>Market</th>
                            <th>Ward</th>
                            <th>Age</th>
                            <th>Phone No</th>
                            <th>ID No</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Populated via AJAX -->
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
        var table = $('#mobilizers-table').DataTable({
            responsive: true,
            // Other DataTable options
        });

        function loadData(filters = {}) {
            $.ajax({
                url: "{{ route('mobilizers.filter') }}",
                method: 'POST',
                data: filters,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(data) {
                    table.clear();
                    $.each(data, function(index, mobilizer) {
                        var age = mobilizer.d_o_b ? new Date().getFullYear() - new Date(mobilizer.d_o_b).getFullYear() : 'N/A';
                        var actions = '';
                        actions += '<a href="' + '{{ url("mobilizers") }}/' + mobilizer.id + '" class="dropdown-item"><i class="ri-eye-fill"></i> View</a>';
                        actions += '<a href="' + '{{ url("mobilizers") }}/' + mobilizer.id + '/edit" class="dropdown-item"><i class="ri-pencil-fill"></i> Edit</a>';
                        @if(Gate::allows('canManageMobilizers'))
                        actions += '<form method="POST" action="' + '{{ url("mobilizers") }}/' + mobilizer.id + '" style="display:inline;"><@csrf @method("DELETE")><button type="submit" class="dropdown-item"><i class="ri-delete-bin-fill"></i> Delete</button></form>';
                        @endif

                        table.row.add([
                            mobilizer.first_name + ' ' + (mobilizer.middle_name || '') + ' ' + mobilizer.last_name,
                            mobilizer.role ? mobilizer.role.name : mobilizer.role_id,
                            mobilizer.user_type,
                            mobilizer.gender == 'M' ? 'Male' : 'Female',
                            mobilizer.market ? mobilizer.market.name : 'N/A',
                            mobilizer.ward ? mobilizer.ward.name : 'N/A',
                            age,
                            mobilizer.phone_no,
                            mobilizer.id_no,
                            '<div class="dropdown"><button class="btn btn-soft-secondary btn-sm dropdown" type="button" data-bs-toggle="dropdown"><i class="ri-more-fill"></i></button><ul class="dropdown-menu">' + actions + '</ul></div>'
                        ]);
                    });
                    table.draw();
                }
            });
        }

        // Initial load
        loadData();

        // On filter submit
        $('#filter-form').submit(function(e) {
            e.preventDefault();
            var filters = $(this).serialize();
            loadData(filters);
        });
    });
</script>
@endsection