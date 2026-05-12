@extends('layouts.header')
@section('css')
<link href="{{ asset('login_css/css/plugins/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
<link href="{{ asset('login_css/css/plugins/sweetalert/sweetalert.css') }}" rel="stylesheet">
@endsection
@section('content')

<div class="wrapper wrapper-content">
    <div class="row">
        @if(auth()->user()->department_id != 8)
        <div class="col-lg-3">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>For Upload</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins"><a
                            href='{{url("acknowledgement")}}'>{{count($requests->where('acknowledgement',null))}}</a>
                    </h1>
                </div>
            </div>
        </div>
        @endif
        <div class="col-lg-3">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Uploaded</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins"><a
                            href='{{url("uploaded-acknowledgement")}}'>{{count($requests->where('acknowledgement','!=',null))}}</a>
                    </h1>
                </div>
            </div>
        </div>
    </div>
    <div class='row'>
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>For Upload </h5>

                </div>
                <div class="ibox-content">

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover tables">
                            <thead>
                                <tr>

                                    <th>Actions</th>
                                    <th>Reference No.</th>
                                    <th>Request Type</th>
                                    <th>Date Requested</th>
                                    <th>Code</th>
                                    <th>Title</th>
                                    <th>Date Cascade</th>
                                    <th>Type</th>
                                    <th>Requested By</th>
                                    <th>Status</th>
                                    @if($requests->where('user_id', auth()->user()->id)->isNotEmpty())
                                        <th>Acknowledge</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @if(auth()->user()->department_id == 8)
                                    @foreach($requests->where('acknowledgement', '!=', null) as $request)
                                    <tr>

                                        <td>
                                            <a href="#" data-target="#view_request{{$request->id}}" data-toggle="modal"
                                                class='btn btn-sm btn-info'><i class="fa fa-eye"></i></a>
                                            @if(auth()->user()->department_id != 8)
                                            <a href="#" data-target="#upload{{$request->id}}" data-toggle="modal"
                                                class='btn btn-sm btn-warning'><i class="fa fa-upload"></i></a>
                                            @endif

                                        </td>
                                        <td>DICR-{{str_pad($request->id, 5, '0', STR_PAD_LEFT)}}</td>
                                        <td>{{$request->request_type}}</td>
                                        <td>{{date('M d Y',strtotime($request->created_at))}}</td>

                                        @if($request->document_id != null)
                                        <td>
                                            {{$request->control_code}}
                                        </td>
                                        <td>
                                            {{$request->title}}
                                        </td>
                                        <td>
                                            {{date('Y-m-d',strtotime($request->updated_at))}}
                                        </td>

                                        @else
                                        <td></td>
                                        <td>{{$request->title}}</td>
                                        <td></td>
                                        @endif
                                        <td>
                                            {{$request->type_of_document}}
                                        </td>
                                        <td>{{$request->user->name}}</td>
                                        <td> @if($request->status == "Pending")
                                            <span class='label label-warning'>
                                                @elseif($request->status == "Approved")
                                                <span class='label label-info'>
                                                    @elseif($request->status == "Declined")
                                                    <span class='label label-danger'>
                                                        @else<span class='label label-success'>
                                                            @endif
                                                            {{$request->status}}</span>
                                        </td>
                                    </tr>
                                    @include('view_change_request')
                                    @include('upload')
                                    @endforeach
                                @else
                                    @foreach($requests->where('acknowledgement',null) as $request)
                                    <tr>

                                        <td>
                                            <a href="#" data-target="#view_request{{$request->id}}" data-toggle="modal"
                                                class='btn btn-sm btn-info'><i class="fa fa-eye"></i></a>
                                            @if(auth()->user()->department_id != 8)
                                            <a href="#" data-target="#upload{{$request->id}}" data-toggle="modal"
                                                class='btn btn-sm btn-warning'><i class="fa fa-upload"></i></a>
                                            @endif

                                        </td>
                                        <td>DICR-{{str_pad($request->id, 5, '0', STR_PAD_LEFT)}}</td>
                                        <td>{{$request->request_type}}</td>
                                        <td>{{date('M d Y',strtotime($request->created_at))}}</td>

                                        @if($request->document_id != null)
                                        <td>
                                            {{$request->control_code}}
                                        </td>
                                        <td>
                                            {{$request->title}}
                                        </td>
                                        <td>
                                            {{date('Y-m-d',strtotime($request->updated_at))}}
                                        </td>

                                        @else
                                        <td></td>
                                        <td>{{$request->title}}</td>
                                        <td></td>
                                        @endif
                                        <td>
                                            {{$request->type_of_document}}
                                        </td>
                                        <td>{{$request->user->name}}</td>
                                        <td> @if($request->status == "Pending")
                                            <span class='label label-warning'>
                                                @elseif($request->status == "Approved")
                                                <span class='label label-info'>
                                                    @elseif($request->status == "Declined")
                                                    <span class='label label-danger'>
                                                        @else<span class='label label-success'>
                                                            @endif
                                                            {{$request->status}}</span>
                                        </td>
                                        @if($requests->where('user_id', auth()->user()->id)->isNotEmpty())
                                            <td>
                                                <button class="btn btn-sm btn-success acknowledge_request"
                                                    id="{{ $request->id }}">
                                                    <i class="fa fa-thumbs-up"></i>
                                                </button>
                                            </td>
                                        @endif
                                    </tr>
                                    @include('view_change_request')
                                    @include('upload')
                                    @endforeach
                                @endif
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
<script src="{{ asset('login_css/js/plugins/sweetalert/sweetalert.min.js') }}"></script>

<script>
    $(document).ready(function(){

        $('.acknowledge_request').click(function () {
        
        var id = this.id;
            swal({
                title: "Are you sure?",
                text: "This Request will be Acknowledged!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, Acknowledge it!",
                closeOnConfirm: false
            }, function (){
                $.ajax({
                    dataType: 'json',
                    type:'POST',
                    url:  '{{url("acknowledge_request")}}',
                    data:{id:id},
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                }).done(function(data){
                    console.log(data);
                    swal("Acknowledged!", "Request is now Acknowledged.", "success");
                    location.reload();
                }).fail(function(data)
                {
                    
                    swal("Acknowledged!", "Request is now Acknowledged.", "success");
                location.reload();
                });
            });
        });
        

        $('.locations').chosen({width: "100%"});
        $('.tables').DataTable({
            pageLength: 25,
            responsive: true,
            sorting:false,
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