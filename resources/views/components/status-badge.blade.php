@php
$map = [
    0 => ['Not Contacted', 'info'],
    1 => ['Picked','success'],
    2 => ['Declined / Unreachable','warning'],
    3 => ['Missed Call','danger'],
    7 => ['Call Back','secondary'],
    8 => ['Invalid Number','dark'],
];
$class = $map[$u->call_status][1] ?? 'primary';
$text = $map[$u->call_status][0] ?? 'Unknown';
@endphp

<span class="badge bg-{{ $class }}">{{ $text }}</span>
