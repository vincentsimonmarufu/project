@extends('layouts.app')

@section('template_title')
    Add new collection
@endsection

@section('template_linked_css')
    <link rel="stylesheet" href="{{ asset('select2/css/select2.min.css') }}">
    <style>
        fieldset.scheduler-border {
            border: 1px groove #ddd !important;
            padding: 0 1.4em 1.4em 1.4em !important;
            margin: 0 0 1.5em 0 !important;
            -webkit-box-shadow:  0px 0px 0px 0px #000;
                    box-shadow:  0px 0px 0px 0px #000;
        }

        legend.scheduler-border {
            font-size: 1em !important;
            font-weight: bold !important;
            text-align: left !important;
            width:auto;
            padding:0 10px;
            border-bottom:none;
        }

        .beneficiary-block{
            display: none;
            visibility: hidden;
        }

    </style>
@endsection

@section('content')

<div class="page-header card">
    <div class="row align-items-end">
        @include('partials.form-status')
        <div class="col-lg-8">
            <div class="page-header-title">
                <div class="d-inline">
                    <h5>Food Collections</h5>
                    <span class="pcoded-mtext"> Add food collection</span>
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
                        <a href="{{ url('fcollections') }}">Food Collections</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ url('fcollections/create') }}">Add New</a>
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
                            <h4 style="margin-bottom:0">Add Collection.</h4>
                            <span>Please complete the request details before proceeding feather with the application</span>
                        </div>
                            <div class="card-block tab-icon mt-0 pt-0 mb-0 pb-0">
                                <ul class="nav nav-tabs md-tabs" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" data-toggle="tab" href="#home7" role="tab"><i class="icofont icofont-home"></i>Collection and Approved Request Details</a>
                                        <div class="slide"></div>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" data-toggle="tab" href="#profile7" role="tab"><i class="icofont icofont-ui-user"></i>Access PIN</a>
                                        <div class="slide"></div>
                                    </li>
                                  </ul>

                                  <div class="tab-content card-block">
                                    <div class="tab-pane active" id="home7" role="tabpanel">
                                        <p class="m-0">
                                            <form action="" method="POST" class="needs-validation">
                                                @csrf
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <label for="paynumber">Employee Paynumber</label>
                                                        <select name="paynumber" id="paynumber" class="form-control" style="width: 100%;">
                                                            @if($requests)
                                                                @foreach ($requests as $request)
                                                                    <option value="{{ $request->paynumber }}">{{ $request->paynumber }}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <label for="jobcard">Job Card Number</label>
                                                        <select name="jobcard" id="jobcard" class="form-control" style="width:100%;">
                                                            @if($jobcards)
                                                                @foreach($jobcards as $jobcard)
                                                                    <option value="{{ $jobcard->id }}">{{ $jobcard->card_number }}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <label for="frequest">Request Number</label>
                                                        <input type="text" name="frequest" id="frequest" class="form-control" placeholder="REQ112">
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <label for="allocation">Allocation Month</label>
                                                        <input type="text" name="allocation" id="allocation" class="form-control" placeholder="244July2021">
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <label for="issue_date">Issue Date</label>
                                                        <input type="date" name="issue_date" id="issue_date" class="form-control" placeholder="02-07-21">
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <label for="issue_date">Select Collection</label> <br>
                                                        <div class="form-check form-check-inline col-lg-3">
                                                            <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1">
                                                            <label class="form-check-label" for="inlineRadio1">Self</label>
                                                        </div>
                                                        <div class="form-check form-check-inline col-lg-3">
                                                            <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2">
                                                            <label class="form-check-label" for="inlineRadio2">Other</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row ">
                                                    <div class="col-lg-12">
                                                        <fieldset class="scheduler-border">
                                                            <legend class="scheduler-border">Beneficiary Details</legend>
                                                            <div class="control-group">
                                                                <div class="row">
                                                                    <div class="col-lg-6">
                                                                        <label for="collected_by">Beneficiary Name</label>
                                                                        <select name="collected_by" id="collected_by" class="form-control" style="width: 100%;">
                                                                            <option value="">Please select employee beneficiary</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="col-lg-6">
                                                                        <label for="id_number">ID Number</label>
                                                                        <input type="text" name="id_number" id="id_number" class="form-control" placeholder="633027341W07">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </fieldset>
                                                    </div>
                                                </div>
                                                <div class="form-group row justify-content-end">
                                                    <div class="col-lg-2">
                                                        <a href="#profile7" class="btn waves-effect waves-light btn-round btn-block btn-success">Next  <i class="fa fa-arrow-right"></i></a>
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <Button class="btn waves-effect waves-light btn-round btn-block btn-success">Finish</Button>
                                                    </div>
                                                </div>
                                            </form>
                                        </p>
                                    </div>
                                    <div class="tab-pane" id="profile7" role="tabpanel">
                                      <p class="m-0">
                                        2.Cras consequat in enim ut efficitur.
                                        Nulla posuere elit quis auctor interdum
                                        praesent sit amet nulla vel enim amet.
                                        Donec convallis tellus neque, et
                                        imperdiet felis amet.
                                      </p>
                                    </div>
                                  </div>
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

<script>
    $('#paynumber').select2({
        placeholder:'Please select employee paynumber',
    });

    $('#jobcard').select2({
        placeholder: 'Please select active jobcard',
    });
    $('#collected_by').select2({
        placeholder: 'Please select employee beneficiary',
    });

</script>

@endsection
