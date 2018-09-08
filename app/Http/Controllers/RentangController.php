<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Excel;
use Session;
use Validator;
use Redirect;
use App\Kategori;
use App\RentangKategori;

class RentangController extends Controller
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

    public function tambah($idKategori)
    {
        $data = [
            'kategori' => Kategori::find($idKategori)
        ];
        return view('admin.rentang.tambah',$data);
    } 

    public function edit($id)
    {
        $rentang = RentangKategori::find($id);
        $data = [
            'rentang' => $rentang,
            'kategori' => Kategori::find($rentang->kategori_id)
        ];
        return view('admin.rentang.edit',$data);
    }

    public function editPost(Request $request)
    {
        $validation = [
            "name" => "required",
            "batas_bawah" => "sometimes",
            "batas_atas" => "sometimes",
            "value" => "value",
        ];
        $validation = Validator::make($request->all(),$validation);
        if ($validation->fails()) {
            Session::put('alert-warning', 'Data gagal diubah, pastikan semua field telah terisi');
            return redirect()->back()->withInput();
        } else {
            $rentang = RentangKategori::find($request->input('idRentang'));
            if (count($request->input('batas_bawah')) !== 0) {
                $rentang->update([
                    "name" => $request->input('name'),
                    "batas_bawah" => $request->input('batas_bawah'),
                    "batas_atas" => $request->input('batas_atas'), 
                ]);
            } else {
                $rentang->update([
                    "name" => $request->input('name'),
                    "value" => $request->input('value'),
                ]);
            }
            

            $rentang->save();

            // Notifikasi sukses
            Session::put('alert-success', 'Data berhasil diedit');

            return Redirect::to('admin/rentang/'.$rentang->kategori_id.'/index');
        }
        
    }

    public function delete($idRentang)
    {
        RentangKategori::find($idRentang)->delete();
        Session::put('alert-success', 'Data berhasil dihapus');
        return redirect()->back();
    }

    public function create()
    {
        return view('admin.kategori.tambah');
    }

    public function add(Request $request)
    {
        $validation = [
            "name" => "required",
            "batas_bawah" => "sometimes",
            "batas_atas" => "sometimes",
            "value" => "sometimes",
        ];
        $validation = Validator::make($request->all(),$validation);
        if ($validation->fails()) {
            Session::put('alert-warning', 'Data gagal ditambahkan, pastikan semua field telah terisi');
            return redirect()->back()->withInput();
        } else { 
            if (count($request->input('batas_bawah')) !== 0) {
                RentangKategori::create([
                    'name' => $request->input('name'), 
                    'kategori_id' => $request->input('id_kategori'), 
                    'batas_bawah' => $request->input('batas_bawah'), 
                    'batas_atas' => $request->input('batas_atas'), 
                ]);
            } else {
                 RentangKategori::create([
                    'name' => $request->input('name'), 
                    'kategori_id' => $request->input('id_kategori'), 
                    'value' => $request->input('value'), 
                ]);
            }
            
            
            Session::put('alert-success', 'Rentang Kategori berhasil ditambahkan');
            return Redirect::to('admin/rentang/'.$request->input('id_kategori').'/index');
        }
    }

    public function index($idKategori)
    {
        $data = [
            'kategori' => Kategori::find($idKategori),
            'rentang' => RentangKategori::where('kategori_id',$idKategori)->get(),
        ];
        
        return view('admin.rentang.index', $data);
    }
}
