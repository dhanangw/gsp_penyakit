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
            'kategori' => Kategori::find($id)
        ];
        return view('admin.kategori.edit',$data);
    }

    public function editPost(Request $request)
    {
        $validation = [
            "name" => "required",
        ];
        $validation = Validator::make($request->all(),$validation);
        if ($validation->fails()) {
            Session::put('alert-warning', 'Data gagal diubah, pastikan semua field telah terisi');
            return redirect()->back()->withInput();
        } else {
            $kategori = Kategori::find($request->input('idKategori'));

            $kategori->update([
                "name" => $request->input('name'),
            ]);

            $kategori->save();

            // Notifikasi sukses
            Session::put('alert-success', 'Data berhasil diedit');

            return Redirect::to('admin/kategori/index');
        }
        
    }

    public function delete($id)
    {
        Kategori::find($id)->delete();
        Session::put('alert-success', 'Data berhasil dihapus');
        return Redirect::to('admin/kategori/index');
    }

    public function create()
    {
        return view('admin.kategori.tambah');
    }

    public function add(Request $request)
    {
        $validation = [
            "name" => "required",
            "type" => "required",
        ];
        $validation = Validator::make($request->all(),$validation);
        if ($validation->fails()) {
            Session::put('alert-warning', 'Data gagal ditambahkan, pastikan semua field telah terisi');
            return redirect()->back()->withInput();
        } else { 
            
            Kategori::create([
                'name' => $request->input('name'), 
                "type" => $request->input('type'), 
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
