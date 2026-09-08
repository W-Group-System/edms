@extends('layouts.header')

@section('css')
<link href="{{ asset('login_css/css/plugins/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
<link href="{{ asset('login_css/css/plugins/sweetalert/sweetalert.css') }}" rel="stylesheet">
@endsection

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
    {{-- Card status --}}
    <div class="row">
        {{-- For approval counter --}}
        <div class="col-lg-3">
            <div class="ibox">
                <div class="ibox-title">
                    <h5>For Approval</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">
                        {{ $supporting_documents->where('status', 'Pending')->count() }}
                    </h1>
                </div>
            </div>
        </div>

        {{-- Declined Counter --}}
        <div class="col-lg-3">
            <div class="ibox">
                <div class="ibox-title">
                    <h5>Declined</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">
                        {{ $supporting_documents->where('status', 'Declined')->count() }}
                    </h1>
                </div>
            </div>
        </div>


    </div>
    {{-- End card status --}}

    {{-- Table --}}
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <!-- Header -->
                <div class="ibox-title">
                    <h5>For Approval Supporting Documents</h5>
                     {{-- DROPDOWN FILTER START --}}
                    <div class="ibox-tools">
                        <form method="GET" action="" id="filterForm" style="display: inline-block; width: 150px;">
                            <select name="status_filter" id="status_filter" class="form-control input-sm" onchange="this.form.submit()">
                                <option value="">All Status</option>
                                <option value="Pending" {{ request('status_filter') == 'Pending' ? 'selected' : '' }}>Pending</option>
                                <option value="Declined" {{ request('status_filter') == 'Declined' ? 'selected' : '' }}>Declined</option>
                            </select>
                        </form>
                    </div>
                    {{-- DROPDOWN FILTER END --}}
                </div>

                <!-- Content -->
                <div class="ibox-content">
                    <div class="table table-responsive">
                        <table class="table table-striped table-bordered table-hover tables">
                            <thead>
                                <tr>
                                    <th>Action</th>
                                        <th>Document Type</th>
                                        <th>Department</th>
                                        <th>Title</th>
                                        <th>Uploaded By</th>
                                        <th>Attachment</th>
                                        <th>Status</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($supporting_documents as $supporting_document)
                                  {{-- Filter collection --}}
                                    @if(request('status_filter'))
                                        @php
                                            if ($supporting_document->status != request('status_filter')) {
                                                continue;
                                            }
                                        @endphp
                                    @endif
                                    <tr>
                                        <td>
                                            <button type="button"
                                                class="btn btn-sm btn-primary"
                                                title="Approved"
                                                data-toggle="modal"
                                                data-target="#approved{{ $supporting_document->id }}">
                                                <i class="fa fa-check"></i>
                                            </button>
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
                                                <span class="label label-danger">
                                                    Declined
                                                </span>
                                            @else
                                                <span class="label label-primary">
                                                    {{ $supporting_document->status ?? 'Pending' }}
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                    <!-- Modal -->
                                    @include('approved_supporting_documents')
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- End table --}}
</div>
@endsection

@section('js')
<script src="{{ asset('login_css/js/plugins/dataTables/datatables.min.js') }}"></script>
<script src="{{ asset('login_css/js/plugins/chosen/chosen.jquery.js') }}"></script>
<script src="{{ asset('login_css/js/plugins/sweetalert/sweetalert.min.js') }}"></script>

<script>
$(document).ready(function() {
    $('.tables').DataTable({
        pageLength: 25,
        responsive: true,
        dom: '<"html5buttons"B>lTfgitp',
        buttons: [
            { extend: 'copy' },
            { extend: 'csv' },
            { extend: 'excel', title: 'For Approval Supporting Documents' },
            { extend: 'pdf', title: 'For Approval Supporting Documents' },
            { 
                extend: 'print',
                customize: function(win) {
                    $(win.document.body).addClass('white-bg').css('font-size', '10px');
                    $(win.document.body).find('table').addClass('compact').css('font-size', 'inherit');
                }
            }
        ]
    });
}); 
</script>
@endsection