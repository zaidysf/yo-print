<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class FileController extends Controller
{
    public function index(): View
    {
        return view('files.index', [

        ]);
    }
}
