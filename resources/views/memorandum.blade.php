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
                        <h5>Memorandum</h5>
                    </div>
                    <div class="ibox-content">
                        <h1 class="no-margins">0</h1>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Memorandum
                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#new">
                                <i class="fa fa-plus"></i>
                                &nbsp;
                                Upload
                            </button>
                        </h5>
                    </div>
                    <div class="ibox-content">
                        <div class="table table-responsive">
                            <table class="table table-striped table-bordered table-hover tables" >
                                <thead>
                                    <tr>
                                        <th>Action</th>
                                        <th>Department</th>
                                        <th>Memo Number</th>
                                        <th>Title</th>
                                        <th>Released Date</th>
                                        <th>Uploaded By</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($memos as $memo)
                                        <tr>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-info" title="View" data-toggle="modal" data-target="#view{{$memo->id}}">
                                                    <i class="fa fa-eye"></i>
                                                </button>

                                                <button type="button" class="btn btn-sm btn-warning" title="Edit" data-toggle="modal" data-target="#edit{{$memo->id}}">
                                                    <i class="fa fa-pencil-square-o"></i>
                                                </button>
                                            </td>
                                            <td>{{$memo->department->name}}</td>
                                            <td>{{$memo->memo_number}}</td>
                                            <td>{{$memo->title}}</td>
                                            <td>{{date('M d Y', strtotime($memo->released_date))}}</td>
                                            <td>{{$memo->user->name}}</td>
                                        </tr>

                                        @include('edit_memorandum')
                                        @include('view_memorandum')
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

    });

</script>
@endsection