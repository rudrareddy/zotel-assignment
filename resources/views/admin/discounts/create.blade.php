@extends('layouts.admin_layout')

@section('content')
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Discount Rules</a></li>
                        <li class="breadcrumb-item active">Create Discount Rule</li>
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
                        <form action="{{ url('admin/discounts') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="formrow-email-input">Name</label>
                                        <input type="text" class="form-control" name="name"
                                            id="formrow-password-input" value="{{ old('name') }}">
                                        @if ($errors->has('name'))
                                            <span class="help-block">
                                                <strong style="color: red;">{{ $errors->first('name') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="formrow-password-input">Type</label>
                                        <select name="type" class="form-control">
                                            <option value="early_bird">Early Bird
                                            </option>
                                            <option value="long_stay">Long Stay
                                            </option>
                                            <option value="last_minute">Last Minute
                                            </option>
                                        </select>
                                        @if ($errors->has('type'))
                                            <span class="help-block">
                                                <strong style="color: red;">{{ $errors->first('type') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                 <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="formrow-password-input">Min Nights</label>
                                    <input type="number" class="form-control"  name="min_nights" id="formrow-password-input"  value="{{old('min_nights')}}">
                                     @if ($errors->has('min_nights'))
                                            <span class="help-block">
                                                <strong style="color: red;">{{ $errors->first('min_nights') }}</strong>
                                            </span>
                                        @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                        <label class="form-label" for="formrow-password-input">Days Before Check In</label>
                                        <input type="number" class="form-control"  name="days_before_checkin" id="formrow-password-input"  value="{{old('days_before_checkin')}}">
                                        @if ($errors->has('days_before_checkin'))
                                                <span class="help-block">
                                                    <strong style="color: red;">{{ $errors->first('days_before_checkin') }}</strong>
                                                </span>
                                            @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                <div class="mb-3">
                                        <label class="form-label" for="formrow-password-input">Discount Percentage</label>
                                        <input type="number" class="form-control"  name="discount_percentage" id="formrow-password-input"  value="{{old('discount_percentage')}}">
                                        @if ($errors->has('discount_percentage'))
                                                <span class="help-block">
                                                    <strong style="color: red;">{{ $errors->first('discount_percentage') }}</strong>
                                                </span>
                                            @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="choices-multiple-default"
                                            class="form-label font-size-13 text-muted">Applicable Rate Plans</label>
                                        <select class="form-control" data-trigger name="applicable_rate_plan_ids[]" id="applicable_rate_plan_ids"
                                            placeholder="This is a placeholder" multiple>
                                            @foreach ($rate_plans as $rate)
                                                <option value="{{ $rate->id }}">{{ $rate->name }}</option>
                                            @endforeach

                                        </select>
                                        @if ($errors->has('applicable_rate_plan_ids'))
                                            <span class="help-block">
                                                <strong style="color: red;">{{ $errors->first('applicable_rate_plan_ids') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="choices-multiple-default"
                                            class="form-label font-size-13 text-muted">Applicable Rooms</label>
                                        <select class="form-control" data-trigger name="applicable_room_type_ids[]" id="applicable_room_type_ids"
                                            placeholder="This is a placeholder" multiple>
                                            @foreach ($room_types as $type)
                                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                                            @endforeach

                                        </select>
                                        @if ($errors->has('applicable_room_type_ids'))
                                            <span class="help-block">
                                                <strong style="color: red;">{{ $errors->first('applicable_room_type_ids') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">

                                        <label class="form-label">Status <span class="text-danger"> *</span></label>
                                        <div class="form-check3">
                                            <input class="form-check-input" type="radio" name="is_active" value="1"
                                                id="formRadios1">
                                            <label class="form-check-label me-1" for="formRadios1">
                                                Active
                                            </label>

                                            <input class="form-check-input" type="radio" name="is_active" id="formRadios2"
                                                value="0" >
                                            <label class="form-check-label" for="formRadios2">
                                                In-active
                                            </label>
                                        </div>
                                        @if ($errors->has('is_active'))
                                            <span class="help-block">
                                                <strong style="color: red;">{{ $errors->first('is_active') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <a href="{{ url('admin/rate-plans') }}" type="reset"
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
