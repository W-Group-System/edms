@extends('layouts.header')
@section('css')
<link href="{{ asset('login_css/css/plugins/c3/c3.min.css') }}" rel="stylesheet">
@endsection
@section('content')

<div class="wrapper wrapper-content ">
    <div class="row">
        <div class="col-lg-3">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <span class="label label-success pull-right">as of Today</span>
                    <h5>Total Documents</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{(count($documents))}}</h1>
                    {{-- <div class="stat-percent font-bold text-success">98% <i class="fa fa-bolt"></i></div> --}}
                    <small>&nbsp;</small>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <span class="label label-success pull-right">as of Today</span>
                    <h5>New Requests</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{count($change_requests->where('created_at','>=',date('Y-m-d')))+count($copy_requests->where('created_at','>=',date('Y-m-d')))}}</h1>
                    {{-- <div class="stat-percent font-bold text-success">98% <i class="fa fa-bolt"></i></div> --}}
                    <small>&nbsp;</small>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <span class="label label-success pull-right">as of Today</span>
                    <h5>Pending</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{count($change_requests->where('status','Pending'))+count($copy_requests->where('status','Pending'))}}</h1>
                    {{-- <div class="stat-percent font-bold text-success">98% <i class="fa fa-bolt"></i></div> --}}
                    <small>&nbsp;</small>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <span class="label label-success pull-right">as of this Month ({{date('M. Y')}})</span>
                    <h5>Approved</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins">{{count($change_requests->where('status','Approved')) + count($copy_requests->where('status','Approved'))}}</h1>
                    {{-- <div class="stat-percent font-bold text-success">98% <i class="fa fa-bolt"></i></div> --}}
                    <small>&nbsp;</small>
                </div>
            </div>
        </div>
    </div>
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
                    <h5>Documents Library </h5>

                </div>
                <div class="ibox-content">
                    <div>
                        <div id="stocked"></div>
                    </div>
                </div>
            </div>
        </div>
        @if((auth()->user()->role == 'Administrator') || (auth()->user()->role == 'Document Control Officer') || (auth()->user()->role == 'Business Process Manager') || (auth()->user()->role == 'Management Representative') || (count(auth()->user()->permits) !=0 ))
        <div class="col-lg-4">
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
                                    <td><small>@foreach($permit->department->permit_accounts as $accountable)
                                        {{$accountable->user->name}} <hr>
                                    @endforeach</small></td>
                                    <td ><span class="label label-danger">{{date('M d, Y',strtotime($permit->expiration_date))}}</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

@endsection

@section('js')
<script src="{{ asset('login_css/js/plugins/dataTables/datatables.min.js')}}"></script>
<script src="{{ asset('login_css/js/plugins/chosen/chosen.jquery.js') }}"></script>
<script src="{{ asset('login_css/js/plugins/chartJs/Chart.min.js') }}"></script>

<script src="{{ asset('login_css/js/plugins/d3/d3.min.js') }}"></script>
<script src="{{ asset('login_css/js/plugins/c3/c3.min.js') }}"></script>
<script>
    var departments = {!! json_encode(($departments)->toArray()) !!};
    var types = {!! json_encode(($categories->pluck('name'))->toArray()) !!};
    var obsoletes = {!! json_encode(($departments->pluck('obsoletes_count'))->toArray()) !!};

    $(document).ready(function(){
        var types_names = {!! json_encode(($categories)->toArray()) !!};
        var colors ={};
        var column = ['x'];
 
    for(y=0;y<departments.length;y++)
    {
        column.push(departments[y].code+"("+departments[y].documents_count+")");
    }
    var types = [];
    var  columns= [column];
    for(i =0;i< types_names.length;i++)
    {
        
        type_column=[types_names[i].code];
        for(z=0;z<departments.length;z++)
        {
            var doc = departments[z].documents;
            var count = doc.filter(o => o.category === types_names[i].name);
            type_column.push(count.length)
           
        }
        
        columns.push(type_column);
        colors[types_names[i].code] = types_names[i].color;
        types.push(types_names[i].code);
    }
    console.log(columns);
    final_types = [types];
        c3.generate({
                bindto: '#stocked',
                data:{
                    x : 'x',
                    columns: columns,
                    colors:colors,
                    type: 'bar',
                    groups: final_types,
                   
                },
                axis: {
                    x: {
                        show: true,
                        type: 'categorized', // this is needed to load string x value
                    },
                    y2: {
                        show: true,
                        label: 'Counts'
                    },
                    y: {
                        show: true,
                        label: 'Counts'
                    },
                }
            });
        

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

