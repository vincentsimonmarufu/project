@extends('layouts.app')

@section('template_title')
    User Collection Report
@endsection

@section('template_linked_css')
    <link rel="stylesheet" type="text/css" href="{{ asset('dash_resource/css/datatables.bootstrap4.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('dash_resource/css/buttons.datatables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('select2/css/select2.min.css') }}">
@endsection
@section('content')

    <div class="page-header card">
        <div class="row align-items-end">
            @include('partials.form-status')
            <div class="col-lg-8">
                <div class="page-header-title">
                    <div class="d-inline">
                        <h5>Food Collection Report</h5>
                        <span class="pcoded-mtext"> Generate Report using Pay Number</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="page-header-breadcrumb">
                    <ul class="breadcrumb breadcrumb-title">
                        <li class="breadcrumb-item">
                            <a href="{{ url('home') }}"
                            ><i class="feather icon-home"></i
                                ></a>
                        </li>

                        <li class="breadcrumb-item">
                            <a href="{{ url('user-report') }}">User Report</a>
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
                                    <h4 style="margin-bottom:0">User Report</h4>
                                    <span>Please select pay number for report generation e.g APPS244</span>
                                </div>
                                <div class="card-block" style="padding-top: 7px;margin-top:0;">
                                    <h4 class="sub-title"></h4>
                                    <form method="POST" action="{{ url('/user-report-post') }}" enctype="multipart/form-data">
                                        @csrf
                                        <div class="form-group row">
                                            <label for="paynumber" class="col-sm-3 col-form-label"
                                            >Pay Number : </label
                                            >
                                            <div class="col-sm-7">
                                                <select name="paynumber" class="form-control" id="paynumber" style="width: 100%;" required="" autofocus>
                                                    <option value="">Select pay number</option>
                                                    @if ($users)
                                                        @foreach ($users as $user)
                                                            <option value="{{ $user->paynumber }}">{{ $user->paynumber }} - {{ $user->first_name }} {{ $user->last_name }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            @error('paynumber')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong> {{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <div class="col-sm-2">
                                                <button class="btn waves-effect btn-round waves-light btn-block btn-sm btn-success">Search</button>
                                            </div>
                                        </div>

                                    </form>

                                    {{-- collections content comes here  --}}
                                    @if (isset($user_collections))
                                        <div class="dt-responsive table-responsive mt-3">
                                            <table
                                                id="basic-btn"
                                                class="table table-bordered nowrap"
                                            >
                                                <thead>
                                                <tr>
                                                    <th>Folio</th>
                                                    <th>Deparment</th>
                                                    <th>Number</th>
                                                    <th>Name</th>
                                                    <th>Jobcard No:</th>
                                                    <th>Issue Date</th>
                                                    <th>Month</th>
                                                    <th>Collected By</th>
                                                    <th>ID Number</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @if ($user_collections)
                                                    @foreach ($user_collections as $collection )
                                                        <tr>
                                                            <td>{{ $collection->id }}</td>
                                                            <td>{{ $collection->department }}</td>
                                                            <td>{{ $collection->paynumber }}</td>
                                                            <td>{{ $collection->name }}</td>
                                                            <td>{{ $collection->card_number }}</td>
                                                            <td>{{ $collection->issue_date }}</td>
                                                            <td>{{ $collection->allocation }}</td>
                                                            <td>{{ $collection->collected_by }}</td>
                                                            <td>{{ $collection->id_number }}</td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                                </tbody>
                                            </table>
                                        </div>
                                    @endif
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
    <script src="{{ asset('dash_resource/js/jquery.datatables.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('dash_resource/js/datatables.buttons.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('dash_resource/js/jszip.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('dash_resource/js/pdfmake.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('dash_resource/js/vfs_fonts.js') }}" type="text/javascript"></script>
    <script src="{{ asset('dash_resource/js/vfs_fonts-2.js') }}" type="text/javascript"></script>
    <script src="{{ asset('dash_resource/js/buttons.colvis.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('dash_resource/js/buttons.print.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('dash_resource/js/buttons.html5.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('dash_resource/js/datatables.bootstrap4.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('dash_resource/js/datatables.responsive.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('dash_resource/js/extension-btns-custom.js') }}" type="text/javascript"></script>

    <script src="{{ asset('select2/js/select2.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('#paynumber').select2({
                placeholder:'Please select pay number.',
            });
        });
    </script>

@endsection
