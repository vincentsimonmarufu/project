@extends('layouts.app')

@section('template_title')
    Import Jobcards
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
                    <h5>Job Cards</h5>
                    <span class="pcoded-mtext"> Import jobcards from excel</span>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="page-header-breadcrumb">
                <ul class="breadcrumb breadcrumb-title">
                    <li class="breadcrumb-item">
                        <a href=""
                        ><i class="feather icon-home"></i
                        ></a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ url('jobcards') }}">Job Cards</a>
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
                            <h4 style="margin-bottom:0">Import Job Cards</h4>
                            <span>Please download the template file <a href="{{ url('/jobcard-download') }}" data-toggle="tooltip" title="Download File" style="font-weight: bold;">HERE</a> for importation. Use plain text fo system to recognise the data.  </span>
                        </div>
                        <div class="card-block" style="padding-top: 7px;margin-top:0;">
                            <h4 class="sub-title"></h4>
                            <form method="POST" action="{{ url('/allocation-import-send') }}" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group row">
                                    <label for="allocation" class="col-sm-2 col-form-label"
                                        >Choose file : </label
                                    >
                                    <div class="col-sm-10">
                                        <input type="file" name="allocation" id="allocation" class="form-control">
                                    </div>
                                    @error('allocation')
                                        <span class="invalid-feedback" role="alert">
                                            <strong> {{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group row justify-content-end">
                                    <div class="col-md-2 mt-4">
                                        <button type="submit" class="btn btn-block btn-sm btn-success btn-round">Upload</button>
                                    </div>
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


@endsection
