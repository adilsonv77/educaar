<?php

namespace App\Http\Controllers;

use App\Models\Painei;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PainelController extends Controller
{
    /*
    Dentro do banco de dados apenas um json é guardado com todas as informações necessárias.
    Isso torna o sistema mais flexível a expanções futuras.
    Template JSON:
    {
        "id": (definido pelo SGBD),
        "txtSuperior": "Texto em cima da midia",
        "txtInferior": "Texto abaixo da mida",
        "link": "https://www.youtube.com/watch?v=4YEypDiRyS4",
        "midia": "img",
        "btn1Icon": 24,
        "btn1Function": 10,
        "btn2Icon": 2,
        "btn2Function": 1,
        "btn2Param": 13,
        "btn4Icon": 5,
        "btn4Function": 17,
        "btn2Param": "15,25",
    }
    */

    public function index()
    {
        // $paineis = Painei::all();
        // dd($paineis);
        return view('pages.pasta.criacaoPaineis');
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $baseFileName = time();

        $imgFile = $baseFileName . '.' . $request->img->getClientOriginalExtension();
        $request->img->move(public_path('midiasPainel'), $imgFile);
        $data['midiasPainel'] = $imgFile;

        //Transforma dados puros do form em json
        $json = "{";
        foreach ($data as $key => $value) {
            //Coisas que não devem ser salvas no JSON
            if($key != "_token" && $key != "midiasPainel" && $key != "midia")
            $json= $json."\"".$key."\": \"".$value."\",";
        }
        $json = ["panel"=>$json." \"midiaExtension\": \"".$request->img->getClientOriginalExtension()."\"}"];

        //Criar o painel e salvo o dado
        $painel = Painei::create($json);

        $data['midiasPainel'] = $painel->id . '.' . $request->img->getClientOriginalExtension();
        $public_path = public_path('midiasPainel');

        rename($public_path . '/' . $imgFile, $public_path . '/' . $data['midiasPainel']);
        
        $painel->update($data);
        return redirect()->route('paineis.create');
    }
}
