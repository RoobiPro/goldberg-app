<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TableController extends Controller
{
    public function index(Request $req){
      return getTableHeaders($req->tablename);
    }
}
