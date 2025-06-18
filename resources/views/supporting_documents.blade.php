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
                        <h5>Supporting Documents</h5>
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
                        <h5>Supporting Documents
                            {{-- @if(auth()->user()->role == 'User' || auth()->user()->role == 'Document Control Officer' || auth()->user()->role == 'Administrator' || auth()->user()->role == 'Business Process Manager') --}}
                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#new">
                                <i class="fa fa-plus"></i>
                                &nbsp;
                                Upload
                            </button>
                            {{-- @endif --}}
                        </h5>
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
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($supporting_documents as $supporting_document)
                                        <tr>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-danger deleteSuppDocs" id="{{ $supporting_document->id }}">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                            <td>{{ $supporting_document->supporting_docs }}</td>
                                            <td>{{ $supporting_document->department->name }}</td>
                                            <td>{{ $supporting_document->title }}</td>
                                            <td>{{ $supporting_document->uploadedBy->name }}</td>
                                            <td>
                                                <a href="{{ url($supporting_document->file) }}" target="_blank">
                                                    <i class="fa fa-file"></i>
                                                </a>
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
    // function updateStatus(id)
    // {
    //     $('#updateStatusForm'+id).submit()
        
    // }

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

        // $("#type").on('change', function() {
        //     if($(this).val() == 'Align Policy')
        //     {
        //         $("#policySelectOption").removeAttr('hidden')
        //         $("[name='document[]']").prop('required', true)
        //     }
        //     else
        //     {
        //         $("#policySelectOption").prop('hidden', true)
        //         $("[name='document[]']").removeAttr('required')
        //     }
        // })

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