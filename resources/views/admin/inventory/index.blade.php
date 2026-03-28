@extends('layouts.admin_layout')

@section('content')
<div class="container-fluid">

    <!-- Page Title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item">
                        <i data-feather="package"></i> Room Inventory
                    </li>
                    <li class="breadcrumb-item active">{{ $room_type->name }}</li>
                </ol>

                <div class="page-title-right">
                    <a class="btn btn-primary waves-effect waves-light" 
                       href="{{ url('admin/inventory/'.$room_type->slug.'/create') }}">
                        <i class="fa fa-plus"></i> Add Inventory
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">

                    <form method="GET" class="d-flex gap-2">
                        <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-control">
                        <input type="date" name="date_to" value="{{ request('date_to') }}" class="form-control">

                        <button class="btn btn-primary">
                            <i class="fas fa-search me-1"></i> Filter
                        </button>
                    </form>

                    @if(request('date_from'))
                        <a href="{{ url('admin/inventory/'.$room_type->slug) }}" class="btn btn-danger">
                            Clear
                        </a>
                    @endif

                </div>

                <!-- Table -->
                <div class="card-body">
                    <table class="table table-bordered table-striped w-100">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Available Rooms</th>
                                <th>Base Price</th>
                                <th>Extra Adult Price</th>
                                <th>Base Occupancy</th>
                                <th>Status</th>
                                <th width="80"></th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($inventory_dates as $date)
                                <tr>
                                    <td>{{ $inventory_dates->firstItem() + $loop->index }}</td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($date->date)->format('d M Y') }}
                                    </td>

                                    <td>{{ $date->available_rooms }}</td>

                                    <td>₹ {{ number_format($date->base_price, 2) }}</td>

                                    <td>₹ {{ number_format($date->extra_adult_price, 2) }}</td>

                                    <td>{{ $date->base_occupancy }}</td>

                                    <td>
                                        <span class="badge {{ $date->is_available ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $date->is_available ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>

                                    <td>
                                        <div class="dropdown">
                                            <a class="btn btn-sm btn-outline-primary dropdown-toggle"
                                               href="#" data-bs-toggle="dropdown">
                                                <i class="fa fa-ellipsis-h"></i>
                                            </a>

                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a class="dropdown-item"
                                                       href="{{ url('admin/inventory/'.$date->id.'/edit') }}">
                                                        <i class="fa fa-edit"></i> Edit
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted">
                                        No inventory found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    <div class="mt-3">
                        {{ $inventory_dates->withQueryString()->links() }}
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>

<!-- Feather Icons -->
<script>
    feather.replace();
</script>
@endsection