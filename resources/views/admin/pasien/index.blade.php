@extends('adminlte::layouts.app')

@section('code-header')

@endsection

@section('htmlheader_title')
Manajemen Pasien
@endsection

@section('contentheader_title')
Pasien
@endsection

@section('main-content')
<br>
<!-- include summernote css/js-->
<div class="flash-message" style="margin-left: -16px;margin-right: -16px; margin-top: 13px;">
  @foreach (['danger', 'warning', 'success', 'info'] as $msg)
  @if(Session::has('alert-' . $msg))
<div class="alert alert-{{ $msg }}">
  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
  <p class="" style="border-radius: 0">{{ Session::get('alert-' . $msg) }}</p>
</div>
  {!!Session::forget('alert-' . $msg)!!}
  @endif
  @endforeach
</div>
<div style="margin-bottom: 10px">
  <a href="{{url('admin/create')}}" type="button" class="btn btn-info btn-md" >
    <i class="fa fa-plus-square"></i> Tambah Data Pasien Baru</a>
</div>
<div style="overflow: auto">
<table id="myTable" class="table table-striped table-bordered" cellspacing="0">
  <thead>
    <tr>
      <th>No.</th>
      <th style="text-align:center">UPTD</th>
      <th style="text-align:center">Tanggal</th>
      <th style="text-align:center">Nama</th>
      <th style="text-align:center">Alamat</th>
      <th style="text-align:center">Kota</th>
      <th style="text-align:center">Kecamatan</th>
      <th style="text-align:center">Kelurahan</th>
      <th style="text-align:center">NIK</th>
      <th style="text-align:center">Umur</th>
      <th style="text-align:center">Tanggal Lahir</th>
      <th style="text-align:center">Jenis Kelamin</th>
      <th style="text-align:center">Pendidikan</th>
      <th style="text-align:center">Tipe Pasien</th>
      <th style="text-align:center">Keluhan</th>
      <th style="text-align:center">Action</th>
    </tr> 
  </thead>
  <tbody>
   <?php $number = 1 ?>
   @forelse($pasien as $key => $p) 
    <tr>
      <td width="5%" style="text-align:center">{{$key+1}}</td>
      <td width="5%" style="text-align:center">{{$p->uptd}}</td>
      <td width="5%">{{$p->tanggal}}</td>
      <td width="5%" style="text-align:center">{{$p->nama}}</td>
      <td width="5%" style="text-align:center">{{$p->alamat}}</td>
      <td width="5%" style="text-align:center">{{$p->kota}}</td>
      <td width="5%" style="text-align:center">{{$p->kecamatan}}</td>
      <td width="5%" style="text-align:center">{{$p->kelurahan}}</td>
      <td width="5%" style="text-align:center">{{$p->nik}}</td>
      <td width="5%" style="text-align:center">{{$p->umur}}</td>
      <td width="5%" style="text-align:center">{{$p->tanggal_lahir}}</td>
      <td width="5%" style="text-align:center">{{$p->jenis_kelamin}}</td>
      <td width="5%" style="text-align:center">{{$p->pendidikan}}</td>
      <td width="5%" style="text-align:center">{{$p->tipe_pasien}}</td>
      <td width="5%" style="text-align:center">{{$p->keluhan}}</td>
      <td style="text-align:center" >
        <a onclick="return confirm('Anda yakin untuk menghapus data ini?');" href="{{url('admin/'.$p->id_pasien.'/delete')}}" class="btn btn-danger btn-xs">
        <i class="fa fa-trash-o"></i> Hapus</a>
        <a href="{{url('admin/'.$p->id_pasien.'/edit/')}}" class="btn btn-warning btn-xs">
        <i class="fa fa-pencil-square-o"></i> Edit</a>
        <a href="{{url('admin/'.$p->id_pasien.'/tambah/')}}" class="btn btn-info btn-xs">
        <i class="fa fa-plus-square"></i> Tambahkan data pasien ini</a>
        </td>
    @empty
      <td colspan="17"><center>Belum ada pasien</center></td>
    @endforelse
    </tr>
  </tbody>
</table>
<br>
</div>


@endsection

@section('code-footer')
<script src="//code.jquery.com/jquery-1.12.3.js"></script>
<script src="//cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.12/js/dataTables.bootstrap.min.js"></script>
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css">
<script>
  $(document).ready(function() {
  $('#myTable').DataTable();
  } 
  );
</script>
@endsection