<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class UsersController extends Controller
{
    public function index() {
       
         return DB::connection('mysql')
            ->table('users')
            ->get();
    }
}
