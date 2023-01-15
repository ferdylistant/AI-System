@extends('layouts.app')

@section('cssRequired')
<link rel="stylesheet" href="{{url('vendors/izitoast/dist/css/iziToast.min.css')}}">
<link rel="stylesheet" href="{{url('vendors/select2/dist/css/select2.min.css')}}">
<link rel="stylesheet" href="{{url('vendors/bootstrap-datepicker/dist/css/bootstrap-datepicker.standalone.css')}}">
@endsection

@section('cssNeeded')
<style>

</style>
@endsection

@section('content')
<section class="section">
    <div class="section-header">
        <div class="section-header-back">
            <button class="btn btn-icon" onclick="history.back()"><i class="fas fa-arrow-left"></i></button>
        </div>
        <h1>Edit Platform Digital</h1>
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card card-success">
                    <form id="fup_PlatformDigital">
                        <div class="card-header">
                            <h4>Form Edit</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-12 col-md-12 mb-4">
                                    <label>Nama Platform: <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text"><i class="fas fa-globe"></i></div>
                                        </div>
                                        <input type="hidden" name="id" value="{{$data->id}}">
                                        <input type="text" class="form-control" name="up_nama" value="{{$data->nama}}" placeholder="Input here..." required>
                                        <div id="err_up_nama"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <button type="submit" class="btn btn-success">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('jsRequired')
<script src="{{url('vendors/jquery-validation/dist/jquery.validate.min.js')}}"></script>
<script src="{{url('vendors/jquery-validation/dist/additional-methods.min.js')}}"></script>
<script src="{{url('vendors/select2/dist/js/select2.full.min.js')}}"></script>
<script src="{{url('vendors/bootstrap-datepicker/dist/js/bootstrap-datepicker.js')}}"></script>
<script src="{{url('vendors/sweetalert/dist/sweetalert.min.js')}}"></script>
<script src="{{url('vendors/izitoast/dist/js/iziToast.min.js')}}"></script>
@endsection


@section('jsNeeded')
<script src="{{url('js/edit_platformdigital.js')}}"></script>
@endsection
