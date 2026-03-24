@extends('layouts.admin_layout')

@section('content')
<div class="container-fluid">

    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Discount Rules</a></li>
                    <li class="breadcrumb-item "></li>

                </ol>

                <div class="page-title-right">
                    <a class="btn btn-danger waves-effect waves-light" href="{{url('admin/discounts/create')}}"><i class="fa fa-plus" aria-hidden="true"></i> Add New</a>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table id="language-table" class="table dt-responsive nowrap w-100 single-border">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Min Nights</th>
                                <th>Days Before Checkin</th>
                                <th>Dis Percentage</th>
                                <th>Status</th>
                                <th data-priority="1" class="no-sort"></th>
                            </tr>
                        </thead>

                        <tbody>
                          @foreach($discounts as $disount)
                            <tr class="default @if($loop->index % 2 ===0)even @else odd @endif">
                                <td>{{$loop->index+1}}</td>
                                <td>{{$disount->name}}</td>
                                <td>{{$disount->type}}</td>
                                <td>{{$disount->min_nights ? :'-'}}</td>
                                <td>{{$disount->days_before_checkin ? :'-'}}</td>
                                <td>{{$disount->discount_percentage}}</td>
                                <td>
                                    <span class="badge {{ $disount->is_active ? 'bg-success' : 'bg-danger' }}">
                                        {{ $disount->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                               
                                <td>
                                    <div class="actionParent">
                                        <!--<a class="tableAction btn-rounded btn-md btn btn-outline-primary waves-effect waves-light" href="#">View</a>-->
                                        <a class="tableAction btn-rounded btn-md btn btn-outline-primary waves-effect waves-light" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
                                        </a>
                                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">

                                            <li><a class="dropdown-item" href="{{url('admin/discounts/'.$disount->id.'/edit')}}"><i class="fa fa-edit"></i> Edit</a></li>
                                            
                                            
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- end cardaa -->
        </div> <!-- end col -->
    </div> <!-- end row -->
</div> <!-- container-fluid -->
<script>
document.querySelectorAll('.delete-btn-banner').forEach(button => {
    button.addEventListener('click', function(e) {
        const url = this.dataset.url;
        Swal.fire({
            title: 'Are you sure you want to delete this rate plan?',
            text: "",
            icon: 'error',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel',
            confirmButtonColor: "#d33",
            cancelButtonColor: "#012169",
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit the form programmatically
                const form = this.closest('.delete-form-banner');
                form.submit();
            }
        });
    });
});
</script>
@endsection
