@extends('layouts.header')
@section('css')
<link href="{{ asset('login_css/css/plugins/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
@endsection
@section('content')

<div class="wrapper wrapper-content">
    @include('error')
    <div class="row">
        <div class="col-lg-3">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Permits</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{count($permits->where('type','Permit'))}}</h1>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Licenses</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{count($permits->where('type','License'))}}</h1>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>For Renewal</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{count($permits->where('expiration_date','<',date('Y-m-d', strtotime("+3 months", strtotime(date('Y-m-d'))))))}}</h1>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Archived</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{count($archives)}}</h1>
                </div>
            </div>
        </div>
    </div>
    <div class='row'>
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Permits & Licenses <button class="btn btn-success "  data-target="#new_permit" data-toggle="modal" type="button"><i class="fa fa-plus"></i>&nbsp;New </button></h5>
                  
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
                                    <th>Type</th>
                                    <th>Expiration Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($permits as $permit)
                                <tr>
                                    <td>{{$permit->title}}</td>
                                    <td>{{$permit->description}}</td>
                                    <td>{{$permit->company->name}}</td>
                                    <td>{{$permit->department->name}}</td>
                                    <td>{{$permit->department->permit_account->name}}</td>
                                    <td>{{date('M d, Y',strtotime($permit->created_at))}}</td>
                                    <td><a href='{{url($permit->file)}}' target='_blank'><i class='fa fa-file'></i></a></td>
                                    <td>{{$permit->type}}</td>
                                    <td>{{date('M d Y',strtotime($permit->expiration_date))}}</td>
                                    <td>@if($permit->expiration_date < date('Y-m-d', strtotime("+3 months", strtotime(date('Y-m-d'))))) <small class="label label-danger">For Renewal</small> @else <small class="label label-primary">Active</small> @endif</td>
                                    <td>
                                        <button class="btn btn-sm btn-primary "  title="Upload " data-target="#upload{{$permit->id}}" data-toggle="modal"><i class="fa fa-upload"></i></button>
                                        <button class="btn btn-sm btn-warning "  title="Transfer Department" data-target="#change{{$permit->id}}" data-toggle="modal"><i class="fa fa-users"></i></button>
                                        {{-- <button class="btn btn-sm btn-warning "  title="View History "><i class="fa fa-eye"></i></button> --}}
                                    </td>
                                </tr>
                                @include('upload_permit')
                                @include('transfer_department')
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>
@include('new_permit')
@endsection
@section('js')
<script src="{{ asset('login_css/js/plugins/dataTables/datatables.min.js')}}"></script>
<script src="{{ asset('login_css/js/plugins/chosen/chosen.jquery.js') }}"></script>
<script>
    $(document).ready(function(){
        
        $('.cat').chosen({width: "100%"});
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
