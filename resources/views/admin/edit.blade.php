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
			<form id="tambahBarang" method="post" action="{{url('/editpost')}}" enctype="multipart/form-data"  class="form-horizontal">
				<input type="hidden" name="_token" value="{{ csrf_token() }}">
				<input type="hidden" name="id_pasien" value="{{ $data->id_pasien }}">
				<div class="form-group">
					<label for="no_index" class="col-sm-2 control-label">Nomor Index</label>
					<div class="col-md-8">
						<input type="text" class="form-control input-lg" id="no_index" name="no_index" placeholder="Masukkan Nomor Index Pasien" value="{{$data->no_index}}" required>
					</div>
				</div>

				<div class="form-group">
					<label for="uptd" class="col-sm-2 control-label">UPTD</label>
					<div class="col-md-8">
						<input type="text" class="form-control input-lg" id="uptd" name="uptd" placeholder="Masukkan UPTD Pasien" value="{{$data->uptd}}" required>
					</div>
				</div>
				
				<div class="form-group">
					<label for="tanggal" class="col-sm-2 control-label">Tanggal</label>
					<div class="col-md-8">
						<input type="text" class="form-control input-lg datepicker" id="datepicker" name="tanggal" placeholder="Masukkan Tanggal Berobat Pasien" value="{{$data->tanggal}}" required>
					</div>
				</div>

				<div class="form-group">
					<label for="nama" class="col-sm-2 control-label">Nama</label>
					<div class="col-md-8">
						<input type="text" class="form-control input-lg" id="nama" name="nama" placeholder="Masukkan Nama Pasien" value="{{$data->nama}}" required>
					</div>
				</div>

				<div class="form-group">
					<label for="alamat" class="col-sm-2 control-label">Alamat</label>
					<div class="col-md-8">
						<input type="text" class="form-control input-lg" id="alamat" name="alamat" placeholder="Masukkan Alamat Pasien" value="{{$data->alamat}}" required>
					</div>
				</div>

				<div class="form-group">
					<label for="kota" class="col-sm-2 control-label">Kota</label>
					<div class="col-md-8">
						<input type="text" class="form-control input-lg" id="kota" name="kota" placeholder="Masukkan Kota Pasien" value="{{$data->kota}}" required>
					</div>
				</div>

				<div class="form-group">
					<label for="kecamatan" class="col-sm-2 control-label">Kecamatan</label>
					<div class="col-md-8">
						<input type="text" class="form-control input-lg" id="kecamatan" name="kecamatan" placeholder="Masukkan Kecamatan Pasien" value="{{$data->kecamatan}}" required>
					</div>
				</div>

				<div class="form-group">
					<label for="kelurahan" class="col-sm-2 control-label">Kelurahan</label>
					<div class="col-md-8">
						<input type="text" class="form-control input-lg" id="kelurahan" name="kelurahan" placeholder="Masukkan Kelurahan Barang" value="{{$data->kelurahan}}" required>
					</div>
				</div>

				<div class="form-group">
					<label for="nik" class="col-sm-2 control-label">NIK</label>
					<div class="col-md-8">
						<input type="text" class="form-control input-lg" id="nik" name="nik" placeholder="Masukkan NIK Barang" value="{{$data->nik}}" required>
					</div>
				</div>

				<div class="form-group">
					<label for="umur" class="col-sm-2 control-label">Umur</label>
					<div class="col-md-8">
						<input type="text" class="form-control input-lg" id="umur" name="umur" placeholder="Masukkan Umur Pasien" value="{{$data->umur}}" required>
					</div>
				</div>

				<div class="form-group">
					<label for="tanggal" class="col-sm-2 control-label">Tanggal Lahir</label>
					<div class="col-md-8">
						<input type="text" class="form-control input-lg datepicker" id="tanggal_lahir" name="tanggal_lahir" placeholder="Masukkan Tanggal Lahir Pasien" value="{{$data->tanggal_lahir}}" required>
					</div>
				</div>

				<div class="form-group">
	              <label for="jenis_kelamin" class="col-sm-2 control-label "> Jenis Kelamin</label>
	              <div class="col-md-8">
					<select class="form-control" name="jenis_kelamin" id="jenis_kelamin" required>
							<option value="">-- Pilih Jenis Kelamin --</option>
							<option value="L" {{($data->jenis_kelamin == 'L') ? 'selected' : '' }}>Laki-laki</option>
							<option value="P" {{($data->jenis_kelamin == 'P') ? 'selected' : '' }}>Perempuan</option>
					</select>
	              </div>
	            </div>

				<div class="form-group">
	              <label for="pendidikan" class="col-sm-2 control-label "> Pendidikan</label>
	              <div class="col-md-8">
	               <select class="form-control" name="pendidikan" required>
		            	<option value="">-- Pilih Pendidikan --</option>
		                <option value="Tidak/Belum Sekolah" {{($data->pendidikan == 'Tidak/Belum Sekolah') ? 'selected' : '' }}>Tidak/Belum Sekolah</option>
		                <option value="Tamat SD/Sederajat" {{($data->pendidikan == 'Tamat SD/Sederajat') ? 'selected' : '' }}>Tamat SD/Sederajat</option>
						<option value="SLTP/Sederajat" {{($data->pendidikan == 'SLTP/Sederajat') ? 'selected' : '' }}>SLTA/Sederajat</option>
						<option value="SLTA/Sederajat" {{($data->pendidikan == 'SLTA/Sederajat') ? 'selected' : '' }}>SLTA/Sederajat</option>
						<option value="UNSPECIFIED" {{($data->pendidikan == 'UNSPECIFIED') ? 'selected' : '' }}>UNSPECIFIED</option>
	              </select>
	              </div>
	            </div>


				<div class="form-group">
	              <label for="jenis_kelamin" class="col-sm-2 control-label "> Tipe Pasien</label>
	              <div class="col-md-8">
	               <select class="form-control" name="tipe_pasien" required>
		            	<option value="">-- Pilih Tipe Pasien --</option>
		                <option value="Umum Surabaya Gratis" {{($data->tipe_pasien == 'Umum Surabaya Gratis') ? 'selected' : '' }}>Umum Surabaya Gratis</option>
		                <option value="Umum" {{($data->tipe_pasien == 'Umum') ? 'selected' : '' }}>Umum</option>
						<option value="BPJS-Mandiri" {{($data->tipe_pasien == 'BPJS-Mandiri') ? 'selected' : '' }}>BPJS-Mandiri</option>
						<option value="BPJS-Jamkesmas" {{($data->tipe_pasien == 'BPJS-Jamkesmas') ? 'selected' : '' }}>BPJS-Jamkesmas</option>
						<option value="BPJS-Askes" {{($data->tipe_pasien == 'BPJS-Askes') ? 'selected' : '' }}>BPJS-Askes</option>
						<option value="Non Kuota" {{($data->tipe_pasien == 'Non Kuota') ? 'selected' : '' }}>Non Kuota</option>
						<option value="BPJS-Peserta Askes" {{($data->tipe_pasien == 'BPJS-Peserta Askes') ? 'selected' : '' }}>BPJS-Peserta Askes</option>
	              </select>
	              </div>
	            </div>

				<div class="form-group">
					<label for="keluhan" class="col-sm-2 control-label">Keluhan</label>
					<div class="col-md-8">
						<input type="text" class="form-control input-lg" id="keluhan" name="keluhan" placeholder="Masukkan Keluhan Pasien" value="{{$data->keluhan}}" required>
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
	$('.datepicker').each(function(){
		$(this).datepicker();
	});

	$(document).ready(function () {
      $('#no_index').autocomplete({
		source:'{!!url('ajax')!!}',
		minlength:2,
        autoFocus:true,
		// source:"{{ URL::to('autocomplete')}}",
		
		select: function (event, ui) {
			var item = ui.item;
			if(item) {
				$("#uptd").val(item.uptd);
				$("#nama").val(item.nama);
				$("#alamat").val(item.alamat);
				$("#kota").val(item.kota);
				$("#kecamatan").val(item.kecamatan);
				$("#kelurahan").val(item.kelurahan);
				$("#nik").val(item.nik);
				$("#tanggal_lahir").val(item.tanggal_lahir);
				$("#jenis_kelamin").val(item.jenis_kelamin);
			}
		}
      });
   });
</script>
@endsection

