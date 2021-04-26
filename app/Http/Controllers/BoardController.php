<?php

namespace App\Http\Controllers;

use App\Models\Board;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BoardController extends Controller
{
    public function index()
    {
        $boards = Board::all();
        return $boards;

//        $boards = DB::table('boards')->get();
//        return view('create', ['boards' => $boards]);

    }
}
