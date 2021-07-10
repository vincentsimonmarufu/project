@extends('layouts.app')

@section('template_title')
    Showing all beneficiaries
@endsection

@section('template_linked_css')
<link rel="stylesheet" type="text/css" href="{{ asset('dash_resource/css/datatables.bootstrap4.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('dash_resource/css/buttons.datatables.min.css') }}">
@endsection

@section('content')

<div class="page-header card">
    <div class="row align-items-end">
        @include('partials.form-status')
        <div class="col-lg-8">
            <div class="page-header-title">
                <div class="d-inline">
                    <h5>Beneficiaries</h5>
                    <span class="pcoded-mtext">Overview of Employee beneficiaries</span>
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
                        <a href="{{ url('beneficiaries') }}">Beneficiaries</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ url('beneficiaries/create') }}">Add New</a>
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
                        <div class="card-block">
                          <div class="dt-responsive table-responsive">
                            <table
                              id="basic-btn"
                              class="table table-bordered nowrap"
                            >
                              <thead>
                                <tr>
                                  <th>Id</th>
                                  <th>Employee Paynumber</th>
                                  <th>Full Name</th>
                                  <th>ID Number</th>
                                  <th>Mobile Number</th>
                                  <th>Action</th>
                                </tr>
                              </thead>
                              <tbody>
                                @if ($beneficiaries)
                                    @foreach ($beneficiaries as $beneficiary )
                                        <tr>
                                            <td>{{ $beneficiary->id }}</td>
                                            <td>{{ $beneficiary->user->paynumber }}</td>
                                            <td>{{ $beneficiary->first_name }}</td>
                                            <td>{{ $beneficiary->id_number }}</td>
                                            <td>{{ $beneficiary->mobile_number }}</td>
                                            <td style="white-space: nowrap;width:20%;">
                                                <a href="{{ route('beneficiaries.edit',$beneficiary->id) }}" data-toggle="tooltip" title="Edit Department" class="d-inline btn btn-sm btn-primary"><i class="fa fa-pencil"></i></a>
                                                <button type="button" class="d-inline btn-sm btn btn-success"><i class="fa fa-eye"></i></button>
                                                <form method="POST" action="{{ route('beneficiaries.destroy',$beneficiary->id) }}" role="form" class="d-inline">
                                                    @csrf
                                                    @method("DELETE")
                                                    <button type="submit" class="d-inline btn-sm btn btn-danger" data-toggle="tooltip" title="Delete Department"><i class="fa fa-trash-o"></i></button>
                                                </form>

                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                              </tbody>
                            </table>
                          </div>
                        </div>
                      </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('departments.show')


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

<script>
    $('#showJobcard').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget) // Button that triggered the modal
        var department = button.data('department')
        var manager = button.data('manager')
        var modal = $(this)
        modal.find('.modal-title').text('Show : ' + department + ' department')
        modal.find('.department').text(department)
        modal.find('.manager').text(manager)
    })
</script>
@endsection
