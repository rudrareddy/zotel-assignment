@extends('layouts.admin_layout')

@section('content')
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Room Inventory</a></li>
                        <li class="breadcrumb-item active">Create New Inventory for {{ $inventory->roomType->name ?? 'N/A' }}
                        </li>
                    </ol>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ url('admin/inventory/store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="formrow-email-input">Last Inventory Date</label>
                                        <input type="text" class="form-control" name="last_inventory_date"
                                            id="formrow-password-input"
                                            value="{{ date('d M, Y', strtotime($inventory->date)) }}">
                                        @if ($errors->has('last_inventory_date'))
                                            <span class="help-block">
                                                <strong
                                                    style="color: red;">{{ $errors->first('last_inventory_date') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="formrow-password-input">Room Type </label>
                                        <select name="room_type_id" class="form-control">
                                            <option value="{{$inventory->roomType->id}}" selected>{{$inventory->roomType->name}}</option>
                                            
                                        </select>
                                        @if ($errors->has('inventory_date_count'))
                                            <span class="help-block">
                                                <strong
                                                    style="color: red;">{{ $errors->first('inventory_date_count') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="formrow-password-input">Create Inventory For </label>
                                        <select name="inventory_date_count" class="form-control">
                                            <option value="30">Next 30 Days</option>
                                            <option value="60">Next 60 Days</option>
                                        </select>
                                        @if ($errors->has('inventory_date_count'))
                                            <span class="help-block">
                                                <strong
                                                    style="color: red;">{{ $errors->first('inventory_date_count') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="formrow-password-input">Available Rooms</label>
                                        <input type="number" class="form-control" name="available_rooms"
                                            id="formrow-password-input" value="{{ $inventory->roomType->total_rooms }}">
                                        @if ($errors->has('available_rooms'))
                                            <span class="help-block">
                                                <strong style="color: red;">{{ $errors->first('available_rooms') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="formrow-password-input">Base Price</label>
                                        <input type="number" class="form-control" name="base_price"
                                            id="formrow-password-input" value="{{ old('base_price') }}">
                                        @if ($errors->has('base_price'))
                                            <span class="help-block">
                                                <strong style="color: red;">{{ $errors->first('base_price') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="formrow-password-input">Extra Adult Price</label>
                                        <input type="number" class="form-control" name="extra_adult_price"
                                            id="formrow-password-input" value="{{ old('extra_adult_price') }}">
                                        @if ($errors->has('extra_adult_price'))
                                            <span class="help-block">
                                                <strong
                                                    style="color: red;">{{ $errors->first('extra_adult_price') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="formrow-password-input">Base Occupancy</label>
                                        <input type="number" class="form-control" name="base_occupancy"
                                            id="formrow-password-input" value="{{ old('base_occupancy') }}">
                                        @if ($errors->has('base_occupancy'))
                                            <span class="help-block">
                                                <strong style="color: red;">{{ $errors->first('base_occupancy') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">

                                        <label class="form-label">Is Available <span class="text-danger"> *</span></label>
                                        <div class="form-check3">
                                            <input class="form-check-input" type="radio" name="is_available"
                                                value="1" checked id="formRadios1">
                                            <label class="form-check-label me-1" for="formRadios1">
                                                True
                                            </label>

                                            <input class="form-check-input" type="radio" name="is_available"
                                                id="formRadios2" value="0">
                                            <label class="form-check-label" for="formRadios2">
                                                False
                                            </label>
                                        </div>
                                        @if ($errors->has('is_available'))
                                            <span class="help-block">
                                                <strong style="color: red;">{{ $errors->first('is_available') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                    </div>
                    <div class="mt-4">
                        <a href="{{ url('admin/inventory/'.$inventory->roomType->slug) }}" type="reset"
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
@endsection
