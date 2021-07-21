@extends('layouts.app')

@section('template_title')
    Reject Request no: {{ $frequest->request }}
@endsection

@section('template_linked_css')
    <link rel="stylesheet" href="{{ asset('select2/css/select2.min.css') }}">
@endsection
@section('content')
<div class="page-header card">
    <div class="row align-items-end">
        @include('partials.form-status')
        <div class="col-lg-8">
            <div class="page-header-title">
                <div class="d-inline">
                    <h5>Food Requests</h5>
                    <span class="pcoded-mtext"> Add New</span>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="page-header-breadcrumb">
                <ul class="breadcrumb breadcrumb-title">
                    <li class="breadcrumb-item">
                        <a href="index.html"
                        ><i class="feather icon-home"></i
                        ></a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ url('frequests') }}">Food Requests</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ url('frequests/create') }}">Add New</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="pcoded-inner-content">
    <div class="main-body">
        <div class="page-wrapper">
            <div class="page-body">
                <div class="row">
                    <div class="col-sm-12">
                      <div class="card">
                        <div class="card-header pb-0">
                            <h4 style="font-size:18px;">Create new humber request</h4>

                            <p class="pt-2">Please note that if request type is extra humber , allocation is not considered.</p>
                        </div>
                        <div class="card-block" style="padding-top: 0;margin-top:0;">
                            <h4 class="sub-title"></h4>
                            <form method="POST" action="{{ route('frequests.store') }}">
                                @csrf
                                <div class="form-group row">
                                    <label for="department" class="col-sm-2 col-form-label"
                                        >Department : </label
                                    >
                                    <div class="col-sm-10">
                                        <input type="text" readonly name="department" id="department" class="form-control @error('department') is-invalid @enderror" placeholder="e.g Accounts" required="" />
                                    </div>
                                    @error('department')
                                        <span class="invalid-feedback" role="alert">
                                            <strong> {{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>


                                <div class="form-group row">
                                    <label for="name" class="col-sm-2 col-form-label"
                                        >Name : </label
                                    >
                                    <div class="col-sm-10">
                                        <input type="text" readonly name="name" id="username" class="form-control @error('name') is-invalid @enderror" placeholder="e.g Vincent" required="" />
                                    </div>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong> {{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group row">
                                    <label for="allocation" class="col-sm-2 col-form-label"
                                        >Allocation : </label
                                    >
                                    <div class="col-sm-10">
                                        <select name="allocation" id="allocation" class="form-control" style="width: 100%">
                                            <option value="">Please select allocation</option>
                                        </select>
                                    </div>
                                    @error('allocation')
                                        <span class="invalid-feedback" role="alert">
                                            <strong> {{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group row justify-content-end">
                                    <button class="btn waves-effect btn-round waves-light btn-sm mr-4 btn-primary">Send Request</button>
                                </div>
                            </form>
                        </div>
                      </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('footer_scripts')
<script src="{{ asset('select2/js/select2.min.js') }}"></script>
<script type="text/javascript">
    $('#paynumber').select2({
        placeholder:'select pay number'
    }).change(function(){
        var paynumber = $(this).val();
        var _token = $("input[name='_token']").val();
        if(paynumber){
            $.ajax({
                type:"get",
                url:"/getusername/"+paynumber,
                _token: _token ,
                success:function(res)
                {
                    if(res)
                    {
                        $("#username").empty();
                        $.each(res,function(key, value){

                            $("#username").val(value);
                        });

                    }
                }

            });

            $.ajax({
                type:"get",
                url:"/get-user-department/"+paynumber,
                _token: _token ,
                success:function(res)
                {
                    if(res)
                    {
                        $("#department").empty();
                        $.each(res,function(key, value){

                            $("#department").val(value);
                        });

                    }
                }

            });

            $.ajax({
                type:"get",
                url:"/get-allocation-request/"+paynumber,
                _token: _token ,
                success:function(res) {
                    if(res) {
                        $("#allocation").empty();
                        $.each(res,function(key, value){
                            $("#allocation").append('<option value="'+value+'">'+value+'</option>');
                        });
                    }
                }

            });
        }
    });

</script>
<script>
    $(document).ready(function() {
        $('#card_number').select2({
            placeholder:'select card number'
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#allocation').select2({
            placeholder:'Please select allocation'
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('#type').select2({
            placeholder:'Please select request type'
        });
    });
</script>
@endsection
