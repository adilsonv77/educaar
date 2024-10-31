<?php

namespace App\Http\Controllers;

use App\Models\Painei;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PainelController extends Controller
{

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
        $midiaExtension = $request->arquivoMidia->getClientOriginalExtension();

        $imgFile = $baseFileName . '.' . $midiaExtension;
        $request->arquivoMidia->move(public_path('midiasPainel'), $imgFile);
        $data['midiasPainel'] = $imgFile;

        //Remove os dados desnecessários para serem guardados e adiciona necessários
        $data['midiaExtension'] = $midiaExtension;
        $data['arquivoMidia'] = $data['id'].'.'.$midiaExtension;
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
            $painel = Painei::where('id', $id);
            //Pega a extenção do arquivo já guardado no painel
            $originalExtension = json_decode($painel->first()->panel)->midiaExtension;
            $painel->update($json);
        }
        $data['midiasPainel'] = $id . '.' . $midiaExtension;
        $public_path = public_path('midiasPainel');
        if($action == 'edit') unlink($public_path . '/' . $id.'.'.$originalExtension);
        rename($public_path . '/' . $imgFile, $public_path . '/' . $data['midiasPainel']);
        // $painel->update($data);
        return redirect()->route('paineis.create');
    }

    public function destroy($id){
        dd($id);
    }

}
