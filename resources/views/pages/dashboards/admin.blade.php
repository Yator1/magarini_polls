@extends('layouts.master')
@section('title')
    Dashboard
@endsection
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
                            <div class="col-12">
                                <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                                    <div class="flex-grow-1">
                                        <h4 class="fs-16 mb-1">Welcome Back, {{ $user->first_name . ' ' . $user->last_name }}!</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                                    <div class="flex-grow-1">
                                        <br>
                                        <h4 class="fs-16 mb-1">Poll Dashboard for: {{ $date1->format('Y-m-d') . ' - ' . $date2->format('Y-m-d') }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin-dashboard') }}" method="POST">
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
                    </div>
                </div>

                <!-- Call Statistics Cards -->
                <div class="row">
                    <div class="col-xl-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title mb-0">Call Statistics</h4>
                            </div>
                            <div class="card-body">
                                <div class="row row-cols-xxl-6 row-cols-lg-3 row-cols-md-2 row-cols-1">
                                    <div class="col">
                                        <a href="{{ route('users.declined') }}" class="card card-animate bg-danger text-white" role="button">
                                            <div class="card-body">
                                                <h5 class="fs-15 fw-semibold" style="color: white;">Declined / Unreachable</h5>
                                                <p class="mb-0"><span class="fw-medium">{{ $callStats['declined_unreachable'] }}/{{$totalAllcontactedUsers}} ({{ $percentages['declined_unreachable'] }}%)</span></p>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col">
                                        <a href="{{ route('users.notreached') }}" class="card card-animate bg-warning text-white" role="button">
                                            <div class="card-body">
                                                <h5 class="fs-15 fw-semibold" style="color: white;">Missed Calls</h5>
                                                <p class="mb-0"><span class="fw-medium">{{ $callStats['missed'] }}/{{$totalAllcontactedUsers}} ({{ $percentages['missed'] }}%)</span></p>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col">
                                        <a href="{{ route('users.picked') }}" class="card card-animate bg-info text-white" role="button">
                                            <div class="card-body">
                                                <h5 class="fs-15 fw-semibold" style="color: white;">Calls Back</h5>
                                                <p class="mb-0"><span class="fw-medium">{{ $callStats['calls_back'] }}/{{$totalAllcontactedUsers}} ({{ $percentages['calls_back'] }}%)</span></p>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col">
                                        <a href="{{ route('users.picked') }}" class="card card-animate bg-secondary text-white" role="button">
                                            <div class="card-body">
                                                <h5 class="fs-15 fw-semibold" style="color: white;">Invalid/Wrong/No Phone</h5>
                                                <p class="mb-0"><span class="fw-medium">{{ $callStats['invalid_phone'] }}/{{$totalAllcontactedUsers}} ({{ $percentages['invalid_phone'] }}%)</span></p>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col">
                                        <a href="{{ route('users.picked') }}" class="card card-animate bg-success text-white" role="button">
                                            <div class="card-body">
                                                <h5 class="fs-15 fw-semibold" style="color: white;">Picked & Participated</h5>
                                                <p class="mb-0"><span class="fw-medium">{{ $callStats['picked_participated'] }}/{{$totalAllcontactedUsers}} ({{ $percentages['picked_participated'] }}%)</span></p>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col">
                                        <a href="{{ route('users.pending') }}" class="card card-animate bg-primary text-white" role="button">
                                            <div class="card-body">
                                                <h5 class="fs-15 fw-semibold" style="color: white;">Not Contacted</h5>
                                                <p class="mb-0"><span class="fw-medium">{{ $callStats['not_contacted'] }}/{{ $totalUsers }} ({{ $percentages['not_contacted'] }}%)</span></p>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Poll Results -->
                @if($poll)
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title mb-0">Poll: {{ $poll->name }}</h4>
                                </div>
                                <div class="card-body">
                                    <ul class="nav nav-tabs nav-tabs-custom nav-success mb-3" role="tablist">
                                        @foreach($pollData as $qid => $qdata)
                                            <li class="nav-item">
                                                <a class="nav-link {{ $loop->first ? 'active' : '' }}" data-bs-toggle="tab" href="#question{{ $qid }}" role="tab">
                                                    Question {{ $loop->iteration }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                    <div class="tab-content text-muted">
                                        @foreach($pollData as $qid => $qdata)
                                            <div class="tab-pane {{ $loop->first ? 'active' : '' }}" id="question{{ $qid }}" role="tabpanel">
                                                <h5>{{ $qdata['question'] }}</h5>

                                                <!-- Overall -->
                                                <div class="card mb-3">
                                                    <div class="card-header bg-soft-primary">
                                                        <h6 class="card-title mb-0">Overall Results</h6>
                                                    </div>
                                                    <div class="card-body">
                                                        @if($qdata['question_type'] === 'multiple')
                                                            @php
                                                                $totalAnswers = array_sum(array_column($qdata['answers'], 'count')) + $qdata['blanks'];
                                                            @endphp
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered table-hover" id="overallTable{{ $qid }}">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Answer</th>
                                                                            <th>Count</th>
                                                                            <th>Percentage</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach($qdata['answers'] as $ans)
                                                                            <tr>
                                                                                <td>{{ $ans['answer'] }}</td>
                                                                                <td>{{ $ans['count'] }}</td>
                                                                                <td>{{ $ans['percentage'] }}%</td>
                                                                            </tr>
                                                                        @endforeach
                                                                        <!-- <tr>
                                                                            <td>Blanks</td>
                                                                            <td>{{ $qdata['blanks'] }}</td>
                                                                            <td>{{ $qdata['total_participants'] ? round($qdata['blanks'] / $qdata['total_participants'] * 100, 2) : 0 }}%</td>
                                                                        </tr> -->
                                                                    </tbody>
                                                                    <tfoot>
                                                                        <tr>
                                                                            <td><strong>Total</strong></td>
                                                                            <td><strong>{{ $totalAnswers }}</strong></td>
                                                                            <td><strong>{{ $totalAnswers ? round($totalAnswers / $totalAnswers * 100, 2) : 0 }}%</strong></td>
                                                                        </tr>
                                                                    </tfoot>
                                                                </table>
                                                            </div>
                                                            <div id="pieChart{{ $qid }}" class="apex-charts"></div>
                                                        @else
                                                            <!-- <p>{{ $qdata['answer_type'] === 'text' ? 'Count' : 'Sum' }}: {{ $qdata['answers'] }}</p>
                                                            <p>Blanks: {{ $qdata['blanks'] }}</p>
                                                            <p>Total: {{ $qdata['answers'] + $qdata['blanks'] }}</p> -->
                                                        @endif
                                                    </div>
                                                </div>

                                                <!-- Per Subcounty (Tabs) -->
                                                <div class="card mb-3">
                                                    <div class="card-header bg-soft-info">
                                                        <h6 class="card-title mb-0">Per Subcounty</h6>
                                                    </div>
                                                    <div class="card-body">
                                                        <ul class="nav nav-tabs nav-tabs-custom nav-info" role="tablist">
                                                            @foreach($subcounties as $scid => $sc)
                                                                <li class="nav-item">
                                                                    <a class="nav-link {{ $loop->first ? 'active' : '' }}" data-bs-toggle="tab" href="#sub{{ $qid }}_{{ $scid }}" role="tab">
                                                                        {{ $sc->name ?? 'Unknown' }}
                                                                    </a>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                        <div class="tab-content">
                                                            @foreach($subcounties as $scid => $sc)
                                                                <div class="tab-pane {{ $loop->first ? 'active' : '' }}" id="sub{{ $qid }}_{{ $scid }}" role="tabpanel">
                                                                    @php
                                                                        $scTotal = $subcountyTotals[$scid] ?? 0;
                                                                        $scCounts = $perSubcountyData[$qid][$scid] ?? collect();
                                                                        $scBlank = $scCounts[null] ?? 0;
                                                                        $sortedAnswers = collect($qdata['answers'])->map(function ($ans) use ($scCounts, $scTotal) {
                                                                            $count = $scCounts[$ans['id']] ?? 0;
                                                                            $perc = $scTotal ? round($count / $scTotal * 100, 2) : 0;
                                                                            return ['id' => $ans['id'], 'answer' => $ans['answer'], 'count' => $count, 'percentage' => $perc];
                                                                        })->sortByDesc('count')->values();
                                                                        $totalAnswers = array_sum(array_column($sortedAnswers->toArray(), 'count')) + $scBlank;
                                                                    @endphp
                                                                    @if($qdata['question_type'] === 'multiple')
                                                                        <table class="table table-bordered table-hover" id="subcountyTable{{ $qid }}_{{ $scid }}">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>Answer</th>
                                                                                    <th>Count</th>
                                                                                    <th>Percentage</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                @foreach($sortedAnswers as $ans)
                                                                                    <tr>
                                                                                        <td>{{ $ans['answer'] }}</td>
                                                                                        <td>{{ $ans['count'] }}</td>
                                                                                        <td>{{ $ans['percentage'] }}%</td>
                                                                                    </tr>
                                                                                @endforeach
                                                                                <!-- <tr>
                                                                                    <td>Blanks</td>
                                                                                    <td>{{ $scBlank }}</td>
                                                                                    <td>{{ $scTotal ? round($scBlank / $scTotal * 100, 2) : 0 }}%</td>
                                                                                </tr> -->
                                                                            </tbody>
                                                                            <tfoot>
                                                                                <tr>
                                                                                    <td><strong>Total</strong></td>
                                                                                    <td><strong>{{ $totalAnswers }}</strong></td>
                                                                                    <td><strong>{{ $totalAnswers ? round($totalAnswers / $totalAnswers * 100, 2) : 0 }}%</strong></td>
                                                                                </tr>
                                                                            </tfoot>
                                                                        </table>
                                                                    @else
                                                                        @php
                                                                            $scData = $perSubcountyData[$qid][$scid] ?? [];
                                                                        @endphp
                                                                        <p>Total in Subcounty: {{ $scTotal }}</p>
                                                                        <p>{{ $qdata['answer_type'] === 'text' ? 'Count' : 'Sum' }}: {{ $scData['value'] ?? 0 }}</p>
                                                                        <!-- <p>Blanks: {{ $scData['blanks'] ?? 0 }}</p>
                                                                        <p>Total: {{ ($scData['value'] ?? 0) + ($scData['blanks'] ?? 0) }}</p> -->
                                                                    @endif
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Per Ward (Table) -->
                                                <div class="card mb-3">
                                                    <div class="card-header bg-soft-warning">
                                                        <h6 class="card-title mb-0">Per Ward</h6>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered table-hover" id="wardTable{{ $qid }}">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Ward</th>
                                                                        @if($qdata['question_type'] === 'multiple')
                                                                            @foreach($qdata['answers'] as $ans)
                                                                                <th>{{ $ans['answer'] }} (Count / %)</th>
                                                                            @endforeach
                                                                            <!-- <th>Blanks (Count / %)</th>
                                                                            <th>Total</th> -->
                                                                        @else
                                                                            <!-- <th>{{ $qdata['answer_type'] === 'text' ? 'Count' : 'Sum' }}</th>
                                                                            <th>Blanks</th>
                                                                            <th>Total</th> -->
                                                                        @endif
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @php
                                                                        $columnTotals = array_fill(0, count($qdata['answers']) + 1, 0);
                                                                    @endphp
                                                                    @foreach($wards as $ward)
                                                                        @php
                                                                            $wTotal = $wardTotals[$ward->id] ?? 0;
                                                                            $rowTotal = 0;
                                                                        @endphp
                                                                        <tr>
                                                                            <td>{{ $ward->name ?? 'Unknown' }}</td>
                                                                            @if($qdata['question_type'] === 'multiple')
                                                                                @php
                                                                                    $wCounts = $perWardData[$qid][$ward->id] ?? collect();
                                                                                    $wBlank = $wCounts[null] ?? 0;
                                                                                @endphp
                                                                                @foreach($qdata['answers'] as $index => $ans)
                                                                                    @php
                                                                                        $count = $wCounts[$ans['id']] ?? 0;
                                                                                        $perc = $wTotal ? round($count / $wTotal * 100, 2) : 0;
                                                                                        $columnTotals[$index] += $count;
                                                                                        $rowTotal += $count;
                                                                                    @endphp
                                                                                    <td>{{ $count }} ({{ $perc }}%)</td>
                                                                                @endforeach
                                                                                @php
                                                                                    $columnTotals[count($qdata['answers'])] += $wBlank;
                                                                                    $rowTotal += $wBlank;
                                                                                @endphp
                                                                                <td>{{ $wBlank }} ({{ $wTotal ? round($wBlank / $wTotal * 100, 2) : 0 }}%)</td>
                                                                                <td>{{ $rowTotal }}</td>
                                                                            @else
                                                                                @php
                                                                                    $wData = $perWardData[$qid][$ward->id] ?? [];
                                                                                    $value = $wData['value'] ?? 0;
                                                                                    $blanks = $wData['blanks'] ?? 0;
                                                                                    $rowTotal = $value + $blanks;
                                                                                @endphp
                                                                                <td>{{ $value }}</td>
                                                                                <!-- <td>{{ $blanks }}</td> -->
                                                                                <td>{{ $rowTotal }}</td>
                                                                            @endif
                                                                        </tr>
                                                                    @endforeach
                                                                    @if($qdata['question_type'] === 'multiple')
                                                                        <tr>
                                                                            <td><strong>Total</strong></td>
                                                                            @foreach($columnTotals as $index => $total)
                                                                                <td><strong>{{ $total }} ({{ $totalParticipants ? round($total / $totalParticipants * 100, 2) : 0 }}%)</strong></td>
                                                                            @endforeach
                                                                            <td><strong>{{ $totalParticipants }}</strong></td>
                                                                        </tr>
                                                                    @endif
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Per Polling Station (Table) -->
                                                <div class="card mb-3">
                                                    <div class="card-header bg-soft-danger">
                                                        <h6 class="card-title mb-0">Per Polling Station</h6>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered table-hover" id="pollingTable{{ $qid }}">
                                                                <thead>
                                                                    <tr>
                                                                        <th>Polling Station</th>
                                                                        @if($qdata['question_type'] === 'multiple')
                                                                            @foreach($qdata['answers'] as $ans)
                                                                                <th>{{ $ans['answer'] }} (Count / %)</th>
                                                                            @endforeach
                                                                            <!-- <th>Blanks (Count / %)</th>
                                                                            <th>Total</th> -->
                                                                        @else
                                                                            <!-- <th>{{ $qdata['answer_type'] === 'text' ? 'Count' : 'Sum' }}</th>
                                                                            <th>Blanks</th>
                                                                            <th>Total</th> -->
                                                                        @endif
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @php
                                                                        $columnTotals = array_fill(0, count($qdata['answers']) + 1, 0);
                                                                    @endphp
                                                                    @foreach($pollingStations as $ps)
                                                                        @php
                                                                            $pTotal = $pollingTotals[$ps->id] ?? 0;
                                                                            $rowTotal = 0;
                                                                        @endphp
                                                                        <tr>
                                                                            <td>{{ $ps->name ?? 'Unknown' }}</td>
                                                                            @if($qdata['question_type'] === 'multiple')
                                                                                @php
                                                                                    $pCounts = $perPollingData[$qid][$ps->id] ?? collect();
                                                                                    $pBlank = $pCounts[null] ?? 0;
                                                                                @endphp
                                                                                @foreach($qdata['answers'] as $index => $ans)
                                                                                    @php
                                                                                        $count = $pCounts[$ans['id']] ?? 0;
                                                                                        $perc = $pTotal ? round($count / $pTotal * 100, 2) : 0;
                                                                                        $columnTotals[$index] += $count;
                                                                                        $rowTotal += $count;
                                                                                    @endphp
                                                                                    <td>{{ $count }} ({{ $perc }}%)</td>
                                                                                @endforeach
                                                                                @php
                                                                                    $columnTotals[count($qdata['answers'])] += $pBlank;
                                                                                    $rowTotal += $pBlank;
                                                                                @endphp
                                                                                <td>{{ $pBlank }} ({{ $pTotal ? round($pBlank / $pTotal * 100, 2) : 0 }}%)</td>
                                                                                <td>{{ $rowTotal }}</td>
                                                                            @else
                                                                                @php
                                                                                    $pData = $perPollingData[$qid][$ps->id] ?? [];
                                                                                    $value = $pData['value'] ?? 0;
                                                                                    $blanks = $pData['blanks'] ?? 0;
                                                                                    $rowTotal = $value + $blanks;
                                                                                @endphp
                                                                                <td>{{ $value }}</td>
                                                                                <!-- <td>{{ $blanks }}</td> -->
                                                                                <td>{{ $rowTotal }}</td>
                                                                            @endif
                                                                        </tr>
                                                                    @endforeach
                                                                    </tbody>
                                                                    <tfoot>
                                                                    @if($qdata['question_type'] === 'multiple')
                                                                        <tr>
                                                                            <td><strong>Total</strong></td>
                                                                            @foreach($columnTotals as $index => $total)
                                                                                <td><strong>{{ $total }} ({{ $totalParticipants ? round($total / $totalParticipants * 100, 2) : 0 }}%)</strong></td>
                                                                            @endforeach
                                                                            <td><strong>{{ $totalParticipants }}</strong></td>
                                                                        </tr>
                                                                    @endif
                                                                </tfoot>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Graphs -->
                                                <div class="card">
                                                    <div class="card-header bg-soft-success">
                                                        <h6 class="card-title mb-0">Graphs</h6>
                                                    </div>
                                                    <div class="card-body">
                                                        @if($qdata['question_type'] === 'multiple')
                                                            <div id="barChart{{ $qid }}" class="apex-charts"></div>
                                                        @else
                                                            <div id="valueBarChart{{ $qid }}" class="apex-charts"></div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Agent Statistics (if admin) -->
                @if($isAdmin && !empty($agentStats))
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title mb-0">Per Agent Statistics</h4>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover" id="agentTable">
                                            <thead>
                                                <tr>
                                                    <th>Agent</th>
                                                    <th>Declined/Unreachable</th>
                                                    <th>Missed</th>
                                                    <th>Calls Back</th>
                                                    <th>Invalid Phone</th>
                                                    <th>Picked & Participated</th>
                                                    <th>Total</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $columnTotals = [
                                                        'declined_unreachable' => 0,
                                                        'missed' => 0,
                                                        'calls_back' => 0,
                                                        'invalid_phone' => 0,
                                                        'picked_participated' => 0,
                                                    ];
                                                @endphp
                                                @foreach($agentStats as $aid => $astats)
                                                    @php
                                                        $rowTotal = $astats['declined_unreachable'] + $astats['missed'] + $astats['calls_back'] + $astats['invalid_phone'] + $astats['picked_participated'];
                                                        $columnTotals['declined_unreachable'] += $astats['declined_unreachable'];
                                                        $columnTotals['missed'] += $astats['missed'];
                                                        $columnTotals['calls_back'] += $astats['calls_back'];
                                                        $columnTotals['invalid_phone'] += $astats['invalid_phone'];
                                                        $columnTotals['picked_participated'] += $astats['picked_participated'];
                                                    @endphp
                                                    <tr>
                                                        <td>{{ $astats['name'] }}</td>
                                                        <td>{{ $astats['declined_unreachable'] }} ({{ $rowTotal ? round($astats['declined_unreachable'] / $rowTotal * 100, 2) : 0 }}%)</td>
                                                        <td>{{ $astats['missed'] }} ({{ $rowTotal ? round($astats['missed'] / $rowTotal * 100, 2) : 0 }}%)</td>
                                                        <td>{{ $astats['calls_back'] }} ({{ $rowTotal ? round($astats['calls_back'] / $rowTotal * 100, 2) : 0 }}%)</td>
                                                        <td>{{ $astats['invalid_phone'] }} ({{ $rowTotal ? round($astats['invalid_phone'] / $rowTotal * 100, 2) : 0 }}%)</td>
                                                        <td>{{ $astats['picked_participated'] }} ({{ $rowTotal ? round($astats['picked_participated'] / $rowTotal * 100, 2) : 0 }}%)</td>
                                                        <td>{{ $rowTotal }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td><strong>Total</strong></td>
                                                    @foreach($columnTotals as $key => $total)
                                                        <td><strong>{{ $total }} ({{ $totalCalled ? round($total / $totalCalled * 100, 2) : 0 }}%)</strong></td>
                                                    @endforeach
                                                    <td><strong>{{ $totalCalled }}</strong></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ URL::asset('build/libs/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/jsvectormap/js/jsvectormap.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/jsvectormap/maps/world-merc.js') }}"></script>
    <script src="{{ URL::asset('build/libs/swiper/swiper-bundle.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/pages/dashboard-ecommerce.init.js') }}"></script>
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
        // Initialize DataTables with default sorting
        $('[id^="overallTable"]').DataTable({
            responsive: true,
            paging: true,
            scrollX: true,
            buttons: ['copy', 'excel', 'pdf', 'print'],
            order: [[1, 'desc']] // Sort by Count column
        });

        $('[id^="subcountyTable"]').DataTable({
            responsive: true,
            paging: true,
            scrollX: true,
            buttons: ['copy', 'excel', 'pdf', 'print'],
            order: [[1, 'desc']] // Sort by Count column
        });

        $('[id^="wardTable"]').DataTable({
            responsive: true,
            paging: true,
            scrollX: true,
            buttons: ['copy', 'excel', 'pdf', 'print'],
            order: [[0, 'asc']] // Sort by Ward name
        });

        $('[id^="pollingTable"]').DataTable({
            responsive: true,
            paging: true,
            scrollX: true,
            buttons: ['copy', 'excel', 'pdf', 'print'],
            order: [[0, 'asc']] // Sort by Polling Station name
        });

        $('#agentTable').DataTable({
            responsive: true,
            paging: true,
            scrollX: true,
            buttons: ['copy', 'excel', 'pdf', 'print'],
            order: [[5, 'desc']] // Sort by Picked & Participated
        });

        // Charts
        @foreach($pollData as $qid => $qdata)
            @if($qdata['question_type'] === 'multiple')
                var pieOptions{{ $qid }} = {
                    series: [@foreach($qdata['answers'] as $ans) {{ $ans['count'] }}, @endforeach {{ $qdata['blanks'] }}],
                    chart: { height: 300, type: 'pie' },
                    labels: [@foreach($qdata['answers'] as $ans) '{{ $ans['answer'] }}', @endforeach],
                    colors: ['#28a745', '#ffc107', '#dc3545', '#17a2b8', '#007bff'],
                    legend: { position: 'bottom' }
                };
                new ApexCharts(document.querySelector("#pieChart{{ $qid }}"), pieOptions{{ $qid }}).render();

                var barSeries{{ $qid }} = [@foreach($qdata['answers'] as $ans) { name: '{{ $ans['answer'] }}', data: [@foreach($subcounties as $scid => $sc) {{ $perSubcountyData[$qid][$scid][$ans['id']] ?? 0 }}, @endforeach ] }, @endforeach ];
                var barOptions{{ $qid }} = {
                    chart: { height: 350, type: 'bar', stacked: true },
                    plotOptions: { bar: { horizontal: false } },
                    series: barSeries{{ $qid }},
                    xaxis: { categories: [@foreach($subcounties as $scid => $sc) '{{ $sc->name ?? 'Unknown' }}', @endforeach ] },
                    fill: { opacity: 1 }
                };
                new ApexCharts(document.querySelector("#barChart{{ $qid }}"), barOptions{{ $qid }}).render();
            @else
                var valueBarSeries{{ $qid }} = [{ name: '{{ $qdata['answer_type'] === 'text' ? 'Count' : 'Sum' }}', data: [@foreach($subcounties as $scid => $sc) {{ $perSubcountyData[$qid][$scid]['value'] ?? 0 }}, @endforeach ] }];
                var valueBarOptions{{ $qid }} = {
                    chart: { height: 350, type: 'bar' },
                    plotOptions: { bar: { horizontal: false } },
                    series: valueBarSeries{{ $qid }},
                    xaxis: { categories: [@foreach($subcounties as $scid => $sc) '{{ $sc->name ?? 'Unknown' }}', @endforeach ] },
                    fill: { opacity: 1 }
                };
                new ApexCharts(document.querySelector("#valueBarChart{{ $qid }}"), valueBarOptions{{ $qid }}).render();
            @endif
        @endforeach
    </script>
@endsection