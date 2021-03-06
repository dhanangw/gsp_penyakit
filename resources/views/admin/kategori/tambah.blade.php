@extends('adminlte::layouts.app')

@section('htmlheader_title')
Tambah Kategori
@endsection

@section('contentheader_title')
Tambah Kategori
@endsection

@section('code-header')
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
@endsection

@section('main-content')
<style>
	.form-group label{
		text-align: left !important;
	}
</style>

	@foreach (['danger', 'warning', 'success', 'info'] as $msg)
	@if(Session::has('alert-' . $msg))
<div class="alert alert-{{ $msg }}">
	<p class="" style="border-radius: 0">{{ Session::get('alert-' . $msg) }}</p>
</div>
	{!!Session::forget('alert-' . $msg)!!}
	@endif
	@endforeach


<div class="row">
	<div class="col-md-12">
		<div class="">

			@if (count($errors) > 0)
			<div class="alert alert-danger">
				<ul>
					@foreach ($errors->all() as $error)
					<li>{{ $error }}</li>
					@endforeach
				</ul>
			</div>
			@endif
			<br>
			<form id="tambahBarang" method="post" action="{{url('admin/kategori/add')}}" enctype="multipart/form-data"  class="form-horizontal">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">

				<div class="form-group">
					<label for="no_index" class="col-sm-2 control-label">Nama Kategori</label>
					<div class="col-md-8">
						<input type="text" class="form-control input-lg" id="name" name="name" placeholder="Masukkan nama kategori"  required>
					</div>
				</div>

				<br>
				<div class="form-group">
	              <label for="jenis_kelamin" class="col-sm-2 control-label "> type</label>
	              <div class="col-md-8">
					<select class="form-control" name="type" id="type" required>
							<option value="">-- Pilih tipe kategori --</option>
							<option value="ranged">ranged</option>
							<option value="exact value">exact value</option>
					</select>
	              </div>
	            </div>

				<br>
				<div class="form-group text-center">
					<div class="col-md-8 col-md-offset-2">
					<button type="submit" class="btn btn-primary btn-lg">
							Confirm
						</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection

@section('code-footer')
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js "></script>
<script>
	$(document).ready(function () {
		$('.datepicker').each(function(){
			$(this).datepicker({
				dateFormat: 'yy-mm-dd' 
			});
		});  
   	});
</script>
@endsection

