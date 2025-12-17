@extends('layouts.header')
@section('css')
<link href="{{ asset('login_css/css/plugins/c3/c3.min.css') }}" rel="stylesheet">
<link href="{{ asset('login_css/css/plugins/morris/morris-0.4.3.min.css') }}" rel="stylesheet">
<link href="{{ asset('login_css/css/plugins/chosen/bootstrap-chosen.css') }}" rel="stylesheet">
@endsection
@section('content')

<div class="wrapper wrapper-content ">
    <div class="row">
        <div class="col-lg-2">
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
        <div class="col-lg-2">
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
        <div class="col-lg-2">
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
        <div class="col-lg-2">
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
        <div class="col-lg-4">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <span class="label label-success pull-right">as of this Month ({{date('M. Y')}})</span>
                    <h5>WGI Policies</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins"><a href="javascript:void(0)" data-toggle="modal" data-target="#viewWGI">{{count($company_policies->where('company_id',1))}}</a></h1>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <span class="label label-success pull-right">as of this Month ({{date('M. Y')}})</span>
                    <h5>WHI Policies</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins"><a href="javascript:void(0)" data-toggle="modal" data-target="#viewWHI">{{count($company_policies->where('company_id',2))}}</a></h1>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <span class="label label-success pull-right">as of this Month ({{date('M. Y')}})</span>
                    <h5>WLI Policies</h5>
                </div>
                <div class="ibox-content">
                    <h1 class="no-margins"><a href="javascript:void(0)" data-toggle="modal" data-target="#viewWLI">{{count($company_policies->where('company_id',3))}}</a></h1>
                </div>
            </div>
        </div>
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
        @if(auth()->user()->role == "Department Head")
        <div class="col-lg-12">
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
        @endif
        @if(auth()->user()->role == "Administrator" || auth()->user()->role == "Business Process Manager" || auth()->user()->role == "Document Control Officer")
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>W Group Inc. Documents </h5>
                </div>
                <div class="ibox-content">
                    <div>
                        <div id="wgiGraph"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>W Hydrocoloids Inc. Documents </h5>
                </div>
                <div class="ibox-content">
                    <div>
                        <div id="whiGraph"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>W Landmark Inc. Documents </h5>
                </div>
                <div class="ibox-content">
                    <div>
                        <div id="wliGraph"></div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
  
    <div class='row'>
        @if((auth()->user()->role == "Administrator") || (auth()->user()->role == "Management Representative") || (auth()->user()->role == "Business Process Manager"))
    
            <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Requests</h5>
                    </div>
                    <div class="ibox-content">
                        <div id="morris-bar-chart"></div>
                    </div>
                </div>
            </div>
            
        @endif
        @php
            $wgiPermits = collect();
            $whiPermits = collect();
            $wliPermits = collect();
        @endphp
        @if(count($permits) != 0)
        @php
            $wgiPermits = $permits->whereIn('department_id', $wgi_departments_permit);
            $whiPermits = $permits->whereIn('department_id', $whi_departments_permit);
            $wliPermits = $permits->whereIn('department_id', $wli_departments_permit);
        @endphp
        
        <div class="col-lg-4">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>WGI Permits and licenses ({{count($wgiPermits)}}) </h5>
                   
                </div>
                <div class="ibox-content">
                    <div id="morris-donut-chart-wgi" ></div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>WLI Permits and licenses ({{count($wliPermits)}}) </h5>
                   
                </div>
                <div class="ibox-content">
                    <div id="morris-donut-chart-wli" ></div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>WHI Permits and licenses ({{count($whiPermits)}}) </h5>
                   
                </div>
                <div class="ibox-content">
                    <div id="morris-donut-chart-whi" ></div>
                </div>
            </div>
        </div>
        @endif
        @if((auth()->user()->role == "Administrator") || (auth()->user()->role == "Management Representative") || (auth()->user()->role == "Business Process Manager"))
    
        {{-- <div class="col-lg-4">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Document Requests Status this {{date('Y')}}</h5>
                </div>
                <div class="ibox-content">
                    <div>
                        <div id="pie"></div>
                    </div>
                </div>
            </div>
        </div> --}}
        <div class="row">
            <div class="col-lg-4">
                <div class="ibox">
                    <div class="ibox-title"><h5>WGI Requests {{ date('Y') }}</h5></div>
                    <div class="ibox-content"><div id="pie-wgi"></div></div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="ibox">
                    <div class="ibox-title"><h5>WLI Requests {{ date('Y') }}</h5></div>
                    <div class="ibox-content"><div id="pie-wli"></div></div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="ibox">
                    <div class="ibox-title"><h5>WHI Requests {{ date('Y') }}</h5></div>
                    <div class="ibox-content"><div id="pie-whi"></div></div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

@include('view_wgi_policies')
@include('view_whi_policies')
@include('view_wli_policies')
@endsection

@section('js')
<script src="{{ asset('login_css/js/plugins/dataTables/datatables.min.js')}}"></script>
<script src="{{ asset('login_css/js/plugins/chosen/chosen.jquery.js') }}"></script>
<script src="{{ asset('login_css/js/plugins/chartJs/Chart.min.js') }}"></script>

<script src="{{ asset('login_css/js/plugins/morris/raphael-2.1.0.min.js') }}"></script>
<script src="{{ asset('login_css/js/plugins/morris/morris.js') }}"></script>


<script src="{{ asset('login_css/js/plugins/d3/d3.min.js') }}"></script>
<script src="{{ asset('login_css/js/plugins/c3/c3.min.js') }}"></script>
<script>
    var departments = {!! json_encode(($departments)->toArray()) !!};
    var wgi_departments = {!! json_encode(($wgi_departments)->toArray()) !!};
    var whi_departments = {!! json_encode(($whi_departments)->toArray()) !!};
    var wli_departments = {!! json_encode(($wli_departments)->toArray()) !!};
    // var for_renewal = {!! json_encode((count($permits->where('expiration_date','!=',null)->where('expiration_date','<',date('Y-m-d', strtotime("+3 months", strtotime(date('Y-m-d')))))))) !!};
    // var over_due = {!! json_encode((count($permits->where('expiration_date','!=',null)->where('expiration_date','<',date('Y-m-d'))))) !!};
    // var active = {!! json_encode((count($permits->where('expiration_date','!=',null)->where('expiration_date','>=',date('Y-m-d', strtotime("+3 months", strtotime(date('Y-m-d')))))))) !!};
    // var no_expiration = {!! json_encode((count($permits->where('expiration_date','==',null)))) !!};
    var wgi_for_renewal = {!! count($wgiPermits->filter(function($p){ return $p->expiration_date != null && $p->expiration_date < date('Y-m-d', strtotime("+3 months")); })) !!};
    var wgi_over_due    = {!! count($wgiPermits->filter(function($p){ return $p->expiration_date != null && $p->expiration_date < date('Y-m-d'); })) !!};
    var wgi_active      = {!! count($wgiPermits->filter(function($p){ return $p->expiration_date != null && $p->expiration_date >= date('Y-m-d', strtotime("+3 months")); })) !!};
    var wgi_no_exp      = {!! count($wgiPermits->filter(function($p){ return $p->expiration_date == null; })) !!};

    var whi_for_renewal = {!! count($whiPermits->filter(function($p){ return $p->expiration_date != null && $p->expiration_date < date('Y-m-d', strtotime("+3 months")); })) !!};
    var whi_over_due    = {!! count($whiPermits->filter(function($p){ return $p->expiration_date != null && $p->expiration_date < date('Y-m-d'); })) !!};
    var whi_active      = {!! count($whiPermits->filter(function($p){ return $p->expiration_date != null && $p->expiration_date >= date('Y-m-d', strtotime("+3 months")); })) !!};
    var whi_no_exp      = {!! count($whiPermits->filter(function($p){ return $p->expiration_date == null; })) !!};

    var wli_for_renewal = {!! count($wliPermits->filter(function($p){ return $p->expiration_date != null && $p->expiration_date < date('Y-m-d', strtotime("+3 months")); })) !!};
    var wli_over_due    = {!! count($wliPermits->filter(function($p){ return $p->expiration_date != null && $p->expiration_date < date('Y-m-d'); })) !!};
    var wli_active      = {!! count($wliPermits->filter(function($p){ return $p->expiration_date != null && $p->expiration_date >= date('Y-m-d', strtotime("+3 months")); })) !!};
    var wli_no_exp      = {!! count($wliPermits->filter(function($p){ return $p->expiration_date == null; })) !!};

    var types = {!! json_encode(($categories->pluck('name'))->toArray()) !!};
    var obsoletes = {!! json_encode(($departments->pluck('obsoletes_count'))->toArray()) !!};
    var months = {!! json_encode(($months)) !!};

    var pending = {!!json_encode(($yearChangeRequests->where('status','Pending')->count()))!!}
    var approved = {!!json_encode(($yearChangeRequests->where('status','Approved')->count()))!!}
    var declined = {!!json_encode(($yearChangeRequests->where('status','Declined')->count()))!!}

    var wgi_pending = {!! json_encode($wgiChangeRequests->where('status','Pending')->count()) !!};
    var wgi_approved = {!! json_encode($wgiChangeRequests->where('status','Approved')->count()) !!};
    var wgi_declined = {!! json_encode($wgiChangeRequests->where('status','Declined')->count()) !!};

    var wli_pending = {!! json_encode($wliChangeRequests->where('status','Pending')->count()) !!};
    var wli_approved = {!! json_encode($wliChangeRequests->where('status','Approved')->count()) !!};
    var wli_declined = {!! json_encode($wliChangeRequests->where('status','Declined')->count()) !!};

    var whi_pending = {!! json_encode($whiChangeRequests->where('status','Pending')->count()) !!};
    var whi_approved = {!! json_encode($whiChangeRequests->where('status','Approved')->count()) !!};
    var whi_declined = {!! json_encode($whiChangeRequests->where('status','Declined')->count()) !!};

    $(function() {
        Morris.Donut({
            element: 'morris-donut-chart-wgi',
            data: [
                { label: "For Renewal", value: wgi_for_renewal-wgi_over_due },
                { label: "Overdue", value: wgi_over_due },
                { label: "Active", value: wgi_active },
                { label: "No Expiration", value: wgi_no_exp }
            ],
            resize: true,
            colors: ['#FFA500','#f44336', '#54cdb4','#1ab394'],
        });
        Morris.Donut({
            element: 'morris-donut-chart-whi',
            data: [
                { label: "For Renewal", value: whi_for_renewal-whi_over_due },
                { label: "Overdue", value: whi_over_due },
                { label: "Active", value: whi_active },
                { label: "No Expiration", value: whi_no_exp }
            ],
            resize: true,
            colors: ['#FFA500','#f44336', '#54cdb4','#1ab394'],
        });
        Morris.Donut({
            element: 'morris-donut-chart-wli',
            data: [
                { label: "For Renewal", value: wli_for_renewal-wli_over_due },
                { label: "Overdue", value: wli_over_due },
                { label: "Active", value: wli_active },
                { label: "No Expiration", value: wli_no_exp }
            ],
            resize: true,
            colors: ['#FFA500','#f44336', '#54cdb4','#1ab394'],
        });
        var aaa= months;
        Morris.Bar({
        element: 'morris-bar-chart',
        data: aaa,
        xkey: 'y',
        ykeys: ['a', 'b'],
        labels: ['Change Requests', 'Copy Requests'],
        hideHover: 'auto',
        resize: true,
        barColors: ['#1ab394', '#cacaca'],
    });
        
    });

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
    final_types = [types];
    console.log(columns);
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
        var types_names = {!! json_encode(($categories)->toArray()) !!};
        var colors ={};
        var column = ['x'];
        for(y=0;y<whi_departments.length;y++)
        {
            column.push(whi_departments[y].code+"("+whi_departments[y].documents_count+")");
        }
        var types = [];
        var columns= [column];
        for(i =0;i< types_names.length;i++)
        {
            type_column=[types_names[i].name];
            
            for(z=0;z<whi_departments.length;z++)
            {
                var doc = whi_departments[z].documents;
                
                var count = doc.filter(o => o.category === types_names[i].name);
                type_column.push(count.length)
            }
            
            columns.push(type_column);
            colors[types_names[i].code] = types_names[i].color;
            types.push(types_names[i].name);
        }
        final_types = [types];
        
        c3.generate({
            bindto: '#whiGraph',
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

        var types_names = {!! json_encode(($categories)->toArray()) !!};
        var colors ={};
        var column = ['x'];
        for(y=0;y<wgi_departments.length;y++)
        {
            column.push(wgi_departments[y].code+"("+wgi_departments[y].documents_count+")");
        }
        var types = [];
        var columns= [column];
        for(i =0;i< types_names.length;i++)
        {
            type_column=[types_names[i].name];
            
            for(z=0;z<wgi_departments.length;z++)
            {
                var doc = wgi_departments[z].documents;
                
                var count = doc.filter(o => o.category === types_names[i].name);
                type_column.push(count.length)
            }
            
            columns.push(type_column);
            colors[types_names[i].code] = types_names[i].color;
            types.push(types_names[i].name);
        }
        final_types = [types];
        
        c3.generate({
            bindto: '#wgiGraph',
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

        var types_names = {!! json_encode(($categories)->toArray()) !!};
        var colors ={};
        var column = ['x'];
        for(y=0;y<wli_departments.length;y++)
        {
            column.push(wli_departments[y].code+"("+wli_departments[y].documents_count+")");
        }
        var types = [];
        var columns= [column];
        for(i =0;i< types_names.length;i++)
        {
            type_column=[types_names[i].name];
            
            for(z=0;z<wli_departments.length;z++)
            {
                var doc = wli_departments[z].documents;
                
                var count = doc.filter(o => o.category === types_names[i].name);
                type_column.push(count.length)
            }
            
            columns.push(type_column);
            colors[types_names[i].code] = types_names[i].color;
            types.push(types_names[i].name);
        }
        final_types = [types];
        
        c3.generate({
            bindto: '#wliGraph',
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

            // c3.generate({
            //     bindto: '#pie',
            //     data:{
            //         columns: [
            //             ['Approved', approved],
            //             ['Declined', declined],
            //             ['Pending', pending]
            //         ],
            //         colors:{
            //             Approved: '#54cdb4',
            //             Declined: '#f44336',
            //             Pending: '#BABABA',
            //         },
            //         type : 'pie'
            //     }
            // });
            c3.generate({
                bindto: '#pie-wgi',
                data:{
                    columns: [
                        ['Approved', wgi_approved],
                        ['Declined', wgi_declined],
                        ['Pending', wgi_pending]
                    ],
                    colors:{ Approved:'#54cdb4', Declined:'#f44336', Pending:'#BABABA' },
                    type:'pie'
                }
            });

            c3.generate({
                bindto: '#pie-wli',
                data:{
                    columns: [
                        ['Approved', wli_approved],
                        ['Declined', wli_declined],
                        ['Pending', wli_pending]
                    ],
                    colors:{ Approved:'#54cdb4', Declined:'#f44336', Pending:'#BABABA' },
                    type:'pie'
                }
            });

            c3.generate({
                bindto: '#pie-whi',
                data:{
                    columns: [
                        ['Approved', whi_approved],
                        ['Declined', whi_declined],
                        ['Pending', whi_pending]
                    ],
                    colors:{ Approved:'#54cdb4', Declined:'#f44336', Pending:'#BABABA' },
                    type:'pie'
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

        $('.policiesTable').DataTable({
            // pageLength: 10,
            paginate:false,
            info:false,
            responsive: true,
            orderable:true,
            dom: '<"html5buttons"B>lTfgitp',
            columnDefs: [
                { "orderable": false, "targets": [0, 1] } // Columns 0 and 3 are not sortable
            ],
            buttons: [
                
            ]
        });
    });

    let returnModal = null;
    $(document).on('click', '.open-policy-modal', function () {
        returnModal = $(this).data('return-modal');
    });

    $('.policies-modal-new').on('hidden.bs.modal', function () {
        if (returnModal) {
            $('#' + returnModal).modal('show');
            returnModal = null;
        }
    });
  

</script>
@endsection

