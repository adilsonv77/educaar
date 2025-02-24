<?php

namespace App\Http\Controllers;



use Illuminate\Http\Request;



class ReceiveMindController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function receive(Request $request)
    {

        $request->file->move(public_path('mind'), $request->content_id.".mind");
      
     }

    
}
