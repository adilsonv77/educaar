<?php

namespace App\Http\Controllers;

use App\DAO\PainelDAO;
use App\DAO\ActivityDAO;
use Illuminate\Http\Request;

class PainelController extends Controller
{
    protected $painelDAO;
    protected $activityDAO;

    public function __construct(PainelDAO $painelDAO, ActivityDAO $activityDAO)
    {
        $this->activityDAO = $activityDAO;
        $this->painelDAO = $painelDAO;
    }

    public function index()
    {
        
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
        // 1. Preparação geral
        // Pega os dados, cria um nome temporário pro arquivo, e pega ação
        $data = $request->all();
        $baseFileName = time();
        $action = $data['action'];
        $arquivoRecebido = !empty($request->arquivoMidia);
        $possuiLinkYoutube = !empty($data['link']);
        $publicPath = public_path('midiasPainel');
        if($action == "edit"){
            $data["arquivoMidia"] = $data["arquivoMidiaEdit"];
            $midiaExtension = $data["midiaExtensionEdit"];
        }

        // 2. Criar arquivo temporario
        //Se tiver um arquivo ele cria um nome temporário
        if ($arquivoRecebido) {
            $midiaExtension = $request->arquivoMidia->getClientOriginalExtension(); //Pega a extensão do arquivo enviado
            $nomeTemporario = $baseFileName . '.' . $midiaExtension; //Nome temporário pro arquivo (tempo atual).(extensão)
            $request->arquivoMidia->move(public_path('midiasPainel'), $nomeTemporario); //Cria arquivo
        }else if($possuiLinkYoutube){
            $midiaExtension = "";
            $data["arquivoMidia"] = "";
        }

        $data['midiaExtension'] = $midiaExtension;
        unset($data['_token'], $data['midia'], $data['midiasPainel'], $data['action']);

        // 3. Cadastro de painel, definição do id do painel, e exclusão da midia já existente se necessário
        if ($action == 'create') {
            //Cadastro
            //Manda o DAO criar o painel e após criar pega o id do painel criado
            $painel = $this->painelDAO->create(["panel" => json_encode($data)]);
            $id = $painel->id; //Só se ganha um id após criar o registro, e ele é usado para nomear arquivos logo abaixo
            $data['id'] = $id; //Guarda o valor para jogar no banco depois
        } else {
            //Edição
            //Pega o id do painel sendo editado para pegar a extensão original do arquivo já registrado
            $id = $data['id'];
            $painel = $this->painelDAO->getById($id);
            $originalExtension = json_decode($painel->panel)->midiaExtension;
            if($originalExtension != "" && ($possuiLinkYoutube || $arquivoRecebido)){
                unlink($publicPath . '/' . $id . '.' . $originalExtension); //Deleta o arquivo que originalmente estava lá
            }
        }

        // 4. Mudar o nome do arquivo cadastrado
        //Com o painel já criado ou selecionado, temos o id estabelecido, ent ele cria o nome REAL do arquivo (id).(extensão)
        if($arquivoRecebido){
            $data['arquivoMidia'] = $id . '.' . $midiaExtension;
            rename($publicPath . '/' . $nomeTemporario, $publicPath . '/' . $data['arquivoMidia']); //Na criação ele renomeia (nome temporário)->(nome real)
        }

        //Atualiza o painel com o nome REAL (criação) | Atualiza todo o json cadastrado (edição)
        $this->painelDAO->updateById($id, ["panel" => json_encode($data)]);

        return redirect()->route('paineis.index');
    }

    public function destroy($id)
    {
        $this->painelDAO->deleteById($id);
        return redirect()->route('paineis.index');
    }

    public function conexoes()
    {
        $paineis = $this->painelDAO->getAll();
        //dd($paineis); 
        return view('pages.painel.conexoes', compact('paineis'));
    }


}
