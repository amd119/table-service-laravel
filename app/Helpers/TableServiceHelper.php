<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

use App\Models\User;
use App\Models\Menu;
use App\Models\Meja;

class TableServiceHelper
{
    public static function checkIsNotLogin()
    {
        if (!Session::has('login')) {
            return Redirect::to('/login');
        }
    }

    public static function hitung($table)
    {
        return DB::table($table)->count();
    }

    // public static function totalKoleksi($table, $userId)
    // {
    //     return Koleksi::where('UserID', $userId)->count();
    // }

    // public static function totalPinjamanDikembalikan($table, $userId)
    // {
    //     return Peminjaman::where('UserID', $userId)->count();
    // }
}
