@extends('layouts.header')

@section('content')

<div class="wrapper wrapper-content ">
   
    <div class="row ">
        <div class="col-lg-8 stretch-card">
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
        </div>
      
    </div>
</div>

@endsection

@section('js')
<script src="{{ asset('login_css/js/plugins/dataTables/datatables.min.js')}}"></script>
<script>
   
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

