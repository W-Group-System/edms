@extends('layouts.header')
@section('css')
<link href="{{ asset('login_css/css/plugins/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
@endsection
@section('content')

<div class="wrapper wrapper-content">
    <div class='row'>
        <div class="col-lg-3">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <span class="label label-success pull-right">as of Today</span>
                    <h5>Total Forms/Templates Documents</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{(count($documents))}}</h1>
                    {{-- <div class="stat-percent font-bold text-success">98% <i class="fa fa-bolt"></i></div> --}}
                    <small>&nbsp;</small>
                </div>
            </div>
        </div>
    </div> 
    <div class='row'>
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Forms/Templates Reports</h5>
                  
                </div>
                <div class="ibox-content">

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover tables" >
                            <thead>
                                <tr>
                                    {{-- <th>Reference No.</th>
                                    <th>Date Requested</th>
                                    <th>Control Code</th>
                                    <th>Title</th>
                                    <th>Request By</th>
                                    <th>Status</th>
                                    s<th>Approver</th>
                                    <th>Start Date</th>
                                    <th>Action Date</th>
                                    <th>TAT</th>
                                    <th>Remarks</th>
                                    <th>Status</th> --}}
                                    <th>Control Code</th>
                                    <th>Department</th>
                                    <th>Title</th>
                                    <th>Category</th>
                                    <th>User</th>
                                    <th>Version</th>
                                    <th>Status</th>
                                    <th>Process Owner</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($documents as $document)
                                    <tr>
                                        <td>{{ $document->control_code }}</td>
                                        <td>{{ $document->company->code.' - '. $document->department->name }}</td>
                                        <td>{{ $document->title }}</td>
                                        <td>{{ $document->category }}</td>
                                        <td>{{ $document->user->name }}</td>
                                        <td>{{ $document->version }}</td>
                                        <td>{{ $document->status }}</td>
                                        <td>
                                            @if($document->processOwner)
                                            {{ $document->processOwner->name }}
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
{{-- @include('properties.create') --}}
@endsection
@section('js')
<script src="{{ asset('login_css/js/plugins/dataTables/datatables.min.js')}}"></script>
<script src="{{ asset('login_css/js/plugins/chosen/chosen.jquery.js') }}"></script>
<script>
    $(document).ready(function(){
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
