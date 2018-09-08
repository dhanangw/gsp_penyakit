@extends('adminlte::layouts.app')

@section('htmlheader_title')
Tambah Data Pasien
@endsection

@section('contentheader_title')
Tambah Data Pasien
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
			<form id="tambahBarang" method="post" action="{{url('admin/add')}}" enctype="multipart/form-data"  class="form-horizontal">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">

				<div class="form-group">
					<label for="no_index" class="col-sm-2 control-label">Nomor Index</label>
					<div class="col-md-8">
						<input type="text" class="form-control input-lg" id="no_index" name="no_index" placeholder="Masukkan Nomor Index Pasien" @isset($pasien) value="{{$pasien->no_index}}" @endisset required>
					</div>
				</div>

				<div class="form-group">
					<label for="uptd" class="col-sm-2 control-label">UPTD</label>
					<div class="col-md-8">
						<input type="text" class="form-control input-lg" id="uptd" name="uptd" placeholder="Masukkan UPTD Pasien" @isset($pasien) value="{{$pasien->uptd}}" @endisset required>
					</div>
				</div>
				
				<div class="form-group">
					<label for="tanggal" class="col-sm-2 control-label">Tanggal</label>
					<div class="col-md-8">
						<input type="text" class="form-control input-lg datepicker" id="datepicker" name="tanggal" placeholder="Masukkan Tanggal Berobat Pasien" required>
					</div>
				</div>

				<div class="form-group">
					<label for="nama" class="col-sm-2 control-label">Nama</label>
					<div class="col-md-8">
						<input type="text" class="form-control input-lg" id="nama" name="nama" placeholder="Masukkan Nama Pasien" @isset($pasien) value="{{$pasien->nama}}" @endisset required>
					</div>
				</div>

				<div class="form-group">
					<label for="alamat" class="col-sm-2 control-label">Alamat</label>
					<div class="col-md-8">
						<input type="text" class="form-control input-lg" id="alamat" name="alamat" placeholder="Masukkan Alamat Pasien" @isset($pasien) value="{{$pasien->alamat}}" @endisset required>
					</div>
				</div>

				<div class="form-group">
					<label for="kota" class="col-sm-2 control-label">Kota</label>
					<div class="col-md-8">
						<input type="text" class="form-control input-lg" id="kota" name="kota" placeholder="Masukkan Kota Pasien" @isset($pasien) value="{{$pasien->kota}}" @endisset required>
					</div>
				</div>

				<div class="form-group">
					<label for="kecamatan" class="col-sm-2 control-label">Kecamatan</label>
					<div class="col-md-8">
						<input type="text" class="form-control input-lg" id="kecamatan" name="kecamatan" placeholder="Masukkan Kecamatan Pasien" @isset($pasien) value="{{$pasien->kecamatan}}" @endisset required>
					</div>
				</div>

				<div class="form-group">
					<label for="kelurahan" class="col-sm-2 control-label">Kelurahan</label>
					<div class="col-md-8">
						<input type="text" class="form-control input-lg" id="kelurahan" name="kelurahan" placeholder="Masukkan Kelurahan Barang" @isset($pasien) value="{{$pasien->kelurahan}}" @endisset required>
					</div>
				</div>

				<div class="form-group">
					<label for="nik" class="col-sm-2 control-label">NIK</label>
					<div class="col-md-8">
						<input type="text" class="form-control input-lg" id="nik" name="nik" placeholder="Masukkan NIK Barang" @isset($pasien) value="{{$pasien->nik}}" @endisset required>
					</div>
				</div>
				@foreach($kategori as $kategori)
				@php 
					$name = $kategori->name 
				@endphp
				@if($kategori->type == 'ranged')
				<div class="form-group">
					<label for="{{$kategori->name}}" class="col-sm-2 control-label">{{$kategori->name}}</label>
					<div class="col-md-8">
						<input type="text" class="form-control input-lg" id="{{$kategori->name}}" name="{{$kategori->name}}" placeholder="Masukkan {{$kategori->name}} Pasien" @isset($pasien) value="{{$pasien->$name}}" @endisset required>
					</div>
				</div>
				@else
				<div class="form-group">
	              <label for="{{$kategori->name}}" class="col-sm-2 control-label "> {{$kategori->name}}</label>
	              <div class="col-md-8">
					<select class="form-control" name="{{$kategori->name}}" id="{{$kategori->name}}" required>
							<option value="">-- Pilih {{$kategori->name}} --</option>
							@foreach($rentang as $rentang)
								@if($rentang->kategori_id === $kategori->id)
									<option @isset($pasien) {{$pasien->$name === $rentang->value ? 'selected' : ''}}  @endisset value="{{$rentang->value}}">{{$rentang->name}}</option>
								@endif
							@endforeach
					</select>
	              </div>
	            </div>
				@endif
				@endforeach
				<div class="form-group">
					<label for="tanggal" class="col-sm-2 control-label">Tanggal Lahir</label>
					<div class="col-md-8">
						<input type="text" class="form-control input-lg datepicker" id="tanggal_lahir" name="tanggal_lahir" placeholder="Masukkan Tanggal Lahir Pasien" @isset($pasien) value="{{$pasien->tanggal_lahir}}" @endisset required>
					</div>
				</div>

				<div class="form-group">
	              <label for="pendidikan" class="col-sm-2 control-label "> Pendidikan</label>
	              <div class="col-md-8">
	               <select class="form-control" name="pendidikan" required>
		            	<option value="">-- Pilih Pendidikan --</option>
		                <option value="Tidak/Belum Sekolah" @isset($pasien) {{$pasien->pendidikan === 'Tidak/Belum Sekolah' ? 'selected' : ''}}  @endisset >Tidak/Belum Sekolah</option>
		                <option value="Tamat SD/Sederajat" @isset($pasien) {{$pasien->pendidikan === 'Tamat SD/Sederajat' ? 'selected' : ''}}  @endisset>Tamat SD/Sederajat</option>
						<option value="SLTP/Sederajat" @isset($pasien) {{$pasien->pendidikan === 'SLTP/Sederajat' ? 'selected' : ''}}  @endisset>SLTA/Sederajat</option>
						<option value="SLTA/Sederajat" @isset($pasien) {{$pasien->pendidikan === 'SLTA/Sederajat' ? 'selected' : ''}}  @endisset>SLTA/Sederajat</option>
						<option @isset($pasien) {{$pasien->pendidikan === 'UNSPECIFIED' ? 'selected' : ''}}  @endisset value="UNSPECIFIED">UNSPECIFIED</option>
	              </select>
	              </div>
	            </div>

				<div class="form-group">
	              <label for="jenis_kelamin" class="col-sm-2 control-label "> Tipe Pasien</label>
	              <div class="col-md-8">
	               <select class="form-control" name="tipe_pasien" required>
		            	<option value="">-- Pilih Tipe Pasien --</option>
		                <option value="Umum Surabaya Gratis" @isset($pasien) {{$pasien->tipe_pasien === 'Umum Surabaya Gratis' ? 'selected' : ''}}  @endisset>Umum Surabaya Gratis</option>
		                <option value="Umum" @isset($pasien) {{$pasien->tipe_pasien === 'Umum' ? 'selected' : ''}}  @endisset>Umum</option>
						<option value="BPJS-Mandiri"  @isset($pasien) {{$pasien->tipe_pasien === 'BPJS-Mandiri' ? 'selected' : ''}}  @endisset>BPJS-Mandiri</option>
						<option value="BPJS-Jamkesmas" @isset($pasien) {{$pasien->tipe_pasien === 'BPJS-Jamkesmas' ? 'selected' : ''}}  @endisset>BPJS-Jamkesmas</option>
						<option value="BPJS-Askes"  @isset($pasien) {{$pasien->tipe_pasien === 'BPJS-Askes' ? 'selected' : ''}}  @endisset>BPJS-Askes</option>
						<option value="Non Kuota" @isset($pasien) {{$pasien->tipe_pasien === 'Non Kuota' ? 'selected' : ''}}  @endisset>Non Kuota</option>
						<option value="BPJS-Peserta Askes" @isset($pasien) {{$pasien->tipe_pasien === 'BPJS-Peserta Askes' ? 'selected' : ''}}  @endisset>BPJS-Peserta Askes</option>
	              </select>
	              </div>
	            </div>

				<div class="form-group">
					<label for="keluhan" class="col-sm-2 control-label">Keluhan</label>
					<div class="col-md-8">
						<input type="text" class="form-control input-lg" id="keluhan" name="keluhan" placeholder="Masukkan Keluhan Pasien" required>
					</div>
				</div>

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

