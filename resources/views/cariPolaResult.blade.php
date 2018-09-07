@extends('adminlte::layouts.app')

@section('code-header')

@endsection

@section('htmlheader_title')
Pola Pasien
@endsection

@section('contentheader_title')
Pola Pasien
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
  Sequence 1
</div>
<div style="overflow: auto">
<table id="myTable" class="table table-striped table-bordered" cellspacing="0">
  <thead>
    <tr>
      <th style="text-align:center">Nomor</th>
      <th style="text-align:center">item</th>
      <th style="text-align:center">count</th>
      <th style="text-align:center">support</th>
      <th style="text-align:center">confidence</th>
    </tr> 
  </thead>
  <tbody>
   <?php $number = 1 ?>
   @forelse($sequence1 as $key => $value) 
    <tr>
      <td width="5%" style="text-align:center">{{$number}}</td>
      <td width="5%" style="text-align:center">{{strtoupper($key)}}</td>
      <td width="5%" style="text-align:center">{{$value['count']}}</td>
      <td width="5%" style="text-align:center">{{$value['persentase support']}}</td>
      <td width="5%" style="text-align:center">-</td>
    <?php $number = $number + 1 ?>
    @empty
      <td colspan="17"><center>Kosong</center></td>
    @endforelse
    </tr>
  </tbody>
</table>
<br><br>
<div style="margin-bottom: 10px">
Sequence 2
</div>
<div style="overflow: auto">
<table id="myTable" class="table table-striped table-bordered" cellspacing="0">
  <thead>
    <tr>
      <th style="text-align:center">Nomor</th>
      <th style="text-align:center">item</th>
      <th style="text-align:center">count</th>
      <th style="text-align:center">support</th>
      <th style="text-align:center">confidence</th>
    </tr> 
  </thead>
  <tbody>
   <?php $number = 1 ?>
   @forelse($sequence2 as $key => $value) 
    <tr>
      <td width="5%" style="text-align:center">{{$number}}</td>
      <td width="5%" style="text-align:center">{{strtoupper($key)}}</td>
      <td width="5%" style="text-align:center">{{$value['count']}}</td>
      <td width="5%" style="text-align:center">{{$value['support']}}</td>
      <td width="5%" style="text-align:center">{{$value['confidence']}}</td>
    <?php $number = $number + 1 ?>
    @empty
      <td colspan="17"><center>Kosong</center></td>
    @endforelse
    </tr>
  </tbody>
</table>
<br><br>
<div style="margin-bottom: 10px">
Sequence 3
</div>
<div style="overflow: auto">
<table id="myTable" class="table table-striped table-bordered" cellspacing="0">
  <thead>
    <tr>
      <th style="text-align:center">Nomor</th>
      <th style="text-align:center">item</th>
      <th style="text-align:center">count</th>
      <th style="text-align:center">support</th>
      <th style="text-align:center">confidence</th>
    </tr> 
  </thead>
  <tbody>
   <?php $number = 1 ?>
   @forelse($sequence3 as $key => $value) 
    <tr>
      <td width="5%" style="text-align:center">{{$number}}</td>
      <td width="5%" style="text-align:center">{{strtoupper($key)}}</td>
      <td width="5%" style="text-align:center">{{$value['count']}}</td>
      <td width="5%" style="text-align:center">{{$value['support']}}</td>
      <td width="5%" style="text-align:center">{{$value['confidence']}}</td>
    <?php $number = $number + 1 ?>
    @empty
      <td colspan="17"><center>Kosong</center></td>
    @endforelse
    </tr>
  </tbody>
</table>

</div>


@endsection

@section('code-footer')
@endsection