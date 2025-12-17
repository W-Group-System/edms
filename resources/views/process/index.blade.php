@extends('layouts.header')
@section('css')
<link href="{{ asset('login_css/css/plugins/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
<link href="{{ asset('login_css/css/plugins/sweetalert/sweetalert.css') }}" rel="stylesheet">
@endsection
@section('content')

<div class="wrapper wrapper-content">
    @include('error')
    <div class="row">
        <div class="col-lg-3">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Processes</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{count($processes)}}</h1>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Active</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{count($processes->where('status',null))}}</h1>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Deactivated</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{count($processes->where('status','!=',null))}}</h1>
                </div>
            </div>
        </div>
        
    </div>
    <div class='row'>
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Processes <button class="btn btn-success "  data-target="#new_process" data-toggle="modal" type="button"><i class="fa fa-plus"></i>&nbsp;New </button></h5>
                  
                </div>
                <div class="ibox-content">

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover tables" >
                        <thead>
                            <tr>
                                
                                <th>Process Name</th>
                                <th>Company</th>
                                <th>Department</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($processes as $process)
                            <tr>
                                
                                <td>{{$process->process_name}}</td>
                                <td>{{$process->company->name}}</td>
                                <td>{{$process->department->name}}</td>
                                <td>@if($process->status) <small class="label label-danger">Inactive</small>  @else <small class="label label-primary">Active</small> @endif</td>
                               
                                <td data-id='{{$process->id}}'>
                                    @if($process->status)
                                    <button class="btn btn-sm btn-primary activate-process-setup" id='{{$process->id}}' title="Activate"><i class="fa fa-check"></i></button>
                                    @else
                                    <button class="btn btn-sm btn-warning"  title='Edit' data-target="#editprocess{{$process->id}}" data-toggle="modal"><i class="fa fa-pencil"></i></button>
                                    <button class="btn btn-sm btn-danger deactivate-process-setup" id='{{$process->id}}' title='Deactivate' ><i class="fa fa-trash"></i></button>
                                    @endif
                                </td>
                            </tr>
                          
                            <div class="modal" id="editprocess{{$process->id}}" tabindex="-1" role="dialog"  >
                                <div class="modal-dialog modal-lg " role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <div class='col-md-10'>
                                                <h5 class="modal-title" id="exampleModalLabel">New Process</h5>
                                            </div>
                                            <div class='col-md-2'>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close" >
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                        </div>
                                        <form method='post' action='edit-process-setup{{$process->id}}' onsubmit='show();' class="form-horizontal"  enctype="multipart/form-data" >
                                            <div class="modal-body">
                                                <div class='row'>
                                                    {{ csrf_field() }}
                                                    <div class='col-md-12'>
                                                        Process Name :
                                                        <input type="text" class="form-control-sm form-control "  value="{{ $process->process_name }}"  name="process_name" required/>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                <button type='submit'  class="btn btn-primary" >Submit</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>
@include('process.new_process')
@endsection
@section('js')
<script src="{{ asset('login_css/js/plugins/dataTables/datatables.min.js')}}"></script>
<script src="{{ asset('login_css/js/plugins/chosen/chosen.jquery.js') }}"></script>
<script src="{{ asset('login_css/js/plugins/sweetalert/sweetalert.min.js') }}"></script>
<script>
    $(document).ready(function(){
        $('.deactivate-process-setup').click(function () {
        
        var id = this.id;
            swal({
                title: "Are you sure?",
                text: "This Process Setup will be deactivated!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, deactivated it!",
                closeOnConfirm: false
            }, function (){
                $.ajax({
                    dataType: 'json',
                    type:'POST',
                    url:  '{{url("deactivate-process-setup")}}',
                    data:{id:id},
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                }).done(function(data){
                    console.log(data);
                    swal("Deactivated!", "Process Setup is now deactivated.", "success");
                    location.reload();
                }).fail(function(data)
                {
                    
                    swal("Deactivated!", "Process Setup is now deactivated.", "success");
                location.reload();
                });
            });
        });
        $('.activate-process-setup').click(function () {
        
        var id = this.id;
            swal({
                title: "Are you sure?",
                text: "This Process Setup will be activated!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, Activated it!",
                closeOnConfirm: false
            }, function (){
                $.ajax({
                    dataType: 'json',
                    type:'POST',
                    url:  '{{url("activate-process-setup")}}',
                    data:{id:id},
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                }).done(function(data){
                    console.log(data);
                    swal("Activated!", "Process Setup is now activated.", "success");
                    location.reload();
                }).fail(function(data)
                {
                    
                    swal("Activated!", "Process Setup is now activated.", "success");
                location.reload();
                });
            });
        });
        
        $('.cat').chosen({width: "100%"});
        $('.locations').chosen({width: "100%"});
        $('.tables').DataTable({
            pageLength: 25,
            responsive: true,
            stateSave: true,
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
