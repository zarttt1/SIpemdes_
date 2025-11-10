<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TanggapanController extends Controller
{
    public function index()
    {
        return view('tanggapan.index'); // pastikan view-nya ada di resources/views/tanggapan/index.blade.php
    }
}
