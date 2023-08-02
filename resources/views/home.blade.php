@extends('layouts.header')

@section('content')

<div class="wrapper wrapper-content ">
   
    <div class="row ">
        {{-- <div class="col-lg-8 stretch-card">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Search Documents</h5>
                </div>
                <div class="ibox-content stretch-card">

                    <div class="search-form">
                        <form action="{{url('/home')}}" method="get">
                            <div class="input-group">
                                <input type="text" placeholder="Document Name/Code" name="search"  class="form-control input-lg" required>
                                <div class="input-group-btn">
                                    <button class="btn btn-lg btn-primary" type="submit">
                                        Search
                                    </button>
                                </div>
                            </div>

                        </form>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="search-result">
                        <h3><a href="#">Document 1 v2</a> <span class="label label-primary">Public</span></h3>
                        Last Updated by <a href="#" class="search-link">Employee 1</a>
                        <p>
                            Date Effective : January 1, 2023 <br>
                            Company : W Group Inc.
                            
                        </p>
                    </div>
                    <div class="hr-line-dashed"></div>
                    <div class="search-result">
                        <h3><a href="#">Document 2 v1</a> <span class="label label-danger">Private</span></h3>
                        Last Updated by <a href="#" class="search-link">Employee 3</a>
                        <p>
                            Date Effective : January 1, 2023 <br>
                            Company : W Group Inc.
                            
                        </p>
                    </div>
                    <div class="hr-line-dashed"></div>
                </div>
            </div>
        </div> --}}
        <div class="col-lg-8">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Documents Library</h5>
                </div>
                <div class="ibox-content">
                    <div>
                        <canvas id="barChart" height="140"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Public Documents (1)</h5>
                </div>
                <div class="ibox-content">
                    <table class="table table-striped table-bordered table-hover tables">
                        <thead>
                        <tr>
                            <th>Document</th>
                            <th>File</th>
                            <th>Uploaded By</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>Overtime Form</td>
                            <td><a href="#"><i class="fa fa-file"></i> File</a></td>
                            <td>Amelia</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Permits and licenses ({{count($permits)}})</h5>
                </div>
                <div class="ibox-content">
                    <table class="table table-striped table-bordered table-hover tables">
                        <thead>
                        <tr>
                            <th>Title</th>
                            <th>Company</th>
                            <th>Responsible</th>
                            <th>Expiration Date</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($permits as $permit)
                                <tr>
                                    <td><a href='{{url($permit->file)}}' target='_blank'>{{$permit->title}}</a></td>
                                    <td>{{$permit->company->name}}</td>
                                    <td>{{$permit->department->permit_account->name}}</td>
                                    <td ><span class="label label-danger">{{date('M d, Y',strtotime($permit->expiration_date))}}</span></td>
                                </tr>
                            @endforeach
                       
                      
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<script src="{{ asset('login_css/js/plugins/dataTables/datatables.min.js')}}"></script>
<script src="{{ asset('login_css/js/plugins/chosen/chosen.jquery.js') }}"></script>
<script src="{{ asset('login_css/js/plugins/chartJs/Chart.min.js') }}"></script>
<script>
    $(function () {
        var barData = {
        labels: ["January", "February", "March", "April", "May", "June", "July"],
        datasets: [
            {
                label: "Documents",
                backgroundColor: 'rgba(220, 220, 220, 0.5)',
                pointBorderColor: "#fff",
                data: [65, 59, 80, 81, 56, 55, 40]
            },
            {
                label: "Obsolete",
                backgroundColor: 'rgba(26,179,148,0.5)',
                borderColor: "rgba(26,179,148,0.7)",
                pointBackgroundColor: "rgba(26,179,148,1)",
                pointBorderColor: "#fff",
                data: [28, 48, 40, 19, 86, 27, 90]
            }
        ]
    };

    var barOptions = {
        responsive: true
    };


    var ctx2 = document.getElementById("barChart").getContext("2d");
    new Chart(ctx2, {type: 'bar', data: barData, options:barOptions});
    });
    $(document).ready(function(){
        

        $('.locations').chosen({width: "100%"});
        $('.tables').DataTable({
            pageLength: 10,
            responsive: true,
            dom: '<"html5buttons"B>lTfgitp',
            buttons: [
                
            ]

        });

    });

</script>
@endsection

