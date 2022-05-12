<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

use App\User;
use App\Barang;
use App\Kategori;
use App\Department;
use App\Cad;
use App\Karyawan;

class APIController extends Controller
{
    public function barang(Request $request)
    {
        $res = array('success' => true);
        $q = $request->q;

        $barang = Barang::where('kode', 'like', '%' . $q . '%')
            ->orWhere('name', 'like', '%' . $q . '%')
			->where('recid',1)
            ->with('kategori')
            ->get();   

        if(session('role') == 5){
            $barang = Barang::where('kode', 'like', '%' . $q . '%')
            ->orWhere('name', 'like', '%' . $q . '%')
			->where('recid',1)
            ->with('kategori')
            ->get();    
        }

        

        $res['name'] = "Category 1";
        $res['results'] = $barang;

        return response()->json($res);
    }

    public function spare_part(Request $request)
    {
        $res = array('success' => true);
        $q = $request->q;

        $barang = Barang::where('kode', 'like', '%' . $q . '%')
            ->orWhere('name', 'like', '%' . $q . '%')
            ->with('kategori')
            
            ->get();

        $res['name'] = "Category 1";
        $res['results'] = $barang;

        return response()->json($res);
    }

    public function barang_cad(Request $request)
    {

        $res = array('success' => true);
        $q = $request->q;

        $barang = Cad::where('kode', 'like', '%' . $q . '%')
            ->select('id', 'mac as mac', 'mac as kode', 'name', 'kategori_id', 'recid', 'department_id', 'created_at', 'updated_at')
            ->orWhere('name', 'like', '%' . $q . '%')
            ->orWhere('mac', 'like', '%' . $q . '%')
            ->with('kategori')
            ->having('recid', 0)
            ->get();

        $res['name'] = "Category 1";
        $res['results'] = $barang;

        return response()->json($res);
    }

    public function kategori(Request $request)
    {
        $res = array('success' => true);
        $q = $request->q;

        $barang = Kategori::where('name', 'like', '%' . $q . '%')
            ->get();

        $res['name'] = "Category 1";
        $res['results'] = $barang;

        return response()->json($res);
    }

    public function department(Request $request)
    {
        $res = array('success' => true);
        $q = $request->q;

        $depart = Department::where('kdtk', 'like', '%' . $q . '%')
            ->orWhere('name', 'like', '%' . $q . '%')
            ->orderBY('kdtk')
            ->get();

        $res['name'] = "Category 1";
        $res['results'] = $depart;

        return response()->json($res);
    }

    public function karyawan(Request $request)
    {
        $res = array('success' => true);
        $q = $request->q;

        $depart = Karyawan::selectRaw('NIK, NAMA, CONCAT(NIK," | ",NAMA) AS KARYAWAN')
            ->where('nik', 'like', '' . $q . '%')
            ->orWhere('nama', 'like', '%' . $q . '%')
            ->get();

        $res['name'] = "Category 1";
        $res['results'] = $depart;

        return response()->json($res);
    }

    public function Opr(Request $request)
    {
        $res = array('success' => true);
        $q = $request->q;

        $depart = User::selectRaw('nik, name, CONCAT(nik," | ",name) AS OPR')
            ->where([
                ['nik', 'like', '' . $q . '%'],
                ['role', 5]
            ])
            ->orWhere([
                ['name', 'like', '%' . $q . '%'],
                ['role', 5]
            ])
            ->get();

        $res['name'] = "Category 1";
        $res['results'] = $depart;

        return response()->json($res);
    }

    public function pinjam(Request $request)
    {
        $res = array('success' => true);
        $kdtk_id = $request->kdtk_id;

        $pinjam = Cad::where('department_id', $kdtk_id)->get();

        $res['results'] = $pinjam;

        return response()->json($res);
    }
}
