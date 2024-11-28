<?php

namespace App\Http\Controllers;

use App\DAO\PainelDAO;
use Illuminate\Http\Request;

class PainelController extends Controller
{
    protected $painelDAO;

    public function __construct(PainelDAO $painelDAO)
    {
        $this->painelDAO = $painelDAO;
    }

    public function create()
    {
        return view('pages.painel.criacaoPaineis', [
            'titulo' => 'Criação de painéis',
            'action' => 'create',
            'midiaExtension' => ''
        ]);
    }

    public function edit($id)
    {
        $painel = $this->painelDAO->getById($id);
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
        $midiaExtension = "";

        if ($action == 'edit' && empty($request->arquivoMidia)) {
            $painel = $this->painelDAO->getById($data['id']);
            $painelData = json_decode($painel->panel);
            $midiaExtension = $painelData->midiaExtension;
        } else {
            $midiaExtension = $request->arquivoMidia->getClientOriginalExtension();
        }

        $imgFile = $baseFileName . '.' . $midiaExtension;
        if ($action != 'edit' || !empty($request->arquivoMidia)) {
            $request->arquivoMidia->move(public_path('midiasPainel'), $imgFile);
        }
        $data['midiasPainel'] = $imgFile;

        $data['midiaExtension'] = $midiaExtension;
        unset($data['_token'], $data['midia'], $data['midiasPainel'], $data['action']);

        $json = ["panel" => json_encode($data)];
        $id = -1;

        if ($action == 'create') {
            $painel = $this->painelDAO->create($json);
            $id = $painel->id;
            $data['id'] = $id;
        } else {
            $id = $data['id'];
            $painel = $this->painelDAO->getById($id);
            $originalExtension = json_decode($painel->panel)->midiaExtension;
        }

        $data['arquivoMidia'] = $id . '.' . $midiaExtension;
        $publicPath = public_path('midiasPainel');

        if ($action == 'edit' && !empty($request->arquivoMidia)) {
            unlink($publicPath . '/' . $id . '.' . $originalExtension);
        }
        if ($action != 'edit' || !empty($request->arquivoMidia)) {
            rename($publicPath . '/' . $imgFile, $publicPath . '/' . $data['arquivoMidia']);
        }

        $this->painelDAO->updateById($id, ["panel" => json_encode($data)]);

        return redirect()->route('paineis.create');
    }

    public function destroy($id)
    {
        dd($id);
    }
}
