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
                        <h5>Approved Supporting Documents</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins">
                            {{ $supporting_documents->filter(fn($doc) => $doc->status == 'Approved' || is_null($doc->status))->count() }}
                        </h1>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Declined Supporting Documents</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins">
                            {{ $supporting_documents->filter(fn($doc) => $doc->status == 'Declined')->count() }}
                        </h1>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Supporting Documents
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
                                        <th>Document Type</th>
                                        <th>Department</th>
                                        <th>Title</th>
                                        <th>Uploaded By</th>
                                        <th>Attachment</th>
                                        <th>Remarks</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($supporting_documents as $supporting_document)

                                        {{-- Filter collection loop check --}}
                                        @if(request('status_filter'))
                                            @php
                                                $filter = request('status_filter');
                                                $isApproved = ($supporting_document->status == 'Approved' || is_null($supporting_document->status));
                                                
                                                if ($filter == 'Approved' && !$isApproved) {
                                                    continue;
                                                }
                                                if ($filter == 'Declined' && $supporting_document->status != 'Declined') {
                                                    continue;
                                                }
                                            @endphp
                                        @endif

                                        <tr>
                                            <td>
                                                @if($supporting_document->status != 'Declined')
                                                    <button type="button" class="btn btn-sm btn-danger deleteSuppDocs" id="{{ $supporting_document->id }}">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                 @else
                                                    <span class="text-muted" style="font-size: 11px; font-style: italic;">-</span>
                                                @endif
                                               
                                            </td>
                                            <td>
                                                @if($supporting_document->others)
                                                {{ $supporting_document->supporting_docs }} - {{ $supporting_document->others }} 
                                                @else
                                                {{ $supporting_document->supporting_docs }}
                                                @endif
                                            </td>
                                            <td>
                                                @foreach ($supporting_document->supporting_document_dept  as $key=>$support_docs_dept)
                                                    <small>{{ $key+1 }}.</small>
                                                    {{ $support_docs_dept->department->name }} <br>
                                                @endforeach 
                                            </td>
                                            <td>{{ $supporting_document->title }}</td>
                                            <td>{{ $supporting_document->uploadedBy->name }}</td>
                                            <td>
                                                <a href="{{ url($supporting_document->file) }}" target="_blank">
                                                    <i class="fa fa-file"></i>
                                                </a>
                                            </td>
                                           <td>
                                                @if($supporting_document->status == 'Declined')
                                                    <span class="text-danger">{{ $supporting_document->remarks ?? 'N/A' }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($supporting_document->status == 'Declined')
                                                    <span class="label label-danger">
                                                        Declined
                                                    </span>
                                                @else
                                                    <span class="label label-success">
                                                        {{ $supporting_document->status ?? 'Approved' }}
                                                    </span>
                                                @endif
                                            </td>
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

    @include('new_supporting_documents')
@endsection

@section('js')
<script src="{{ asset('login_css/js/plugins/dataTables/datatables.min.js')}}"></script>
<script src="{{ asset('login_css/js/plugins/chosen/chosen.jquery.js') }}"></script>
<script src="{{ asset('login_css/js/plugins/sweetalert/sweetalert.min.js') }}"></script>
<script>
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

        $("[name='supporting_documents']").on('change', function() {
            if($(this).val() == 'Others')
            {
                $("#othersCol").removeAttr('hidden')
                $("[name='others']").prop('required', true)
            }
            else
            {
                $("#othersCol").prop('hidden', true)
                $("[name='others']").removeAttr('required')
            }
        })

        $('.deleteSuppDocs').click(function () {
            var id = this.id;
            
            swal({
                title: "Are you sure?",
                text: "This supporting document will be deleted!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete it!",
                closeOnConfirm: false
            }, function (){
                $.ajax({
                    dataType: 'json',
                    type:'POST',
                    url:  '{{url("destroy_supporting_document")}}',
                    data:{id:id},
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                }).done(function(data){
                    console.log(data);
                    swal("Deleted!", "Supporting Document is now deleted.", "success");
                    location.reload();
                }).fail(function(data)
                {
                    swal("Deleted!", "Supporting Document is now deleted.", "success");
                    location.reload();
                });
            });
        });
    });

</script>
@endsection