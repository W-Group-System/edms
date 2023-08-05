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
                        <h5>{{$document->title}} </h5><span class="label label-primary">Active</span>
                    </div>
                    <div class="ibox-content">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="m-b-md">
                                    <a href="#" class="btn btn-danger btn-sm ">Request Copy</a>
                                    <a href="#" class="btn btn-warning btn-sm ">Request Change</a>
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
                                </dl>
                            </div>
                            <div class="col-lg-6" >
                                <dl class="dl-horizontal" >

                                    <dt>Created:</dt> <dd> 	{{date('M d Y h:i:s',strtotime($document->updated_at))}} </dd>
                                    <dt>Last Updated:</dt> <dd>{{date('M d Y h:i:s',strtotime($document->created_at))}}</dd>
                                    <dt>Effective Date:</dt> <dd>{{date('M d Y',strtotime($document->effective_date))}}</dd>
                                    <dt>Process Owner:</dt> <dd>@if(count($document->department->drc) != 0) @foreach($document->department->drc as $drc) <small class="label label-info"> {{$drc->name}} </small> @endforeach @else <small class="label label-danger">No Process Owner</small>  @endif</dd>
                                    <dt>Access:</dt>
                                    <dd class="project-people">
                                        <a href=""><img alt="image" class="img-circle" src="{{asset('login_css/img/a3.jpg')}}"></a>
                                        <a href=""><img alt="image" class="img-circle" src="{{asset('login_css/img/a1.jpg')}}"></a>
                                        <a href=""><img alt="image" class="img-circle" src="{{asset('login_css/img/a2.jpg')}}"></a>
                                        <a href=""><img alt="image" class="img-circle" src="{{asset('login_css/img/a4.jpg')}}"></a>
                                        <a href=""><img alt="image" class="img-circle" src="{{asset('login_css/img/a5.jpg')}}"></a>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <dl class="dl-horizontal">

                                    <dt>Attachments</dt>
                                        @foreach($document->attachments as $attachment)
                                             <dd><a href='' >{{$attachment->type}}</a></dd>
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
                                        <li class=""><a href="#tab-2" data-toggle="tab">DICR</a></li>
                                        <li class=""><a href="#tab-3" data-toggle="tab">Copy Request</a></li>
                                    </ul>
                                </div>
                            </div>

                            <div class="panel-body">

                            <div class="tab-content">
                            <div class="tab-pane active" id="tab-1">
                                <table class="table table-striped">
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
                                       

                                    </tbody>
                                </table>
                            </div>
                            <div class="tab-pane" id="tab-2">

                                <table class="table table-striped">
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
                                    </tbody>
                                </table>

                            </div>
                            <div class="tab-pane" id="tab-3">

                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Reference #</th>
                                            <th>Type of Document</th>
                                            <th>Date Requested</th>
                                            <th>Date Needed</th>
                                            <th>Requestor</th>
                                            <th>Status</th>
                                            <th>Issued By</th>
                                            <th>Status</th>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
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
