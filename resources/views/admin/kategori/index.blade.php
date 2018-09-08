@extends('adminlte::layouts.app')

@section('code-header')

@endsection

@section('htmlheader_title')
Kategori
@endsection

@section('contentheader_title')
Kategori
@endsection

@section('main-content')
 <script src="https://code.jquery.com/jquery-3.3.1.js"></script> 
 <script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript">
    $(document).ready( function () {
    $('#myTable').DataTable();
});
</script>
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
  <a href="{{url('admin/kategori/create')}}" type="button" class="btn btn-info btn-md" >
    <i class="fa fa-plus-square"></i> Tambah Kategori Baru</a>
</div>
<div style="overflow: auto">
<table id="myTable" class="table table-striped table-bordered" cellspacing="0">
  <thead>
    <tr>
      <th>No.</th>
      <th style="text-align:center">Nama Kategori</th>
      <th style="text-align:center">Action</th>
    </tr> 
  </thead>
  <tbody>
   <?php $number = 1 ?>
   @forelse($kategori as $key => $p) 
    <tr>
      <td width="10%" style="text-align:center">{{$key+1}}</td>
      <td width="45%" style="text-align:center">{{$p->name}}</td>
      <td width="45%" style="text-align:center" >
        <a onclick="return confirm('Anda yakin untuk menghapus data ini?');" href="{{url('admin/kategori/'.$p->id.'/delete')}}" class="btn btn-danger btn-xs">
        <i class="fa fa-trash-o"></i> Hapus</a>
        <a href="{{url('admin/kategori/'.$p->id.'/edit/')}}" class="btn btn-warning btn-xs">
        <i class="fa fa-pencil-square-o"></i> Edit</a>
        <a href="{{url('admin/rentang/'.$p->id.'/index/')}}" class="btn btn-info btn-xs">
        <i class="fa fa-plus-square"></i> Manage Rentang Kategori</a>
        </td>
    @empty
      <td colspan="17"><center>Belum ada Kategori</center></td>
    @endforelse
    </tr>
  </tbody>
</table>
<br>
</div>


@endsection

@section('code-footer')
@endsection