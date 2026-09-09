@extends('layouts.header')

@section('css')
<link href="{{ asset('login_css/css/plugins/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
<link href="{{ asset('login_css/css/plugins/sweetalert/sweetalert.css') }}" rel="stylesheet">
@endsection

@section('content')
    <div class="wrapper wrapper-content">
        <div class="row">
            <div class="col-lg-3">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Approved Memorandum</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins">{{ $memos->filter(fn($doc) => $doc->final_status == 'Approved' || is_null($doc->final_status))->count() }}</h1>
                    </div>
                </div>
            </div>
            <div class="col-lg-3"> 
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Declined Memorandum</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins">{{ $memos->filter(fn($doc) => $doc->final_status == 'Declined' && $doc->uploaded_by == auth()->id())->count() }}</h1>
                    </div>
                    
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Memorandum
                            {{-- @if(auth()->user()->role == 'User' || auth()->user()->role == 'Document Control Officer' || auth()->user()->role == 'Administrator' || auth()->user()->role == 'Business Process Manager') --}}
                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#new">
                                <i class="fa fa-plus"></i>
                                &nbsp;
                                Upload
                            </button>
                            {{-- @endif --}}
                        </h5>

                         
                    {{-- DROPDOWN FILTER START --}}
                    <div class="ibox-tools">
                        <form method="GET" action="" id="filterForm" style="display: inline-block; width: 150px;">
                            <select name="status_filter" id="status_filter" class="form-control input-sm" onchange="this.form.submit()">
                                <option value="">All Status</option>
                                <option value="Approved" {{ request('status_filter') == 'Approved' ? 'selected' : '' }}>Approved</option>
                                <option value="Declined" {{ request('status_filter') == 'Declined' ? 'selected' : '' }}>Declined</option>
                            </select>
                        </form>
                    </div>
                    {{-- DROPDOWN FILTER END --}}
                    </div>
                    
                    <div class="ibox-content">
                        <div class="table table-responsive">
                            <table class="table table-striped table-bordered table-hover tables" >
                                <thead>
                                    <tr>
                                        <th>Action</th>
                                        <th>Public</th>
                                        <th>Department</th>
                                        <th>Memo Number</th>
                                        <th>Title</th>
                                        <th>Released Date</th>
                                        <th>Uploaded By</th>
                                        <th>Align Policy</th>
                                        <th>Attachment</th>
                                        <th>Remarks</th>
                                        <th>Visibility</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($memos as $memo)

                                        {{-- Filter collection loop check --}}
                                        @if(request('status_filter'))
                                            @php
                                                $filter = request('status_filter');
                                                $isApproved = ($memo->final_status == 'Approved' || is_null($memo->final_status));
                                                
                                                if ($filter == 'Approved' && !$isApproved) {
                                                    continue;
                                                }
                                                if ($filter == 'Declined' && $memo->final_status != 'Declined') {
                                                    continue;
                                                }
                                            @endphp
                                        @endif

                                        <tr>
                                            <td>
                                                @if($memo->final_status != 'Declined')
                                                    {{-- <button type="button" class="btn btn-sm btn-info" title="View" data-toggle="modal" data-target="#view{{$memo->id}}">
                                                        <i class="fa fa-eye"></i>
                                                    </button> --}}
                                                    
                                                    @if($memo->department_id == auth()->user()->department_id)
                                                    <button type="button" class="btn btn-sm btn-warning" title="Edit" data-toggle="modal" data-target="#edit{{$memo->id}}">
                                                        <i class="fa fa-pencil-square-o"></i>
                                                    </button>
                                                    @endif
                                                    
                                                    @if(auth()->user()->role == 'Document Control Officer')
                                                    <button type="button" class="btn btn-sm btn-danger deleteMemo" id="{{ $memo->id }}">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                    @endif
                                                @else
                                                    <span class="text-muted" style="font-size: 11px; font-style: italic;">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if(auth()->user()->role == 'Document Control Officer' || auth()->user()->role == 'Administrator')
                                                    <form method="POST" action="{{url('update_status/'.$memo->id)}}" onsubmit="show()" id="updateStatusForm{{$memo->id}}">
                                                        @csrf 

                                                        <input type="checkbox" name="status" class="form-check" onchange="updateStatus({{$memo->id}})" value="Public" 
                                                            @if($memo->status == 'Public') checked @endif
                                                            @if($memo->final_status == 'Declined') disabled @endif>
                                                    </form>
                                                @endif
                                            </td>

                                            <td>{{$memo->department->name ?? 'N/A'}}</td>
                                            <td>{{$memo->memo_number}}</td>
                                            <td>{{$memo->title}}</td>
                                            <td>{{date('M d Y', strtotime($memo->released_date))}}</td>
                                            <td>{{$memo->user->name ?? 'N/A'}}</td>
                                            <td>
                                                @foreach ($memo->memorandum_document as $memo_docs)
                                                    <a href="{{url('view-document/'.$memo_docs->document->id)}}">{{$memo_docs->document->control_code}}</a> <br>
                                                @endforeach
                                            </td>
                                            <td>
                                                <a href="{{url($memo->file_memo)}}" target="_blank">
                                                    <i class="fa fa-file"></i>
                                                </a>
                                            </td>
                                            <td>
                                                @if($memo->final_status == 'Declined')
                                                    <span class="text-danger">{{ $memo->remarks ?? 'N/A' }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($memo->status == 'Private')
                                                    <span class="label label-danger">Private</span>
                                                @else
                                                    <span class="label label-success">Public</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($memo->final_status == 'Declined')
                                                    <span class="label label-warning">
                                                        Declined
                                                    </span>
                                                @else
                                                    <span class="label label-primary">
                                                        {{ $memo->final_status ?? 'Approved' }}
                                                    </span>
                                                @endif
                                            </td> 
                                           
                                        </tr>

                                        @include('edit_memorandum')
                                        @include('approved_memorandum')
                                        {{-- @include('view_memorandum') --}}
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('new_memorandum')
@endsection

@section('js')
<script src="{{ asset('login_css/js/plugins/dataTables/datatables.min.js')}}"></script>
<script src="{{ asset('login_css/js/plugins/chosen/chosen.jquery.js') }}"></script>
<script src="{{ asset('login_css/js/plugins/sweetalert/sweetalert.min.js') }}"></script>
<script>
    function updateStatus(id)
    {
        $('#updateStatusForm'+id).submit()
        
    }

    $(document).ready(function(){
        $(".cat").chosen({width:"100%"})

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

        $("#type").on('change', function() {
            if($(this).val() == 'Align Policy')
            {
                $("#policySelectOption").removeAttr('hidden')
                $("[name='document[]']").prop('required', true)
            }
            else
            {
                $("#policySelectOption").prop('hidden', true)
                $("[name='document[]']").removeAttr('required')
            }
        })

        $('.deleteMemo').click(function () {
            var id = this.id;
            
            swal({
                title: "Are you sure?",
                text: "This memo will be deleted!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete it!",
                closeOnConfirm: false
            }, function (){
                $.ajax({
                    dataType: 'json',
                    type:'POST',
                    url:  '{{url("delete_memo")}}',
                    data:{id:id},
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                }).done(function(data){
                    console.log(data);
                    swal("Deleted!", "Memo is now deleted.", "success");
                    location.reload();
                }).fail(function(data)
                {
                    
                    swal("Deleted!", "Memo is now deleted.", "success");
                location.reload();
                });
            });
        });
    });

</script>
@endsection