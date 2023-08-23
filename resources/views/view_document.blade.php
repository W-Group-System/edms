@extends('layouts.header')
@section('css')
<link href="{{ asset('login_css/css/plugins/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
@endsection
@section('content')

<div class="wrapper wrapper-content">
    @include('error')
   
    <div class="row">
        <div class="col-lg-12">
            <div class="wrapper wrapper-content ">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>{{$document->title}} </h5>@if($document->status == null)<span class="label label-primary">Active</span> @else<span class="label label-danger">Obsolete</span> @endif
                    </div>
                    <div class="ibox-content">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="m-b-md">
                                    @if($document->status != "Obselete")
                                        @if((auth()->user()->role == "User") || (auth()->user()->role == "Documents and Records Controller") || (auth()->user()->role == "Document Control Officer"))
                                            @if(auth()->user()->role == "Documents and Records Controller")
                                                @if(auth()->user()->department_id != $document->department_id)
                                                    <a href="#" data-target="#copyRequest" data-toggle="modal"  class="btn btn-success btn-sm ">Copy Request </a>
                                                @endif
                                            @elseif(auth()->user()->role == "Document Control Officer") 
                                                @php
                                                    $dep = (auth()->user()->dco)->where('department_id',$document->department_id);
                                                @endphp
                                                @if(count($dep) == 0)
                                                    <a href="#" data-target="#copyRequest" data-toggle="modal"  class="btn btn-success btn-sm ">Copy Request </a>
                                                @endif
                                            @else
                                                <a href="#" data-target="#copyRequest" data-toggle="modal"  class="btn btn-success btn-sm ">Copy Request </a>
                                            @endif
                                        @endif
                                    @if(auth()->user()->role == "Documents and Records Controller")
                                        @if(auth()->user()->department_id == $document->department_id)
                                            <a href="#" data-target="#changeRequest" data-toggle="modal" class="btn btn-warning btn-sm ">Change Request </a>
                                            <a href="#"  data-target="#obsoleteRequest" data-toggle="modal"  class="btn btn-danger btn-sm ">Obsolete Request </a>
                                        @endif
                                    @endif
                                    @if(auth()->user()->role == "Document Control Officer")
                                        @php
                                            $dep = (auth()->user()->dco)->where('department_id',$document->department_id);
                                        @endphp
                                        @if(count($dep) == 1)
                                            <a href="#" data-target="#changeRequest" data-toggle="modal" class="btn btn-warning btn-sm ">Change Request </a>
                                            <a href="#"  data-target="#obsoleteRequest" data-toggle="modal"  class="btn btn-danger btn-sm ">Obsolete Request </a>
                                        @endif
                                    @endif
                                    
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-lg-6">
                                <dl class="dl-horizontal">
                                    <dt>Uploaded by:</dt> <dd>{{$document->user->name}}</dd>
                                    <dt>Control Code:</dt> <dd>  {{$document->control_code}}</dd>
                                    <dt>Revisions:</dt> <dd>  {{$document->version}}</dd>
                                    <dt>Type of Document:</dt> <dd>  {{$document->category}}</dd>
                                    <dt>Company:</dt> <dd>{{$document->company->name}}</dd>
                                    <dt>Department:</dt> <dd> 	{{$document->department->name}}</dd>
                                    <dt>Department Head:</dt> <dd> 	@if($document->department->dep_head){{$document->department->dep_head->name}}@else <small class='label label-danger'>No Department Head</small>@endif</dd>
                                </dl>
                            </div>
                            <div class="col-lg-6" >
                                <dl class="dl-horizontal" >

                                    <dt>Created:</dt> <dd> 	{{date('M, d Y h:i:s',strtotime($document->updated_at))}} </dd>
                                    <dt>Last Updated:</dt> <dd>{{date('M, d Y h:i:s',strtotime($document->created_at))}}</dd>
                                    <dt>Effective Date:</dt> <dd>{{date('M, d Y',strtotime($document->effective_date))}}</dd>
                                    <dt>Process Owner:</dt> <dd>@if(count($document->department->drc) != 0) @foreach($document->department->drc as $drc) <small class="label label-info"> {{$drc->name}} </small> @endforeach @else <small class="label label-danger">No Process Owner</small>  @endif</dd>
                                    {{-- <dt>Access:</dt>
                                    <dd class="project-people">
                                        <a href=""><img alt="image" class="img-circle" src="{{asset('login_css/img/a3.jpg')}}"></a>
                                        <a href=""><img alt="image" class="img-circle" src="{{asset('login_css/img/a1.jpg')}}"></a>
                                        <a href=""><img alt="image" class="img-circle" src="{{asset('login_css/img/a2.jpg')}}"></a>
                                        <a href=""><img alt="image" class="img-circle" src="{{asset('login_css/img/a4.jpg')}}"></a>
                                        <a href=""><img alt="image" class="img-circle" src="{{asset('login_css/img/a5.jpg')}}"></a>
                                    </dd> --}}
                                </dl>
                            </div>
                        </div>
                        @php
                            $allow = 0;
                        @endphp
                        @foreach($document->department->drc as $drc)
                            @if($drc->id == auth()->user()->id)
                                @php
                                        $allow = 1;
                                @endphp
                            @endif
                        @endforeach
                        @foreach($document->department->dco as $dco)
                            @if($dco->user_id == auth()->user()->id)
                                @php
                                        $allow = 1;
                                @endphp
                            @endif
                        @endforeach
                        @if((auth()->user()->role == "Administrator") || (auth()->user()->role == "Business Process Manager") || (auth()->user()->role == "Management Representative"))
                            @php
                                $allow = 1;
                            @endphp
                        @endif
                        @foreach(auth()->user()->department_head as $head)
                            @if($head->user_id == auth()->user()->id)
                                @php
                                        $allow = 1;
                                @endphp
                            @endif
                        @endforeach
                        @if($allow == 1)
                        <div class="row">
                            <div class="col-lg-12">
                                <dl class="dl-horizontal">

                                    <dt>Attachments</dt>
                                        @foreach($document->attachments as $attachment)
                                            @if($attachment->attachment != null)
                                                @if($attachment->type == "soft_copy")
                                                <dd><a href='{{url($attachment->attachment)}}' target="_blank" ><i class="fa fa-file-word-o"></i> Editable Copy</a></dd>
                                                @elseif($attachment->type == "pdf_copy")
                                                <dd><a href='{{url($attachment->attachment)}}' target="_blank" ><i class="fa fa-file-pdf-o"></i> PDF Copy</a></dd>
                                                @else
                                                <dd><a href='{{url($attachment->attachment)}}' target="_blank" ><i class="fa fa-file-pdf-o"></i> Fillable Copy</a></dd>
                                                @endif
                                            
                                             @endif
                                        @endforeach
                                   
                                </dl>
                            </div>
                        </div>
                        <div class="row m-t-sm">
                            <div class="col-lg-12">
                            <div class="panel blank-panel">
                            <div class="panel-heading">
                                <div class="panel-options">
                                    <ul class="nav nav-tabs">
                                        <li class="active"><a href="#tab-1" data-toggle="tab">Revisions</a></li>
                                        <li class=""><a href="#tab-2" data-toggle="tab">Change Requests</a></li>
                                        <li class=""><a href="#tab-3" data-toggle="tab">Copy Requests</a></li>
                                    </ul>
                                </div>
                            </div>
                            
                            <div class="panel-body">

                            <div class="tab-content">
                            <div class="tab-pane active" id="tab-1">
                                <table class="table table-striped tables">
                                    <thead>
                                        <tr>
                                            <th>Control Code</th>
                                            <th>Company</th>
                                            <th>Department</th>
                                            <th>Revision</th>
                                            <th>Date Obsolete</th>
                                            <th>Obsolete By</th>
                                            <th>Attachments</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($document->revisions as $revision)
                                            <tr>
                                                <td>{{$revision->control_code}}</td>
                                                <td>{{$revision->company->name}}</td>
                                                <td>{{$revision->department->name}}</td>
                                                <td>{{$revision->version}}</td>
                                                <td>{{date('M d, Y',strtotime($revision->created_at))}}</td>
                                                <td>{{$revision->user->name}}</td>
                                                <td>
                                                    @foreach($revision->attachments as $att)
                                                    <a href='{{url($att->attachment)}}' target='_blank'>{{$att->type}}</a>
                                                    <br>
                                                    @endforeach
                                            </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane" id="tab-2">

                                <table class="table table-striped tables">
                                    <thead>
                                        <tr>
                                            
                                            <th>DICR #</th>
                                            <th>Type of Request</th>
                                            <th>Requested Date</th>
                                            <th>Requestor</th>
                                            <th>Department</th>
                                            <th>Proposed Effective Date</th>
                                            <th>Type of Document</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($document->change_requests as $change_req)
                                            <tr>
                                                
                                                <td>DICR-{{str_pad($change_req->id, 5, '0', STR_PAD_LEFT)}}</td>
                                                <td>{{$change_req->request_type}}</td>
                                                <td>{{date('M d Y',strtotime($change_req->created_at))}}</td>
                                                <td>{{$change_req->user->name}}</td>
                                                <td>{{$change_req->department->name}}</td>
                                                <td>{{date('M d, Y',strtotime($change_req->effective_date))}}</td>
                                                <td>{{$change_req->type_of_document}}</td>
                                                <td>@if($change_req->status == "Pending")
                                                    <span class='label label-warning'>
                                                @elseif($change_req->status ==  "Approved")
                                                    <span class='label label-info'>    
                                                @elseif($change_req->status ==  "Declined")
                                                        <span class='label label-danger'>
                                                @else<span class='label label-success'>
                                                    @endif
                                                    {{$change_req->status}}</span> </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                            </div>
                            <div class="tab-pane" id="tab-3">

                                <table class="table table-striped tables">
                                    <thead>
                                        <tr>
                                            <th>Reference #</th>
                                            <th>Type of Document</th>
                                            <th>Date Requested</th>
                                            <th>Date Needed</th>
                                            <th>Requestor</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($document->copy_requests as $copy_request)
                                        <tr>
                                            <td>CR-{{str_pad($copy_request->id, 5, '0', STR_PAD_LEFT)}}</td>
                                            <td>{{$copy_request->type_of_document}}</td>
                                            <td>{{date('M d Y',strtotime($copy_request->created_at))}}</td>
                                            <td>{{date('M d Y',strtotime($copy_request->date_needed))}}</td>
                                            <td>{{$copy_request->user->name}}</td>
                                            <td>{{$copy_request->status}}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                            </div>
                            </div>

                            </div>

                            </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('copy_request')
@include('change_request')
@include('obsolete_request')
@endsection
@section('js')
<script src="{{ asset('login_css/js/plugins/dataTables/datatables.min.js')}}"></script>
<script src="{{ asset('login_css/js/plugins/chosen/chosen.jquery.js') }}"></script>
<script>
    $(document).ready(function(){
        

        $('.cat').chosen({width: "100%"});
        $('.tables').DataTable({
            pageLength: 25,
            responsive: true,
            bPaginate: false,
            bInfo : false,
            dom: '<"html5buttons"B>lTfgitp',
            buttons: [
                {extend: 'pdf', title: 'Histories'},

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
