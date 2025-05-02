<?php

namespace App\Controllers\Restrito;

use CodeIgniter\Controller;
use CodeIgniter\Database\BaseConnection;

class Config extends \App\Controllers\BaseController
{
    private $dados;
    protected $db;

    public function __construct(){
        $this->dados = array();
        $this->db = db_connect();
    }
    
    public function index()
    {
        $this->dados['configs'] = [
            ['titulo' => 'Gerenciar categorias', 'parametro' => 'ref_categoria', 'rota' => 'restrito.config.crud' ],
            ['titulo' => 'Gerenciar Localizações', 'parametro' => 'ref_local', 'rota' => 'restrito.config.crud' ],
            
            ['titulo' => 'Gerenciar Motivo Entrada', 'parametro' => 'ref_motivo_entrada', 'rota' => 'restrito.config.crud' ],
            ['titulo' => 'Gerenciar Motivo Saída', 'parametro' => 'ref_motivo_saida', 'rota' => 'restrito.config.crud' ],
            
        ];

        return view('restrito/config/index', $this->dados);
    }

    public function crud($titulo, $tabela, $id=null)
    {
        $titulo = base64_decode($titulo);
        $tabela = base64_decode($tabela);

        $query = $this->db->table($tabela)->get();
        $dados = $query->getResultArray();
        $campos = $this->db->getFieldNames($tabela);

        $camposVisiveis = array_filter($campos, function($campo) {
            $excluir = ['json', 'created_at', 'updated_at', 'deleted_at', 'user_created', 'user_updated', 'user_deleted'];
            return !in_array(strtolower($campo), $excluir);
        });

        if ($id && !empty($id)) {
            $id = base64_decode($id);
            $item = $this->db->table($tabela)->where(getIdNameTabela($tabela), $id)->get()->getRowArray();
        }

        return view('restrito/config/crud', [
            'titulo' => $titulo,
            'tabela' => $tabela,
            'dados' => $dados,
            'campos' => $campos,
            'camposVisiveis' => $camposVisiveis,
            'item' => $item ?? [],
        ]);
    }
    

    public function salvar()
    {
        $post = $this->request->getPost();
        
        $titulo = $post['titulo'];
        $tabela = $post['tabela'];
        $id     = $post['id'] ?? '';

        unset($post['titulo']);
        unset($post['tabela']);
        unset($post['id']);

        if (isset($id) && !empty($id)) {
            $save = $this->db->table($tabela)->where( getIdNameTabela($tabela) , $id)->update($post);
        } else {
            $save = $this->db->table($tabela)->insert($post);
        }

        if(!$save){
            session()->setFlashdata( getMessageFail() );
        }else{
            session()->setFlashdata( getMessageSucess() );
        }

        if (isset($id) && !empty($id)) {
            return redirect()->route('restrito.config.edit', [base64_encode($titulo),base64_encode($tabela),base64_encode($id)]);
        }

        return redirect()->route('restrito.config.crud', [base64_encode($titulo),base64_encode($tabela)]);
    }


    public function excluir() 
    {
        if ( ! $this->request->is('post') ) {
            return $this->response->setJSON( getMessageFail() )->setStatusCode(401);
        }else{
            if(! $this->db->table( $this->request->getPost('tabela') )->where(getIdNameTabela( $this->request->getPost('tabela') ) , $this->request->getPost('id') )->delete() ){
                return $this->response->setJSON( getMessageFail() )->setStatusCode(401);
            }else{
                return $this->response->setJSON( getMessageSucess() )->setStatusCode(200);
            }
        }
    }


}
