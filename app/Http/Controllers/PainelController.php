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
        "midiaExtension": ".png"
    }
    */

    public function create()
    {
        return view('pages.painel.criacaoPaineis', ['titulo' => 'Criação de painéis', 'action' => 'create']);
    }

    public function edit($id)
    {
        $painel = Painei::where('id', $id)->first();
        if (!empty($painel)) {
            $data = json_decode($painel->panel, true);
            $data['titulo'] = 'Edição de painel';
            $data['action'] = 'edit';
            $data['id'] = $id;

            return view('pages.painel.criacaoPaineis', $data);
        }
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $baseFileName = time();
        $action = $data['action'];

        $imgFile = $baseFileName . '.' . $request->arquivoMidia->getClientOriginalExtension();
        $request->arquivoMidia->move(public_path('midiasPainel'), $imgFile);
        $data['midiasPainel'] = $imgFile;

        //Remove os dados desnecessários para serem guardados
        $data['midiaExtension'] = $request->arquivoMidia->getClientOriginalExtension();
        unset($data['_token']);
        unset($data['midia']);
        unset($data['midiasPainel']);
        unset($data['action']);

        $json = ["panel" => json_encode($data)];

        //Criar o painel e salvo o dado
        if ($action == 'create') {
            $painel = Painei::create($json);
            $id = $painel->id;
        } else {
            $id = $data['id'];
            unset($data['id']);
            $painel = Painei::where('id', $id)->update($json);
        }
        $data['midiasPainel'] = $id . '.' . $request->arquivoMidia->getClientOriginalExtension();
        $public_path = public_path('midiasPainel');
        if($action == 'edit') unlink($public_path . '/' . $data['midiasPainel']);
        rename($public_path . '/' . $imgFile, $public_path . '/' . $data['midiasPainel']);
        // $painel->update($data);
        return redirect()->route('paineis.create');
    }

    public function destroy($id){
        dd($id);
    }

}
