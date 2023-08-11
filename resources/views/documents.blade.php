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
                    <h5>Documents</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{count($documents->where('status',null))}}</h1>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>New Documents this {{date('M Y')}}</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{count($documents->whereBetween('created_at',[date('Y-m-01'), date('Y-m-t')]))}}</h1>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Revised this {{date('M Y')}}</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{count($documents->whereBetween('updated_at',[date('Y-m-01'), date('Y-m-t')]))}}</h1>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Obsoletes</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{count($obsoletes)+count($documents->where('status',"Obsolete"))}}</h1>
                </div>
            </div>
        </div>
    </div>
    <div class='row'>
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Documents
                        @if((auth()->user()->role == 'Administrator') || (auth()->user()->role == 'Business Process Manager') || (auth()->user()->role == 'Management Representative') ||(auth()->user()->role == 'Document Control Officer') )
                        <button class="btn btn-success "  data-target="#uploadDocument" data-toggle="modal" type="button"><i class="fa fa-plus"></i>&nbsp;Upload Document</button></h5>
                        @endif
                    </div>
                <div class="ibox-content">

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover tables" >
                        <thead>
                            <tr>
                                <th>Action</th>
                                <th>Control Code</th>
                                <th>Revisions</th>
                                <th>Company</th>
                                <th>Department</th>
                                <th>Document</th>
                                <th>Type of Document</th>
                                <th>Effective Date</th>
                                <th>Process Owner</th>
                                <th>Uploaded By</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($documents as $document)
                                <tr>
                                    <td><a href="{{url('view-document/'.$document->id)}}" target="_blank" class='btn btn-sm btn-info'><i class="fa fa-eye"></i></a></td>
                                    <td>{{$document->control_code}}</td>
                                    <td>{{$document->version}}</td>
                                    <td>{{$document->company->name}}</td>
                                    <td>{{$document->department->name}}</td>
                                    <td>{{$document->title}}</td>
                                    <td>{{$document->category}}</td>
                                    <td>{{date('M d, Y',strtotime($document->effective_date))}}</td>
                                    <td>@if(count($document->department->drc) != 0) @foreach($document->department->drc as $drc) <small class="label label-info"> {{$drc->name}} </small> <br>@endforeach @else <small class="label label-danger">No Process Owner</small>  @endif</td>
                                    <td>{{$document->user->name}}</td>
                                    <td>@if($document->status == null)<span class="label label-primary">Active</span> @else<span class="label label-danger">Obsolete</span> @endif</td>
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
@include('upload_document')
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
