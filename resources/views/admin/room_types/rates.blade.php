@extends('layouts.admin_layout')

@section('content')
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Room Types</a></li>
                        <li class="breadcrumb-item active">Attach Rate</li>
                    </ol>

                    <!--<div class="page-title-right">
                        <a class="btn btn-danger waves-effect waves-light" href="#"><i class="fa fa-plus" aria-hidden="true"></i> Add New</a>
                    </div>-->

                </div>
            </div>
        </div>
        <!-- end page title -->



        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ url('admin/room-types/' . $roomType->id . '/rates') }}" method="POST">
                            @csrf

                            <div class="row">



                                <h5>Attach Rate Plans for {{ $roomType->name }}</h5>

                                @foreach ($ratePlans as $plan)
                                    @php
                                        $attached = $roomType->ratePlans->firstWhere('id', $plan->id);
                                    @endphp

                                    <div class="card mb-3 p-3 border">

                                        <div class="form-check mb-2">
                                            <input type="checkbox" name="rate_plans[{{ $plan->id }}][selected]"
                                                value="1" class="form-check-input rate-plan-checkbox"
                                                data-id="{{ $plan->id }}" {{ $attached ? 'checked' : '' }}>

                                            <label class="form-check-label">
                                                <strong>{{ $plan->name }} ({{ $plan->code }})</strong>
                                            </label>
                                        </div>

                                        <div class="row rate-plan-fields" id="plan-fields-{{ $plan->id }}"
                                            style="{{ $attached ? 'display:flex;' : 'display:none;' }}">

                                            <!--<div class="col-md-4">
                                                <label>Base Price Multiplier</label>
                                                <input type="number" step="0.01" class="form-control"
                                                    name="rate_plans[{{ $plan->id }}][base_price_multiplier]"
                                                    value="{{ $attached->pivot->base_price_multiplier ?? '' }}">
                                            </div>-->

                                            <div class="col-md-4">
                                                <label>Meal Price / Person</label>
                                                <input type="number" class="form-control"
                                                    name="rate_plans[{{ $plan->id }}][meal_price_per_person]"
                                                    value="{{ $attached->pivot->meal_price_per_person ?? '' }}">
                                            </div>

                                            <div class="col-md-4">
                                                <label>Available</label>
                                                <select class="form-control"
                                                    name="rate_plans[{{ $plan->id }}][is_available]">
                                                    <option value="1"
                                                        {{ ($attached->pivot->is_available ?? 1) == 1 ? 'selected' : '' }}>
                                                        Yes</option>
                                                    <option value="0"
                                                        {{ ($attached->pivot->is_available ?? 1) == 0 ? 'selected' : '' }}>
                                                        No</option>
                                                </select>
                                            </div>

                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="mt-4">
                                <a href="{{ url('admin/room-types') }}" type="reset"
                                    class="btn btn-outline-danger w-md">Cancel</a>
                                <button type="submit" class="btn btn-primary w-md">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- end cardaa -->
            </div> <!-- end col -->
        </div> <!-- end row -->
    </div> <!-- container-fluid -->
    <script>
        document.querySelectorAll('.rate-plan-checkbox').forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                let id = this.getAttribute('data-id');
                let section = document.getElementById('plan-fields-' + id);

                if (this.checked) {
                    section.style.display = 'flex';
                } else {
                    section.style.display = 'none';
                }
            });
        });
    </script>
@endsection
