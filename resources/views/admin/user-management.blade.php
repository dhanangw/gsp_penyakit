@extends('adminlte::page')

@section('title', 'User Management')

@section('content_header')
    <h1>User Management</h1>
@stop

@section('content')
<br>
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
    @if (count($errors) > 0)
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
<div style="margin-bottom: 10px">
  <button type="button" class="btn btn-info btn-md" data-toggle="modal" data-target="#modalAdmin">
    <i class="fa fa-plus-square"></i> Tambah User</button>
</div>
<div style="overflow: auto">
<table id="myTable" class="table table-striped table-bordered" cellspacing="0" style="width: 50%;">
	<thead>
		<tr>
      <th>No.</th>
			<th>Nama</th>
			<th>E-mail</th>
			<th>Action</th>
		</tr>
	</thead>
 <tbody>
    <?php $i = 1 ?>
    @foreach($users as $user)
    <tr>
      <td width="1%">{{$i}}</td>
      <td width="25%">{{$user->name}} </td>
      <td width="30%">{{$user->email}}</td>
      <td width="15%">
        <form onsubmit="return confirm('Anda yakin untuk menghapus akun ini?');" action="{{url('user-management/'.$user->id.'/delete/')}}" method="GET">
          <button type="submit" class="btn btn-danger btn-xs">
            <i class="fa fa-trash"></i> Hapus
          </button>
        </form>
      </td>
    </tr>
    <?php $i++ ?>
    @endforeach
  </tbody>
</table>
</div>

<!-- Modal -->
<div id="modalAdmin" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h2 class="modal-title text-center"> Tambah User</h2>
      </div>
      <div class="modal-body">
         <form role="form" method="POST" action="{{url('user-management/add')}}">
         <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="form-group">
              <label for="name"><span class="glyphicon glyphicon-user"></span> Nama</label>
              <input type="text" class="form-control" id="name" placeholder="Masukkan Nama" name="name" required>
            </div>
             <div class="form-group">
              <label for="email"><span class="glyphicon glyphicon-envelope"></span> E-mail</label>
              <input type="email" class="form-control" id="email" placeholder="Masukkan E-mail" name="email"  required>
            </div>
            <div class="form-group">
              <label for="password"><span class="glyphicon glyphicon-lock"></span> Password</label>
              <input type="password" class="form-control" id="password" placeholder="Masukkan Password" name="password"  required>
            </div>
            <div class="form-group">
              <label for="password_confirmation"><span class="glyphicon glyphicon-lock"></span> Konfirmasi Password</label>
              <input type="password" class="form-control" id="password_confirmation" placeholder="Masukkan Password Kembali" name="password_confirmation"  required>
            </div>
            <div class="form-group" style="text-align:right">
              <button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-plus-sign"></span> Create</button>
            </div>
          </form>
      </div>
    </div>
  </div>
</div>

@stop
@section('css')

<style>
.modal-open {
 overflow: auto; 
}

/* extra stetting for fixed navbar, see bs3 doc
*/
.modal-body {
  padding: 50px;
}
</style>

@stop