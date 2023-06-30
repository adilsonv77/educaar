<?php

namespace App\Http\Controllers;



use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\DAO\ContentDAO;
use App\DAO\ActivityDAO;

class ARController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

      
        return view('pages.ar.index');
    }

    
}
