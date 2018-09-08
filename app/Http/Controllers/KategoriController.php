<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Excel;
use Session;
use Validator;
use Redirect;
use App\Kategori;
use App\Dummy;

class KategoriController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function tambah()
    {
        return view('admin.kategori.tambah');
    } 

    public function edit($id)
    {
        $data = [
            'data' => Pasien::find($id)
        ];
        return view('admin.kategori.edit',$data);
    }

    public function editPost(Request $request)
    {
        $validation = [
            "no_index" => "required",
            "uptd" => "required",
            "tanggal" => "required",
            "nama" => "required",
            "alamat" => "required",
            "kota" => "required",
            "kecamatan" => "required",
            "kelurahan" => "required",
            "nik" => "required",
            "umur" => "required",
            "tanggal_lahir" => "required",
            "jenis_kelamin" => "required",
            "pendidikan" => "required",
            "tipe_pasien" => "required",
            "keluhan" => "required"
        ];
        $validation = Validator::make($request->all(),$validation);
        if ($validation->fails()) {
            Session::put('alert-warning', 'Data gagal diubah, pastikan semua field telah terisi');
            return redirect()->back()->withInput();
        } else {
            $pasien = Pasien::find($request->input('id_pasien'));

            $pasien->update([
                "no_index" => $request->input('no_index'),
                "uptd" => $request->input('uptd'),
                "tanggal" => $request->input('tanggal'),
                "nama" => $request->input('nama'),
                "alamat" => $request->input('alamat'),
                "kota" => $request->input('kota'),
                "kecamatan" => $request->input('kecamatan'),
                "kelurahan" => $request->input('kelurahan'),
                "nik" => $request->input('nik'),
                "umur" => $request->input('umur'),
                "tanggal_lahir" => $request->input('tanggal_lahir'),
                "jenis_kelamin" => $request->input('jenis_kelamin'),
                "pendidikan" => $request->input('pendidikan'),
                "tipe_pasien" => $request->input('tipe_pasien'),
                "keluhan" => $request->input('keluhan')
            ]);

            $pasien->save();

            // Notifikasi sukses
            Session::put('alert-success', 'Data berhasil diedit');

            return Redirect::to('admin/index');
        }
        
    }

    public function delete($id)
    {
        Pasien::find($id)->delete();
        Session::put('alert-success', 'Data berhasil dihapus');
        return Redirect::to('admin/index');
    }

    public function create()
    {
        return view('admin.kategori.tambah');
    }

    public function add(Request $request)
    {
        $validation = [
            "name" => "required",
        ];
        $validation = Validator::make($request->all(),$validation);
        if ($validation->fails()) {
            Session::put('alert-warning', 'Data gagal ditambahkan, pastikan semua field telah terisi');
            return redirect()->back()->withInput();
        } else { 
            Kategori::create([
                'name' => $request->input('name'), 
            ]);
            
            Session::put('alert-success', 'Kategori berhasil ditambahkan');
            return Redirect::to('admin/kategori/index');
        }
    }

    public function index()
    {
        $data = [
            'kategori' => Kategori::all(),
        ];
        
        return view('admin.kategori.index', $data);
    }
}
