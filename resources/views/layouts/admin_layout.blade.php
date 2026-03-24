<!doctype html>
<html lang="en">

    <head>

        <meta charset="utf-8" />
        <title>Zotel Admin</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Ecommerce website admin" name="description" />
        <meta content="" name="author" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{asset('admin/assets/images/favicon.ico')}}">
        <!-- datepicker css -->
        <link rel="stylesheet" href="{{asset('admin/assets/libs/flatpickr/flatpickr.min.css')}}">
        <!-- DataTables -->
        <link href="https://cdn.datatables.net/v/bs5/jszip-3.10.1/dt-2.2.1/b-3.2.1/b-colvis-3.2.1/b-html5-3.2.1/b-print-3.2.1/fh-4.0.1/r-3.0.3/datatables.min.css" rel="stylesheet">
        <!-- preloader css -->
        <link rel="stylesheet" href="{{asset('admin/assets/css/preloader.min.css')}}" type="text/css" />
        <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
        <!-- choices css -->
        <link href="{{asset('admin/assets/libs/choices.js/public/assets/styles/choices.min.css')}}" rel="stylesheet" type="text/css" />

        <!-- Bootstrap Css -->
        <link href="{{asset('admin/assets/css/bootstrap.min.css')}}" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="{{asset('admin/assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="{{asset('admin/assets/css/app.min.css')}}" id="app-style" rel="stylesheet" type="text/css" />
        <!-- plugin css -->
        <link href="{{asset('admin/assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.css')}}" rel="stylesheet" type="text/css" />

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <style>
         .ck-editor__editable {
    white-space: pre-wrap !important; /* This enables word wrap */
    word-break: break-word; /* Ensures long words break properly */
    
}

.choices__list--dropdown {
    max-height: 200px !important;
    overflow-y: scroll !important;
    display: block !important;
}

</style>
    </head>

    <body>
      <!-- Begin page -->
      <div id="layout-wrapper">

        @include('admin_includes.top_navigation')
        @include('admin_includes.side_navigation')

        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">
          <div class="page-content forcheckboxArea">
             @yield('content')
          </div>
          @include('admin_includes.footer_navigation')
        </div>

      </div>
      <!-- JAVASCRIPT -->
      <script src="{{asset('admin/assets/libs/jquery/jquery.min.js')}}"></script>
      <script src="{{asset('admin/assets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
      <script src="{{asset('admin/assets/libs/metismenu/metisMenu.min.js')}}"></script>
      <script src="{{asset('admin/assets/libs/simplebar/simplebar.min.js')}}"></script>
      <script src="{{asset('admin/assets/libs/node-waves/waves.min.js')}}"></script>
      <script src="{{asset('admin/assets/libs/feather-icons/feather.min.js')}}"></script>
      <!-- pace js -->
      <script src="{{asset('admin/assets/libs/pace-js/pace.min.js')}}"></script>
      <!-- choices js -->
      <script src="{{asset('admin/assets/libs/choices.js/public/assets/scripts/choices.min.js')}}"></script>
      <!-- Plugins js-->
      <script src="{{asset('admin/assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.min.js')}}"></script>
      <script src="{{asset('admin/assets/libs/admin-resources/jquery.vectormap/maps/jquery-jvectormap-world-mill-en.js')}}"></script>
      <!-- dashboard init -->
      

      <!-- apexcharts -->
      <script src="{{asset('admin/assets/libs/apexcharts/apexcharts.min.js')}}"></script>
      <!-- datepicker js -->
      <script src="{{asset('admin/assets/libs/flatpickr/flatpickr.min.js')}}"></script>
      <script src="{{asset('admin/assets/js/app.js')}}"></script>


      <!-- Required datatable js -->
      <script src="https://cdn.datatables.net/v/bs5/jszip-3.10.1/dt-2.2.1/b-3.2.1/b-colvis-3.2.1/b-html5-3.2.1/b-print-3.2.1/fh-4.0.1/r-3.0.3/datatables.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
      <!-- maps api -->
      <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
      <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
      

{{-- Blade template section --}}
@if(Session::has('success'))
    <script>
        Swal.fire({
            title: 'Success!',
            text: "{{ Session::get('success') }}",
            icon: 'success',
            confirmButtonText: 'OK',
            confirmButtonColor: "#012169",
        });
    </script>
@elseif(Session::has('error'))
    <script>
        Swal.fire({
            title: 'Error!',
            text: "{{ Session::get('error') }}",
            icon: 'error',
            confirmButtonText: 'OK',
            confirmButtonColor: "#d33",
        });
    </script>
@endif
      <script>
        $(document).ready(function(){
            $("#language-table").DataTable({
                "lengthChange": false
            });
            $("#pickup-warehouse").DataTable({
              "lengthChange": false,
            });
            $("#api-keys-table").DataTable({
              "lengthChange": false,
            });
            $("#route-master-table").DataTable({
              "lengthChange": false,
            });
            $("#vendor-table").DataTable({
              "lengthChange": false,
            });
            $("#vehicle-table1").DataTable({
              "lengthChange": false,
            });
            $("#vehicle-table2").DataTable({
              "lengthChange": false,
            });
        });
          
  $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});


$('#main_content').summernote({
    tabsize: 2,
    height: 585,
    toolbar: [
              ['style', ['style']],
              ['fontsize', ['fontsize']],
              ['fontname', ['fontname']],
              ['font', ['bold', 'italic', 'underline', 'clear']],
              ['fontname', ['fontname']],
              ['color', ['color']],
              ['para', ['ul', 'ol', 'paragraph']],
              ['height', ['height']],
              ['insert', ['picture', 'hr']],
              ['table', ['table']],
              ['view', ['fullscreen', 'codeview', 'help']],
            ],
    fontSizes: ['8', '9', '10', '11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','36','48','64','82','150'],
    fontNames:['IBM Plex Sans','sans-serif','Poppins'],
    fontNamesIgnoreCheck:['IBM Plex Sans','sans-serif','Poppins'],
});
// Initialize Choices.js for each select box
document.addEventListener('DOMContentLoaded', function() {

 const applicable_room_type_ids = document.getElementById('applicable_room_type_ids');
 if(applicable_room_type_ids){
   new Choices('#applicable_room_type_ids', {
       removeItemButton: true, // Enable close button
       searchEnabled: true,   // Disable search
       placeholder: true,      // Enable placeholder
       allowHTML: true,
       shouldSort: false,
       position: 'auto',
   });
 }

 const applicable_rate_plan_ids = document.getElementById('applicable_rate_plan_ids');
 if(applicable_rate_plan_ids){
   new Choices('#applicable_rate_plan_ids', {
       removeItemButton: true, // Enable close button
       searchEnabled: true,   // Disable search
       placeholder: true,      // Enable placeholder
       allowHTML: true,
       shouldSort: false,
       position: 'auto',
   });
 }


});
</script>
@stack('scripts')
</body>

</html>
