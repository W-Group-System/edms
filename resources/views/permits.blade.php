@extends('layouts.header')
@section('css')
<link href="{{ asset('login_css/css/plugins/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
@endsection
@section('content')

<div class="wrapper wrapper-content">
    <div class="row">
        <div class="col-lg-3">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Permits</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">0</h1>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Licenses</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">0</h1>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>For Expiration</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">0</h1>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Archived</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">0</h1>
                </div>
            </div>
        </div>
    </div>
    <div class='row'>
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Permits & Licenses <button class="btn btn-success "  data-target="#addProperty" data-toggle="modal" type="button"><i class="fa fa-plus"></i>&nbsp;New </button></h5>
                  
                </div>
                <div class="ibox-content">

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover tables" >
                        <thead>
                        <tr>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Company</th>
                            <th>Department</th>
                            <th>Accountable Person</th>
                            <th>Date Uploaded</th>
                            <th>File</th>
                            <th>Expiration Date</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>
{{-- @include('properties.create') --}}
@endsection
@section('js')
<script src="{{ asset('login_css/js/plugins/dataTables/datatables.min.js')}}"></script>
<script src="{{ asset('login_css/js/plugins/chosen/chosen.jquery.js') }}"></script>
<script>
    $(document).ready(function(){
        

        $('.locations').chosen({width: "100%"});
        $('.tables').DataTable({
            pageLength: 25,
            responsive: true,
            dom: '<"html5buttons"B>lTfgitp',
            buttons: [
                { extend: 'copy'},
                {extend: 'csv'},
                {extend: 'excel', title: 'ExampleFile'},
                {extend: 'pdf', title: 'ExampleFile'},

                {extend: 'print',
                 customize: function (win){
                        $(win.document.body).addClass('white-bg');
                        $(win.document.body).css('font-size', '10px');

                        $(win.document.body).find('table')
                                .addClass('compact')
                                .css('font-size', 'inherit');
                }
                }
            ]

        });

    });

</script>
@endsection
