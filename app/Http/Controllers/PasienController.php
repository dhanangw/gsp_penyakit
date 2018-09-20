<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Excel;
use Session;
use Validator;
use Redirect;
use App\Pasien;
use App\Kategori;
use App\RentangKategori;
use App\Dummy;

class PasienController extends Controller
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

    public function tambah($id = null)
    {
        $data['kategori'] = Kategori::all();
        $data['rentang'] = RentangKategori::all();
        if (isset($id)) {
            $data['pasien'] = Pasien::find($id);
            return view('admin.pasien.tambah',$data);
        } else {
            return view('admin.pasien.tambah',$data);
        }
        
    } 

    public function edit($id)
    {
        $data = [
            'data' => Pasien::find($id)
        ];
        return view('admin.pasien.edit',$data);
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

            // Kembali ke halaman inventaris/asset
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
        $data['kategori'] = Kategori::all();
        $data['rentang'] = RentangKategori::all();
        
        return view('admin.pasien.tambah',$data);
    }

    public function add(Request $request)
    {
        $kategori = Kategori::select('name')->get();
        foreach ($kategori as $key => $value) {
            $kategoriRule = [$value->name => 'required'];
        }
        
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
            "tanggal_lahir" => "required",
            "jenis_kelamin" => "required",
            "pendidikan" => "required",
            "tipe_pasien" => "required",
            "keluhan" => "required"
        ];
        $validation = array_merge($validation,$kategoriRule);
        $validation = Validator::make($request->all(),$validation);
        if ($validation->fails()) {
            Session::put('alert-warning', 'Data gagal ditambahkan, pastikan semua field telah terisi');
            return redirect()->back()->withInput();
        } else {
            $keluhan = $request->input('keluhan');
            $kategori = Kategori::all();
            
            foreach ($kategori as $key => $value) {
                $kategoriRaw = $request->input($value->name);
                if ($value->type == 'ranged') {
                    $kategoriConverted = RentangKategori::where([
                        ['kategori_id', '=', $value->id],
                        ['batas_bawah', '<=', $kategoriRaw],
                        ['batas_atas', '>=', $kategoriRaw]
                    ])->value('name');
                } else {
                    $kategoriConverted = RentangKategori::where('value',$kategoriRaw)->value('name');
                }
                $keluhan = $kategoriConverted.','.$keluhan;
            }
            
            Pasien::create([
                'uptd' => $request->input('uptd'), 
                'tanggal' => $request->input('tanggal'),
                'no_index' => $request->input('no_index'),
                'nama' => $request->input('nama'),
                'alamat' => $request->input('alamat'),
                'kota' => $request->input('kota'),
                'kecamatan' => $request->input('kecamatan'),
                'kelurahan' => $request->input('kelurahan'),
                'nik' => $request->input('nik'),
                'tanggal_lahir' => $request->input('tanggal_lahir'),
                'jenis_kelamin' => $request->input('jenis_kelamin'),
                'pendidikan' => $request->input('pendidikan'),
                'pekerjaan' => $request->input('pekerjaan'),
                'tipe_pasien' => $request->input('tipe-pasien'),
                'keluhan' => $keluhan,
            ]);
            
            Session::put('alert-success', 'Data berhasil ditambahkan');
            return Redirect::to('admin/index');
        }
    }

    public function index()
    {
        $pasien = Pasien::all();
        $title = array_keys($pasien[0]->toArray());
        $data =[
            'pasien' => $pasien,
            'title' => $title
        ];
        
        return view('admin.pasien.index', $data);
    }

    //1. get gejala by id
    public function indekskanGejala($data)
    {   
        // $tanggalAwal = '2010-02-06 19:30:13';
        // $tanggalAkhir = '2010-02-06 19:30:13';

        $indexGejala = [];
        foreach ($data as $keluhan) {
            
            //get pasien gejala satu persatu (convert ke lowercase)
            $keluhan = explode(',',strtolower($keluhan->keluhan));

            foreach ($keluhan as $value) {    
                //pastikan gejala unik
                if (in_array($value, $indexGejala)) { //jika gejala sudah terindex
                    continue; //lewati iterasi
                } else {
                    array_push($indexGejala, $value);
                }
            }
        }
        //dd($indexGejala);
        return $indexGejala;
    }

    // 2. ubah keluhan/gejala pasien menjadi ID keluhan
    public function getGejalaPasienInID($indexGejala, $dataPasien)
    {
        //ambil semua data nama dan keluhan pasien
        $gejalaPasienInID = [];

        foreach ($dataPasien as $pasien) {
            $indexGejalaPerBerobat = [];
            //ambil keluhan pasien satu-persatu
            if (isset($gejalaPasienInID[$pasien['no_index']])) {
                $dataKeluhan = explode(',',strtolower($pasien['keluhan']));
                foreach ($dataKeluhan as $key => $value) 
                {
                    if (in_array($value,$indexGejala)) //jika nama keluhan ada di dalam index
                    {
                        array_push($indexGejalaPerBerobat, array_search($value,$indexGejala));
                    } else 
                    {
                        continue;
                    }
                }
                array_push($gejalaPasienInID[$pasien['no_index']], $indexGejalaPerBerobat);
            } else {
                $dataKeluhan = explode(',',strtolower($pasien['keluhan']));
                foreach ($dataKeluhan as $key => $value) 
                {
                    if (in_array($value,$indexGejala)) //jika nama keluhan ada di dalam index
                    {
                        array_push($indexGejalaPerBerobat, array_search($value,$indexGejala));
                    } else 
                    {
                        continue;
                    }
                }
                $gejalaPasienInID[$pasien['no_index']] = [$indexGejalaPerBerobat];
            }
        }
        return $gejalaPasienInID;
    }

    // 3. hitung berapa orang yang memiliki suatu gejala
    public function sequence1($indexGejala, $gejalaPasienInID, $minSupport)
    {
        $jumlahGejala = [];
        foreach ($indexGejala as $index => $namaGejala) {
            $count = 0;
            foreach ($gejalaPasienInID as $nomorIndex => $dataBerobat) {
                foreach ($dataBerobat as $value) {
                    //periksa jika pasien punya index keluhan di salah satu berobatnya 
                    if (in_array($index,$value)) {
                        $count++;
                        break;
                    }
                }
            }
            $support = $count / count($gejalaPasienInID);
            $supportPercentage = round((float)$support * 100 );
            //ambil yang persentase support >= minimal support
            if ($supportPercentage >= $minSupport) {
                $jumlahGejala[$index] = ['count' => $count, 'persentase support' => $supportPercentage];
            } else {
                continue;
            }
        }
        return $jumlahGejala;
    }

    public function sequence2Terpisah($jumlahGejala, $gejalaPasienInID, $minSupport, $minConfidence)
    {
        $sequence2Terpisah = [];
        foreach ($jumlahGejala as $indeksKeluhan1 => $value1) 
        {
            foreach ($jumlahGejala as $indeksKeluhan2 => $value2) 
            {
                $count = 0;
                //cari yang keluhannya terpisah
                foreach ($gejalaPasienInID as $nomorIndexPasien => $dataBerobat) {
                    $adaKeluhan1 = false;
                    $adaKeluhan2 = false;
                    $adaKeluhan1Key = null;
                    //cari apakah ada keluhan 1 di data berobat
                    foreach ($dataBerobat as $keyBerobat => $gejalaBerobat) {
                        if (in_array($indeksKeluhan1,$gejalaBerobat)) 
                        {
                            $adaKeluhan1 = true;
                            $adaKeluhan1Key = $keyBerobat;
                            break;
                        }
                    }
                    //cari apakah ada keluhan 2 di data berobat
                    foreach ($dataBerobat as $keyBerobat => $gejalaBerobat) {
                        if ($keyBerobat <= $adaKeluhan1Key) {
                            continue;
                        } else {
                            if (in_array($indeksKeluhan2,$gejalaBerobat)) {
                                $adaKeluhan2 = true;
                                break;
                            }
                        }
                    }
                    //jika ada keluhan 1 dan ada keluhan 2
                    if ($adaKeluhan1 == true && $adaKeluhan2 == true) {
                        $count = $count+1;
                    }
                }
                $support = $count / count($gejalaPasienInID); //support = jumlah gejala / jumlah pasien unik
                $supportPercentage = round((float)$support * 100 );
                $confidence = $count / $jumlahGejala[$indeksKeluhan1]['count']; //confidence = jumlah gejala / jumlah gejala awal
                $confidencePercentage = round((float)$confidence * 100 );
                //saring berdasarkan minimum support dan minimum confidence
                if ($supportPercentage >= $minSupport && $confidencePercentage >= $minConfidence) {
                    $sequence2Terpisah[$indeksKeluhan1.','.$indeksKeluhan2]['count'] = $count;
                    $sequence2Terpisah[$indeksKeluhan1.','.$indeksKeluhan2]['support'] = $supportPercentage;
                    $sequence2Terpisah[$indeksKeluhan1.','.$indeksKeluhan2]['confidence'] = $confidencePercentage;
                } else {
                    continue;
                }
            }
        }
        return $sequence2Terpisah;
    }

    public function sequence2Bersamaan($jumlahGejala, $gejalaPasienInID, $minSupport, $minConfidence)
    {
        $sequence2Bersamaan = [];
        $skippedSequence = [];
        foreach ($jumlahGejala as $indeksKeluhan1 => $value1) {
            foreach ($jumlahGejala as $indeksKeluhan2 => $value2) {
                if ($indeksKeluhan1 == $indeksKeluhan2 || in_array([$indeksKeluhan2,$indeksKeluhan1],$skippedSequence) || isset($sequence2Bersamaan['('.$indeksKeluhan2.','.$indeksKeluhan1.')'])) {
                    continue;
                }
                $count = 0;
                //hitung jumlah keluhan yang bersamaan
                foreach ($gejalaPasienInID as $nomorIndexPasien => $dataBerobat)  {
                    foreach ($dataBerobat as $keyBerobat => $gejalaBerobat) {
                        if (in_array($indeksKeluhan1,$gejalaBerobat) && in_array($indeksKeluhan2,$gejalaBerobat)){
                            $count = $count+1;
                            break;
                        }
                    }
                }
                $support = $count / count($gejalaPasienInID); //support = jumlah gejala / jumlah pasien unik
                $supportPercentage = round((float)$support * 100 );
                $confidence = $count / $jumlahGejala[$indeksKeluhan1]['count']; //confidence = jumlah gejala / jumlah gejala awal
                $confidencePercentage = round((float)$confidence * 100 );
                //saring berdasarkan minimum support dan minimum confidence
                if ($supportPercentage >= $minSupport && $confidencePercentage >= $minConfidence) {
                    $sequence2Bersamaan['('.$indeksKeluhan1.','.$indeksKeluhan2.')']['count'] = $count;
                    $sequence2Bersamaan['('.$indeksKeluhan1.','.$indeksKeluhan2.')']['support'] = $supportPercentage;
                    $sequence2Bersamaan['('.$indeksKeluhan1.','.$indeksKeluhan2.')']['confidence'] = $confidencePercentage;
                } else {
                    array_push($skippedSequence,[$indeksKeluhan1,$indeksKeluhan2]);
                }
            }
        }
        return $sequence2Bersamaan;
    }   

    public function sequence2($sequence1, $keluhanPasienInID, $minSupport, $minConfidence)
    {
        $sequence2Terpisah = $this->sequence2Terpisah($sequence1, $keluhanPasienInID, $minSupport, $minConfidence);
        $sequence2Bersamaan = $this->sequence2Bersamaan($sequence1, $keluhanPasienInID, $minSupport, $minConfidence);
        return array_merge($sequence2Terpisah,$sequence2Bersamaan);
    }

    public function minus1st($sequence2)
    {
        $resultMinus1st = [];
        foreach ($sequence2 as $sequenceKey => $value) {
            $item = explode(',', $sequenceKey);
            $minus1st = $item[0];    
            
            if (strpos($minus1st,')') === false && strpos($minus1st,'(') === false) {
                unset($item[0]);
                $result = implode(',',$item);
                $resultMinus1st[$sequenceKey] = [$result];
            } else {
                $result = [];
                if (isset($item[1])) {
                    if (strpos($item[1],')') !== false) {
                        $setBerikutnya = array_slice($item, 2);
                        $item = array_diff($item, $setBerikutnya);
                        
                        foreach (array_reverse($item) as $key => $value) {
                            $t = filter_var($value, FILTER_SANITIZE_EMAIL).implode(',',$setBerikutnya);
                            array_push($result,$t);        
                        }
                    }
                } else {
                    foreach (array_reverse($item) as $key => $value) {
                        $t = filter_var($value, FILTER_SANITIZE_EMAIL);
                        array_push($result,$t);
                    }
                }
                $resultMinus1st[$sequenceKey] =  $result;
            } 
        }
        return $resultMinus1st;
    }

    public function minusLast($sequence2)
    {
        $resultMinusLast = [];
        foreach ($sequence2 as $sequenceKey => $value) {
            $item = explode(',', $sequenceKey);
            $max = count($item)-1;
            $minusLast = $item[$max];    
            
            if (strpos($minusLast,'(') === false && strpos($minusLast,')') === false) {
                unset($item[$max]);
                $result = implode(',',$item);    
                $resultMinusLast[$sequenceKey] =  [$result];
            } else {
                //cek apakah gandeng atau sendirian
                if (strpos($item[$max-1],'(') !== false && !isset($item[$max-2])) {
                    //gandeng sendirian
                    foreach ($item as $key => $value) {
                        $item[$key] = filter_var($value, FILTER_SANITIZE_EMAIL);
                    }
                    $resultMinusLast[$sequenceKey] =  $item;
                } else {
                    //gandeng tidak sendirian
                    if (strpos($item[$max-1],'(') !== false) {
                        $setTerakhir = [ $item[$max-1],$item[$max] ];    
                        $setSebelumnya = array_diff($item,$setTerakhir);
                        $setSebelumnya = implode(',',$setSebelumnya);

                        foreach ($setTerakhir as $key => $value) {
                            $value = filter_var($value, FILTER_SANITIZE_EMAIL);
                            $result[$key] =  $setSebelumnya.',('.$value.')';
                        }
                    } else {
                        foreach ($item as $key => $value) {
                            $t = filter_var($value, FILTER_SANITIZE_EMAIL);
                            array_push($result,$t);
                        }
                    }
                    $resultMinusLast[$sequenceKey] =  $result;
                }   
            }
        }
        return $resultMinusLast;
    }

    public function generateCandidate($sequence2)
    {
        $minus1st = $this->minus1st($sequence2);
        $minusLast = $this->minusLast($sequence2);
        
        $nextSequence = [];
        foreach ($sequence2 as $key => $value) {
            $minusLastForSearch = $minusLast;
            unset($minusLastForSearch[$key]);
            $key2 = [];
            foreach ($minus1st[$key] as $keyMinus1st => $valueMinus1st) {
                foreach ($minusLastForSearch as $keyMinusLast => $valueMinusLast) {    
                    if(in_array($valueMinus1st, $valueMinusLast)){
                        array_push($key2,$keyMinusLast);
                    }  
                }
            }
            
            if (empty($key2)) {
                continue;
            }
            foreach ($key2 as $key2) {
                //jika format key1 = x,y dan format key2 = x,y
                if (strpos($key,'(') === false && strpos($key2,'(') === false && strpos($key,')') === false && strpos($key2,')') === false) {
                    $tempKey = $key.','.$key2;
                    $tempKey = explode(',',$tempKey);
                    $tempKey = implode(',',[$tempKey[0],$tempKey[1],$tempKey[3]]);
                }
                //jika format key1 = x,y dan format key2 = (x,y)
                elseif (strpos($key2,'(') !== false && strpos($key,'(') === false) {
                    $item1 = explode(',', $key);
                    if (str_contains($key2,$item1[count($item1)-1])) {
                        $tempKey = $item1[0].','.$key2;
                    }
                }
                //jika format key1 = (x,y) dan format key2 = x,y
                elseif (strpos($key,'(') !== false && strpos($key2,'(') === false) {
                    $item2 = explode(',', $key2);
                    if (str_contains($key2,$item2[count($item2)-1])) {
                        $tempKey = $item2[0].','.$key2;
                    }
                }
                //jika format key1 = (x,y) dan format key2 = (x,y)
                elseif (strpos($key,'(') !== false && strpos($key2,'(') !== false) {
                    $item = explode(',',$key);
                    $item2 = explode(',',$key2);
                    
                    foreach ($item as $keyItem1 => $value) {
                        $item[$keyItem1] = filter_var($value, FILTER_SANITIZE_EMAIL);
                    }
                    
                    foreach ($item2 as $keyItem2 => $value) {
                        $item2[$keyItem2] = filter_var($value, FILTER_SANITIZE_EMAIL);
                    }
                    
                    $itemYangBeda = array_diff($item2,$item);
                    $tempKey = '('.implode(',',$item).','.implode('',$itemYangBeda).')';

                    $cekKembar = $this->checkIfCombinationExist($nextSequence,$tempKey);
                    
                    if ($cekKembar !== false){
                        $tempKey = $cekKembar;
                    } 
                }
                array_push($nextSequence,$tempKey);
            } 
        }
        return $nextSequence;
    }

    public function checkIfCombinationExist($previousSequences, $sequence)
    {
        $result = false;
        //cari tahu apakah sudah ada array key yang sama (a,b,c) == (b,c,a) == (b,a,c) == ...
        if (!empty($previousSequences) ) {
            $jumlahYangSama = 0;
            $newSequence = explode(',',$sequence);
            foreach ($newSequence as $key => $value) {
                $newSequence[$key] = filter_var($value, FILTER_SANITIZE_EMAIL);
            }
            
            foreach ($previousSequences as $keyPreviousSequence) {
                $keyPreviousSequence = explode(',',$keyPreviousSequence);
                if (strpos($keyPreviousSequence[0],'(') !== false && strpos($keyPreviousSequence[count($keyPreviousSequence)-1],')') !== false) {
                    foreach ($keyPreviousSequence as $keyPreviousSequenceCleaned => $value) {
                        $keyPreviousSequence[$keyPreviousSequenceCleaned] = filter_var($value, FILTER_SANITIZE_EMAIL);
                    }
                    //jika perbedaan array tidak kosong maka elemen array sama
                    if (empty(array_diff($keyPreviousSequence,$newSequence))) {
                        $jumlahYangSama++;
                    }
                }
            }
            if ($jumlahYangSama === 0) {
                $newSequence = implode(',',$newSequence);
                $result = '('.$newSequence.')';
            }
        }
        return $result;
    }

    public function sequence3($keySequence3,$sequence2,$sequence1,$keluhanPasienInID, $minSupport, $minConfidence)
    {
        //dd($keySequence3,$sequence2,$sequence1);
        $sequence3 = [];
        foreach ($keySequence3 as $value) {
            $key = explode(',',$value);
            $count = 0;

            //key = (0,0),0
            if (strpos($key[0],'(') !== false && strpos($key[1],')') !== false) {
                $set1 = [
                    filter_var($key[0], FILTER_SANITIZE_EMAIL),
                    filter_var($key[1], FILTER_SANITIZE_EMAIL)
                ];

                $set2 = filter_var($key[2], FILTER_SANITIZE_EMAIL);
                
                foreach ($keluhanPasienInID as $nomorIndexPasien => $dataBerobat) {
                    $adaSet1 = false;
                    $adaSet2 = false;
                    //cari apakah ada set 1 di data berobat
                    foreach ($dataBerobat as $keyBerobat => $gejalaBerobat) {
                        if (in_array($set1,$gejalaBerobat)) 
                        {
                            $adaSet1 = true;
                            $adaSet1Key = $keyBerobat;
                            break;
                        }
                    }
                    
                    //cari apakah ada set 2 di data berobat
                    foreach ($dataBerobat as $keyBerobat => $gejalaBerobat) {
                        if ($keyBerobat <= $adaSet1Key) {
                            continue;
                        } else {
                            if (in_array($set2,$gejalaBerobat)) {
                                $adaSet2 = true;
                                break;
                            }
                        }
                    }
                    //jika ada set 1 dan ada set 2
                    if ($adaSet1 === true && $adaSet2 === true) {
                        $count = $count+1;
                    }
                }

                $support = $count / count($keluhanPasienInID);
                $supportPercentage = round((float)$support * 100 );
                if (isset($sequence2['('.$set1[0].','.$set1[1].')']) && $sequence2['('.$set1[0].','.$set1[1].')']['count'] !== 0) {
                    $confidence = $count / $sequence2['('.$set1[0].','.$set1[1].')']['count'];
                    $confidencePercentage = round((float)$confidence * 100 );
                }
            }
            //key = 0,(0,0)
            elseif (strpos($key[1],'(') !== false && strpos($key[2],')') !== false) {
                $set1 = filter_var($key[0], FILTER_SANITIZE_EMAIL);

                $set2 = [
                    filter_var($key[1], FILTER_SANITIZE_EMAIL),
                    filter_var($key[2], FILTER_SANITIZE_EMAIL)
                ];
                
                foreach ($keluhanPasienInID as $nomorIndexPasien => $dataBerobat) {
                    $adaSet1 = false;
                    $adaSet2 = false;
                    //cari apakah ada set 1 di data berobat
                    foreach ($dataBerobat as $keyBerobat => $gejalaBerobat) {
                        if (in_array($set1,$gejalaBerobat)) 
                        {
                            $adaSet1 = true;
                            $adaSet1Key = $keyBerobat;
                            break;
                        }
                    }
                    
                    //cari apakah ada set 2 di data berobat
                    foreach ($dataBerobat as $keyBerobat => $gejalaBerobat) {
                        if ($keyBerobat <= $adaSet1Key) {
                            continue;
                        } else {
                            if (in_array($set2,$gejalaBerobat)) {
                                $adaSet2 = true;
                                break;
                            }
                        }
                    }
                    //jika ada set 1 dan ada set 2
                    if ($adaSet1 === true && $adaSet2 === true) {
                        $count = $count+1;
                    }
                }
                
                $support = $count / count($keluhanPasienInID);
                $supportPercentage = round((float)$support * 100 );
                if (isset($sequence2[$set1[0].','.$set2[1]]) && $sequence2[$set1[0].','.$set2[1]]['count'] !== 0) {
                    $confidence = $count / $sequence2[$set1[0].','.$set2[1]]['count'];
                    $confidencePercentage = round((float)$confidence * 100 );
                }
            }
            //key = (0,0,0)
            elseif (strpos($key[0],'(') !== false && strpos($key[2],')') !== false) {
                $set1 = [
                    filter_var($key[0], FILTER_SANITIZE_EMAIL),
                    filter_var($key[1], FILTER_SANITIZE_EMAIL),
                    filter_var($key[2], FILTER_SANITIZE_EMAIL)
                ];

                foreach ($keluhanPasienInID as $nomorIndexPasien => $dataBerobat) {
                    $adaSet1 = false;
                    //cari apakah ada set 1 di data berobat
                    foreach ($dataBerobat as $keyBerobat => $gejalaBerobat) {
                        if (in_array($set1,$gejalaBerobat)) 
                        {
                            $adaSet1 = true;
                            $adaSet1Key = $keyBerobat;
                            break;
                        }
                    }
                    
                    //jika ada set 1
                    if ($adaSet1 === true) {
                        $count = $count+1;
                    }      
                }

                $support = $count / count($keluhanPasienInID);
                $supportPercentage = round((float)$support * 100 );
                if (isset($sequence2['('.$set1[0].','.$set1[1].')']) && $sequence2['('.$set1[0].','.$set1[1].')']['count'] !== 0) {
                    $confidence = $count / $sequence2['('.$set1[0].','.$set1[1].')']['count'];
                    $confidencePercentage = round((float)$confidence * 100 );
                }
            }
            //key = 0,0,0
            else {
                $set1 = [
                    filter_var($key[0], FILTER_SANITIZE_EMAIL),
                    filter_var($key[1], FILTER_SANITIZE_EMAIL),
                    filter_var($key[2], FILTER_SANITIZE_EMAIL)
                ];

                foreach ($keluhanPasienInID as $nomorIndexPasien => $dataBerobat) {
                    $adaSet1 = false;
                    //cari apakah ada set 1 di data berobat
                    foreach ($dataBerobat as $keyBerobat => $gejalaBerobat) {
                        if (in_array($set1,$gejalaBerobat)) 
                        {
                            $adaSet1 = true;
                            $adaSet1Key = $keyBerobat;
                            break;
                        }
                    }
                    
                    //jika ada set 1
                    if ($adaSet1 === true) {
                        $count = $count+1;
                    }      
                }

                $support = $count / count($keluhanPasienInID);
                $supportPercentage = round((float)$support * 100 );
                if (isset($sequence2[$set1[0].','.$set1[1]]) && $sequence2[$set1[0].','.$set1[1]]['count'] !== 0) {
                    $confidence = $count / $sequence2[$set1[0].','.$set1[1]]['count'];
                    $confidencePercentage = round((float)$confidence * 100 );
                }
            }

            $confidencePercentage = '';
            if ($supportPercentage >= $minSupport && $confidencePercentage >= $minConfidence) {
                $sequence3[$value]['count'] = $count;
                $sequence3[$value]['support'] = $supportPercentage;
                $sequence3[$value]['confidence'] = $confidencePercentage;
            }
        }
        //dd($sequence1,$sequence2,$keySequence3);
        return $sequence3;
    }

    public function cariPola()
    {
        return view('cariPola');
    }

    public function cariPolaPost(Request $request)
    {
        $validation = [
            "min_support" => "required",
            "min_confidence" => "required",
            "tanggal_batas_bawah" => "sometimes",
            "tanggal_batas_atas" => "sometimes",
        ];
        $validation = Validator::make($request->all(),$validation);
        if ($validation->fails()) {
            Session::put('alert-warning', 'Pola keluhan gagal dicari, pastikan semua field telah terisi');
            return redirect()->back()->withInput();
        } else {
            
            $minSupport = $request->input('min_support');
            $minConfidence = $request->input('min_confidence');
            // dd($request->input('tanggal_batas_bawah'));
            // dd($request->input('tanggal_batas_bawah'));
            
            if (is_array($request->input('tanggal_batas_bawah')) && count($request->input('tanggal_batas_bawah')) != null && count($request->input('tanggal_batas_bawah')) != null) {
                $batasBawah = $request->input('tanggal_batas_bawah');
                $batasAtas = $request->input('tanggal_batas_atas');
                $data = Pasien::select('keluhan')->whereBetween('tanggal',[$batasBawah,$batasAtas])->get();
            } else {
                $data = Pasien::select('keluhan')->get();
            }
            
            if (count($data) == 0) {
                Session::put('alert-warning', 'Pola keluhan gagal dicari, tidak ada data pada rentang tanggal tersebut');
                return redirect()->back()->withInput();
            }
            
            $dataPasien = Pasien::select('no_index','keluhan')->get()->toArray();
            $index = $this->indekskanGejala($data);
            $keluhanPasienInID = $this->getGejalaPasienInID($index, $dataPasien);
            $sequence1 = $this->sequence1($index, $keluhanPasienInID, $minSupport);
            $sequence2 = $this->sequence2($sequence1, $keluhanPasienInID, $minSupport, $minConfidence);
            $keySequence3 = $this->generateCandidate($sequence2);
            $sequence3 = $this->sequence3($keySequence3,$sequence2,$sequence1,$keluhanPasienInID,$minSupport,$minConfidence);
            if (!empty($sequence1)) {
                foreach ($sequence1 as $indeksKeluhan => $value) {
                    unset($sequence1[$indeksKeluhan]);
                    $sequence1[$index[$indeksKeluhan]] = $value;
                }
            }
            
            if (!empty($sequence2)) {
                foreach ($sequence2 as $indeksKeluhan => $value) {
                    $keluhan = explode(',',$indeksKeluhan);
                    if (str_contains($keluhan[0],'(')) {
                        $k = str_split($keluhan[0]);
                        if (count($k) == 3) {
                            $set1 = '('.$index[$k[1].$k[2]];
                        } else {
                            $set1 = '('.$index[$k[1]];
                        }
                    } else {
                        $set1 = $index[$keluhan[0]];
                    }

                    if (str_contains($keluhan[1],')')) {
                        $k = str_split($keluhan[1]);
                        if (count($k) == 3) {
                            $set2 = $index[$k[0].$k[1]].')';
                        } else {
                            $set2 = $index[$k[0]].')';
                        }
                    } else {
                        $set2 = $index[$keluhan[1]];
                    }
                    
                    $newIndex = $set1.','.$set2;

                    unset($sequence2[$indeksKeluhan]);
                    $sequence2[$newIndex] = $value;
                }
            }

            if (!empty($sequence3)) {
                foreach ($sequence3 as $indeksKeluhan => $value) {
                    $keluhan = explode(',',$indeksKeluhan);
                    
                    if (str_contains($keluhan[0],'(')) {
                        $k = str_split($keluhan[0]);
                        if (count($k) == 3) {
                            $set1 = '('.$index[$k[1].$k[2]];
                        } else {
                            $set1 = '('.$index[$k[1]];
                        }
                    } else {
                        $set1 = $index[$keluhan[0]];
                    }

                    if (str_contains($keluhan[1],'(')) {
                        $k = str_split($keluhan[1]);
                        if (count($k) == 3) {
                            $set2 = '('.$index[$k[1].$k[2]];
                        } else {
                            $set2 = '('.$index[$k[1]];
                        }
                    } 

                    if (str_contains($keluhan[1],')')) {
                        $k = str_split($keluhan[1]);
                        if (count($k) == 3) {
                            $set2 = $index[$k[0].$k[1]].')';
                        } else {
                            $set2 = $index[$k[0]].')';
                        }
                    } 

                    if (!str_contains($keluhan[1],')') && !str_contains($keluhan[1],'(')) {
                        $set2 = $index[$keluhan[1]];
                    }

                    
                    if (str_contains($keluhan[2],')')) {
                        $k = str_split($keluhan[2]);
                        if (count($k) == 3) {
                            $set3 = $index[$k[1].$k[2]].')';
                        } else {
                            $set3 = $index[$k[0]].')';
                        }
                    } else {
                        $set3 = $index[$keluhan[2]];
                    }
                    
                    
                    $new_index = $set1.','.$set2.','.$set3;
                    unset($sequence3[$indeksKeluhan]);
                    $sequence3[$new_index] = $value;
                }
            }
            $data = [
                'sequence1' => $sequence1,
                'sequence2' => $sequence2,
                'sequence3' => $sequence3,
            ];
            
            return view('cariPolaResult',$data);
        }
    }


    public function populateTable()
    {
        $data = Excel::load(public_path("/data-puskesmas/dummy.xlsx"), function($reader) {})->get();
        foreach ($data as $value) {
            Dummy::create([
                "nama" => $value->nama,
                "kelamin" => $value->kelamin,
                "umur" => $value->umur,
                "berat_badan" => $value->berat_badan,
                "tinggi_badan" => $value->tinggi_badan,
                "suhu" => $value->suhu,
                "gejala" => $value->gejala,
                "diagnosa" => $value->diagnosa,
            ]);
        }
        //dd('sudah dummy');
    }
}
