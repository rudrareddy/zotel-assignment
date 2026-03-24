@extends('layouts.admin_layout')

@section('content')
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Room Types</a></li>
                    <li class="breadcrumb-item active">Create New Room Type</li>
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
                    <form action="{{url('admin/room-types')}}" method="POST" enctype="multipart/form-data">
                      @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="formrow-email-input">Name</label>
                                    <input type="text" class="form-control"  name="name" id="formrow-password-input"  value="{{old('name')}}">
                                     @if ($errors->has('name'))
                                            <span class="help-block">
                                                <strong style="color: red;">{{ $errors->first('name') }}</strong>
                                            </span>
                                        @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="formrow-password-input">Max Adults</label>
                                    <input type="number" class="form-control"  name="max_adults" id="formrow-password-input"  value="{{old('max_adults')}}">
                                     @if ($errors->has('max_adults'))
                                            <span class="help-block">
                                                <strong style="color: red;">{{ $errors->first('max_adults') }}</strong>
                                            </span>
                                        @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="formrow-password-input">Total Rooms</label>
                                    <input type="number" class="form-control"  name="total_rooms" id="formrow-password-input"  value="{{old('total_rooms')}}">
                                     @if ($errors->has('total_rooms'))
                                            <span class="help-block">
                                                <strong style="color: red;">{{ $errors->first('total_rooms') }}</strong>
                                            </span>
                                        @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label" for="formrow-password-input">Description</label>
                                    <textarea name="description" class="form-control" id=""></textarea>
                                     @if ($errors->has('description'))
                                            <span class="help-block">
                                                <strong style="color: red;">{{ $errors->first('description') }}</strong>
                                            </span>
                                        @endif
                                </div>
                            </div>
                        </div>
                        <div class="mt-4">
                            <a href="{{url('admin/room-types')}}" type="reset" class="btn btn-outline-danger w-md">Cancel</a>
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
