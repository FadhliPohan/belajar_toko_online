<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(){
        return view('laporan.index',[
            'title'=>'Laporan'
        ]);
    }
    public function proses(Request $request){
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $bulan_transaksi = date('Y-m', strtotime($request->tahun.'-'.$request->bulan));
        $itemtransaksi = Order::whereHas('cart', function($q) use ($bulan_transaksi) {
            $q->where('status_pembayaran', 'sudah');
            $q->where('created_at', 'like', $bulan_transaksi.'%');
        })
        ->get();
        $data = array('title' => 'Laporan Penjualan',
                    'itemtransaksi' => $itemtransaksi,
                    'bulan' => $this->cetakbulan($bulan),
                    'tahun' => $tahun);
        return view('laporan.proses', $data)->with('no', 1);
    }

    public function cetakbulan($bulan) {
        switch ($bulan) {
            case '1':
                return "Januari";
                break;
            case '2':
                return "Februari";
                break;
            case '3':
                return "Maret";
                break;
            case '4':
                return "April";
                break;
            case '5':
                return "Mei";
                break;
            case '6':
                return "Juni";
                break;
            case '7':
                return "Juli";
                break;
            case '8':
                return "Agustus";
                break;
            case '9':
                return "September";
                break;
            case '10':
                return "Oktober";
                break;
            case '11':
                return "Nopember";
                break;
            case '12':
                return "Desember";
                break;

            default:
                return "";
                break;
        }
    }
}
