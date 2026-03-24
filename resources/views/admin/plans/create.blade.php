@extends('layouts.admin_layout')

@section('content')
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Rate Plans</a></li>
                        <li class="breadcrumb-item active">Create New Rate Plan</li>
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
                        <form action="{{ url('admin/rate-plans') }}" method="POST"
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
                                        <label class="form-label" for="formrow-password-input">Meal Type</label>
                                        <select name="meal_type" class="form-control">
                                            <option value="EP">EP
                                            </option>
                                            <option value="CP">CP
                                            </option>
                                            <option value="MAP">MAP
                                            </option>
                                        </select>
                                        @if ($errors->has('meal_type'))
                                            <span class="help-block">
                                                <strong style="color: red;">{{ $errors->first('meal_type') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label" for="formrow-password-input">Description</label>
                                        <textarea name="description" class="form-control" id="">{{ old('decription') }}</textarea>
                                        @if ($errors->has('description'))
                                            <span class="help-block">
                                                <strong style="color: red;">{{ $errors->first('description') }}</strong>
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
