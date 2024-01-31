<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DeveloperController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $activities= DB::table('activities')
                    ->join('contents', 'activities.content_id', '=', 'contents.id')
                    ->join('content_developer','content_developer.content_id','=','contents.id')
                    ->where('content_developer.user_id',Auth::user()->id)
                    ->select('activities.*')
                    ->get();
        return view('developer.index', compact('activities'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    
    public function listDevs(Request $request){

        $data = $request->input('content');

        session()->put('content_id', $data);

        $devs = User::where('type', 'developer')->get();
        return view('developer.selectDevelopers', compact('devs'));
    }

    public function store(Request $request)
    {
        $data = $request->all();

        $content_id = session()->get('content_id');

        $devs= DB::table('users')
                    ->where('type', 'developer')
                    ->get();
        
        foreach($devs as $dev){
            if($data['dev'.$dev->id]==$dev->id){
                DB::table('content_developer')->insert([
                    'content_id' => $content_id,
                    'developer_id' => $dev->id
                ]);
            }
        }
        return redirect()->route('content.index');
    }

    public function edit()
    {
        //
    }

    public function destroy()
    {
        //
    }
}
