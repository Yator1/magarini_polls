@extends('layouts.master-without-nav')
@section('title') Overall Mobilizer Dashboard @endsection
@section('css')
    <link href="{{ URL::asset('build/libs/jsvectormap/css/jsvectormap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('build/libs/swiper/swiper-bundle.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ URL::asset('build/libs/@simonwep/pickr/themes/classic.min.css') }}" />
    <link rel="stylesheet" href="{{ URL::asset('build/libs/@simonwep/pickr/themes/monolith.min.css') }}" />
    <link rel="stylesheet" href="{{ URL::asset('build/libs/@simonwep/pickr/themes/nano.min.css') }}" />
    <style>
        .card-animate:hover {
            transform: translateY(-5px);
            transition: transform 0.3s ease;
        }
        .card-body a {
            color: #fff !important;
            text-decoration: none;
        }
        .nav-tabs-custom .nav-link {
            color: #fff !important;
            font-weight: 500;
        }
        .nav-tabs-custom.nav-success .nav-link.active {
            background-color: #28a745 !important;
            border-color: #28a745 !important;
        }
        .nav-tabs-custom.nav-info .nav-link.active {
            background-color: #17a2b8 !important;
            border-color: #17a2b8 !important;
        }
    </style>
@endsection
@section('content')
    <div class="row">
        <div class="col">
            <div class="h-100">
                <div class="card">
                    <div class="card-header">
                        <div class="row mb-3 pb-1">
                            {{-- <div class="col-12">
                                <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                                    <div class="flex-grow-1">
                                        <h4 class="fs-16 mb-1">Welcome Back, {{ Auth::user()->first_name . ' ' . Auth::user()->last_name }}!</h4>
                                    </div>
                                </div>
                            </div> --}}
                            <div class="col-12">
                                <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                                    <div class="flex-grow-1">
                                        <br>
                                        <h4 class="fs-16 mb-1">Overall Mobilizer Dashboard </h4>
                                            {{--  for: {{ $date1->format('Y-m-d') . ' - ' . $date2->format('Y-m-d') }}</h4> --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="card-body">
                        <form action="{{ route('mobilizers.dashboard') }}" method="POST">
                            @csrf
                            <div class="row gy-3">
                                <div class="col-lg-6">
                                    <label class="form-label mb-0">Start Date</label>
                                    <input type="text" name="date_1" class="form-control" data-provider="flatpickr" data-date-format="Y-m-d" value="{{ $date1->format('Y-m-d') }}">
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-label mb-0">End Date</label>
                                    <input type="text" name="date_2" class="form-control" data-provider="flatpickr" data-date-format="Y-m-d" value="{{ $date2->format('Y-m-d') }}">
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-lg-12">
                                    <button type="submit" class="btn btn-primary">Filter</button>
                                </div>
                            </div>
                        </form>
                    </div> --}}
                </div>

                <!-- Summary Statistics Cards -->
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title mb-0">Overall Mobilizer Statistics</h4>
                            </div>
                            <div class="card-body">
                                    @foreach($roleGroups as $roleId => $roleName)
                                    <div class="row row-cols-xxl-6 row-cols-lg-3 row-cols-md-2 row-cols-1">
                                        <div class="col">
                                            <div class="card card-animate bg-primary text-white">
                                                <div class="card-body">
                                                    <h5 style="color: white;" class="fs-15 fw-semibold">{{ $roleName }} Total</h5>
                                                    <p style="color: white;" class="mb-0"><span class="fw-medium">{{ $summaryStats[$roleId]['total'] }}</span></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="card card-animate bg-success text-white">
                                                <div class="card-body">
                                                    <h5 style="color: white;" class="fs-15 fw-semibold">Male {{ $roleName }}</h5>
                                                    <p style="color: white;" class="mb-0"><span class="fw-medium">{{ $summaryStats[$roleId]['male'] }}</span></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="card card-animate bg-warning text-white">
                                                <div class="card-body">
                                                    <h5 style="color: white;" class="fs-15 fw-semibold">Female {{ $roleName }}</h5>
                                                    <p style="color: white;" class="mb-0"><span class="fw-medium">{{ $summaryStats[$roleId]['female'] }}</span></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="card card-animate bg-info text-white">
                                                <div class="card-body">
                                                    <h5 style="color: white;" class="fs-15 fw-semibold">Unknown {{ $roleName }}</h5>
                                                    <p style="color: white;" class="mb-0"><span class="fw-medium">{{ $summaryStats[$roleId]['unknown'] }}</span></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                <div class="row row-cols-xxl-6 row-cols-lg-3 row-cols-md-2 row-cols-1">
                                    <div class="col">
                                        <div class="card card-animate bg-secondary text-white">
                                            <div class="card-body">
                                                <h5 style="color: white;" class="fs-15 fw-semibold">Grand Total</h5>
                                                <p style="color: white;" class="mb-0"><span class="fw-medium">{{ $grandTotal }}</span></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="card card-animate bg-dark text-white">
                                            <div class="card-body">
                                                <h5 style="color: white;" class="fs-15 fw-semibold">Total Male</h5>
                                                <p style="color: white;" class="mb-0"><span class="fw-medium">{{ $totalMale }}</span></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="card card-animate bg-danger text-white">
                                            <div class="card-body">
                                                <h5 style="color: white;" class="fs-15 fw-semibold">Total Female</h5>
                                                <p style="color: white;" class="mb-0"><span class="fw-medium">{{ $totalFemale }}</span></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="card card-animate bg-info text-white">
                                            <div class="card-body">
                                                <h5 style="color: white;" class="fs-15 fw-semibold">Total Unknown</h5>
                                                <p style="color: white;" class="mb-0"><span class="fw-medium">{{ $totalUnknown }}</span></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Per Subcounty -->
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-header bg-soft-primary">
                                <h4 class="card-title mb-0">Per Subcounty</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover" id="subcountyTable">
                                        <thead>
                                            <tr>
                                                <th>Subcounty</th>
                                                @foreach($roleGroups as $roleId => $roleName)
                                                <th>{{ $roleName }} (M/F/U/Total)</th>
                                                @endforeach
                                                <th>Grand Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($subcountyData as $subcounty)
                                                <tr>
                                                    <td>{{ $subcounty->name ?? 'Unknown' }}</td>
                                                    @foreach($roleGroups as $roleId => $roleName)
                                                    <td>{{ $subcounty->{$roleId . '_male'} ?? 0 }} / {{ $subcounty->{$roleId . '_female'} ?? 0 }} / {{ $subcounty->{$roleId . '_unknown'} ?? 0 }} / {{ $subcounty->{$roleId . '_total'} ?? 0 }}</td>
                                                    @endforeach
                                                    <td>{{ $subcounty->grand_total ?? 0 }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="{{ count($roleGroups) + 2 }}">No data available</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <div id="subcountyPieChart" class="apex-charts"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Per Ward -->
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-header bg-soft-info">
                                <h4 class="card-title mb-0">Per Ward</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover" id="wardTable">
                                        <thead>
                                            <tr>
                                                <th>Ward</th>
                                                <th>Subcounty</th>
                                                @foreach($roleGroups as $roleId => $roleName)
                                                <th>{{ $roleName }} (M/F/U/Total)</th>
                                                @endforeach
                                                <th>Grand Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($wardData as $ward)
                                                <tr>
                                                    <td>{{ $ward->name ?? 'Unknown' }}</td>
                                                    <td>{{ $ward->subcounty_name ?? 'N/A' }}</td>
                                                    @foreach($roleGroups as $roleId => $roleName)
                                                    <td>{{ $ward->{$roleId . '_male'} ?? 0 }} / {{ $ward->{$roleId . '_female'} ?? 0 }} / {{ $ward->{$roleId . '_unknown'} ?? 0 }} / {{ $ward->{$roleId . '_total'} ?? 0 }}</td>
                                                    @endforeach
                                                    <td>{{ $ward->grand_total ?? 0 }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="{{ count($roleGroups) + 3 }}">No data available</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <div id="wardPieChart" class="apex-charts"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Per Polling Station -->
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-header bg-soft-warning">
                                <h4 class="card-title mb-0">Per Polling Station</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover" id="pollingTable">
                                        <thead>
                                            <tr>
                                                <th>Polling Station</th>
                                                <th>Ward</th>
                                                @foreach($roleGroups as $roleId => $roleName)
                                                <th>{{ $roleName }} (M/F/U/Total)</th>
                                                @endforeach
                                                <th>Grand Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($pollingStationData as $ps)
                                                <tr>
                                                    <td>{{ $ps->name ?? 'Unknown' }}</td>
                                                    <td>{{ $ps->ward_name ?? 'N/A' }}</td>
                                                    @foreach($roleGroups as $roleId => $roleName)
                                                    <td>{{ $ps->{$roleId . '_male'} ?? 0 }} / {{ $ps->{$roleId . '_female'} ?? 0 }} / {{ $ps->{$roleId . '_unknown'} ?? 0 }} / {{ $ps->{$roleId . '_total'} ?? 0 }}</td>
                                                    @endforeach
                                                    <td>{{ $ps->grand_total ?? 0 }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="{{ count($roleGroups) + 3 }}">No data available</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <div id="pollingPieChart" class="apex-charts"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Per Market -->
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-header bg-soft-danger">
                                <h4 class="card-title mb-0">Per Market</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover" id="marketTable">
                                        <thead>
                                            <tr>
                                                <th>Market</th>
                                                <th>Ward</th>
                                                @foreach($roleGroups as $roleId => $roleName)
                                                <th>{{ $roleName }} (M/F/U/Total)</th>
                                                @endforeach
                                                <th>Grand Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($marketData as $market)
                                                <tr>
                                                    <td>{{ $market->market_name ?? 'Unknown' }}</td>
                                                    <td>{{ $market->ward_name ?? 'N/A' }}</td>
                                                    @foreach($roleGroups as $roleId => $roleName)
                                                    <td>{{ $market->{$roleId . '_male'} ?? 0 }} / {{ $market->{$roleId . '_female'} ?? 0 }} / {{ $market->{$roleId . '_unknown'} ?? 0 }} / {{ $market->{$roleId . '_total'} ?? 0 }}</td>
                                                    @endforeach
                                                    <td>{{ $market->grand_total ?? 0 }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="{{ count($roleGroups) + 3 }}">No data available</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <div id="marketPieChart" class="apex-charts"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ URL::asset('build/libs/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/jsvectormap/js/jsvectormap.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/jsvectormap/maps/world-merc.js') }}"></script>
    <script src="{{ URL::asset('build/libs/swiper/swiper-bundle.min.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="{{ URL::asset('build/js/pages/select2.init.js') }}"></script>
    <script src="{{ URL::asset('build/js/pages/datatables.init.js') }}"></script>
    <script src="{{ URL::asset('build/libs/@simonwep/pickr/pickr.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/pages/form-pickers.init.js') }}"></script>
    <script src="{{ URL::asset('build/js/app.js') }}"></script>

    <script>
        // Initialize DataTables
        $('#subcountyTable').DataTable({
            responsive: true,
            paging: true,
            scrollX: true,
            buttons: ['copy', 'excel', 'pdf', 'print'],
            order: [[0, 'asc']] // Sort by Subcounty name
        });

        $('#wardTable').DataTable({
            responsive: true,
            paging: true,
            scrollX: true,
            buttons: ['copy', 'excel', 'pdf', 'print'],
            order: [[0, 'asc']] // Sort by Ward name
        });

        $('#pollingTable').DataTable({
            responsive: true,
            paging: true,
            scrollX: true,
            buttons: ['copy', 'excel', 'pdf', 'print'],
            order: [[0, 'asc']] // Sort by Polling Station name
        });

        $('#marketTable').DataTable({
            responsive: true,
            paging: true,
            scrollX: true,
            buttons: ['copy', 'excel', 'pdf', 'print'],
            order: [[0, 'asc']] // Sort by Market name
        });

        // Pie Charts for Gender Distribution
        var subcountyPieOptions = {
            series: [{{ $totalMale }}, {{ $totalFemale }}, {{ $totalUnknown }}],
            chart: { height: 300, type: 'pie' },
            labels: ['Male', 'Female', 'Unknown'],
            colors: ['#007bff', '#ff69b4', '#6c757d'],
            legend: { position: 'bottom' }
        };
        new ApexCharts(document.querySelector("#subcountyPieChart"), subcountyPieOptions).render();

        var wardPieOptions = {
            series: [{{ $totalMale }}, {{ $totalFemale }}, {{ $totalUnknown }}],
            chart: { height: 300, type: 'pie' },
            labels: ['Male', 'Female', 'Unknown'],
            colors: ['#007bff', '#ff69b4', '#6c757d'],
            legend: { position: 'bottom' }
        };
        new ApexCharts(document.querySelector("#wardPieChart"), wardPieOptions).render();

        var pollingPieOptions = {
            series: [{{ $totalMale }}, {{ $totalFemale }}, {{ $totalUnknown }}],
            chart: { height: 300, type: 'pie' },
            labels: ['Male', 'Female', 'Unknown'],
            colors: ['#007bff', '#ff69b4', '#6c757d'],
            legend: { position: 'bottom' }
        };
        new ApexCharts(document.querySelector("#pollingPieChart"), pollingPieOptions).render();

        var marketPieOptions = {
            series: [{{ $totalMale }}, {{ $totalFemale }}, {{ $totalUnknown }}],
            chart: { height: 300, type: 'pie' },
            labels: ['Male', 'Female', 'Unknown'],
            colors: ['#007bff', '#ff69b4', '#6c757d'],
            legend: { position: 'bottom' }
        };
        new ApexCharts(document.querySelector("#marketPieChart"), marketPieOptions).render();

        // Bar Charts for Role Counts
        var subcountyBarOptions = {
            series: [
                {
                    name: 'Total',
                    data: [@foreach($roleGroups as $roleId => $roleName) {{ $summaryStats[$roleId]['total'] }}, @endforeach]
                }
            ],
            chart: { height: 350, type: 'bar' },
            plotOptions: { bar: { horizontal: false } },
            xaxis: { categories: [@foreach($roleGroups as $roleId => $roleName) '{{ $roleName }}', @endforeach] },
            fill: { opacity: 1 }
        };
        new ApexCharts(document.querySelector("#subcountyBarChart"), subcountyBarOptions).render();

        var wardBarOptions = {
            series: [
                {
                    name: 'Total',
                    data: [@foreach($roleGroups as $roleId => $roleName) {{ $summaryStats[$roleId]['total'] }}, @endforeach]
                }
            ],
            chart: { height: 350, type: 'bar' },
            plotOptions: { bar: { horizontal: false } },
            xaxis: { categories: [@foreach($roleGroups as $roleId => $roleName) '{{ $roleName }}', @endforeach] },
            fill: { opacity: 1 }
        };
        new ApexCharts(document.querySelector("#wardBarChart"), wardBarOptions).render();

        var pollingBarOptions = {
            series: [
                {
                    name: 'Total',
                    data: [@foreach($roleGroups as $roleId => $roleName) {{ $summaryStats[$roleId]['total'] }}, @endforeach]
                }
            ],
            chart: { height: 350, type: 'bar' },
            plotOptions: { bar: { horizontal: false } },
            xaxis: { categories: [@foreach($roleGroups as $roleId => $roleName) '{{ $roleName }}', @endforeach] },
            fill: { opacity: 1 }
        };
        new ApexCharts(document.querySelector("#pollingBarChart"), pollingBarOptions).render();

        var marketBarOptions = {
            series: [
                {
                    name: 'Total',
                    data: [@foreach($roleGroups as $roleId => $roleName) {{ $summaryStats[$roleId]['total'] }}, @endforeach]
                }
            ],
            chart: { height: 350, type: 'bar' },
            plotOptions: { bar: { horizontal: false } },
            xaxis: { categories: [@foreach($roleGroups as $roleId => $roleName) '{{ $roleName }}', @endforeach] },
            fill: { opacity: 1 }
        };
        new ApexCharts(document.querySelector("#marketBarChart"), marketBarOptions).render();
    </script>
@endsection