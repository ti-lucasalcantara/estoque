<?php

namespace App\Controllers\Restrito;
use App\Models\RefCategoria as RefCategoriaModal;

class RefCategoria extends \App\Controllers\BaseController
{
    public function salvar()
    {
        if ( ! $this->request->is('post') ) {
            return $this->response->setJSON( getMessageFail() )->setStatusCode(422);
        }else{

            $rules = [
                'categoria' => 'required|max_length[255]',
            ];

            $messages   = [
                'categoria' => [
                    'required' => 'Campo obrigatório.',
                    'max_length' => 'A quantidade de caracteres informada está maior que o permitido.',
                ],
            ];

            $validation = \Config\Services::validation();
            $validation->setRules($rules, $messages);

            if (! $validation->run($this->request->getPost()) ) {
                return $this->response->setJSON( ['validation_error' => $validation->getErrors() ] )->setStatusCode(200);
            }

            $categoria = $this->request->getPost('categoria');

            $ref_categoria = [
                'categoria' => $categoria,
            ];

            $RefCategoriaModal = new RefCategoriaModal();

            $save = $RefCategoriaModal->save($ref_categoria);

            if(!$save){
                return $this->response->setJSON( getMessageFail() )->setStatusCode(200);
            }

            return $this->response->setJSON( getMessageSucess() )->setStatusCode(200);
        }
    }
}