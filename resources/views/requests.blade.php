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
                    <h5>Pending</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{count($requests->where('status','Pending'))}}</h1>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Cancelled</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{count($requests->where('status','Cancelled'))}}</h1>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Declined</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{count($requests->where('status','Declined'))}}</h1>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Approved</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{count($requests->where('status','Approved'))}}</h1>
                </div>
            </div>
        </div>
    </div>
    <div class='row'>
        <div class="col-lg-8">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Copy Requests </h5>
                  
                </div>
                <div class="ibox-content">

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover tables" >
                            <thead>
                                <tr>
                                    
                                    <th>Actions</th>
                                    <th>Reference No.</th>
                                    <th>Date Requested</th>
                                    <th>Document</th>
                                    <th>Request By</th>
                                    <th>Approvers</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($requests as $request)
                                    <tr>
                                        
                                        <td><a href="#"  data-target="#view_request{{$request->id}}" data-toggle="modal" class='btn btn-sm btn-info'><i class="fa fa-eye"></i></a></td>
                                        <td>CR-{{str_pad($request->id, 5, '0', STR_PAD_LEFT)}}</td>
                                        <td>{{date('M d Y',strtotime($request->created_at))}}</td>
                                        <td>
                                            <small>
                                                {{$request->control_code}} Rev. {{$request->revision}}<br>
                                                {{$request->title}} <br>
                                                {{$request->type_of_document}}
                                            </small>
                                        </td>
                                        <td>{{$request->user->name}}</td>
                                        <td>
                                            <small>
                                                @foreach($request->approvers as $approver)
                                                    {{$approver->user->name}} - @if($approver->status == "Pending")<span class='label label-danger'>@else<span class='label label-success'>@endif{{$approver->status}}</span> -  @if($request->level == $approver->level){{date('M d, Y',strtotime($approver->start_date))}}@endif  <br><br>
                                                @endforeach
                                            </small>
                                        </td>
                                        <td>{{$request->status}}</td>
                                    </tr>
                                    @include('view_copy_request')
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Public Documents ({{count($documents->where('public',1))}})</h5>
                </div>
                <div class="ibox-content">
                    <table class="table table-striped table-bordered table-hover tables">
                        <thead>
                        <tr>
                            <th>Document</th>
                            <th>File</th>
                            <th>Uploaded By</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($documents->where('public',1) as $document)
                            <tr>
                                <td>{{$document->title}}</td>
                                <td><a href="#"><i class="fa fa-file"></i> File</a></td>
                                <td>Amelia</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
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
