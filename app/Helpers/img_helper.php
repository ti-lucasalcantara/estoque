<?php

if ( ! function_exists('uploadImagem') ){
    function uploadImagem( $requestImagem=null, $path='uploads/' ) {
        $imagem = $requestImagem;

        if ($imagem && $imagem->isValid() && !$imagem->hasMoved()) {

            $novoNome = $imagem->getRandomName();
            $caminhoSalvar = FCPATH . $path;

            // Garante que a pasta existe
            if (!is_dir($caminhoSalvar)) {
                mkdir($caminhoSalvar, 0755, true);
            }

            // Move o arquivo para a pasta
            $imagem->move($caminhoSalvar, $novoNome);

            // Monta a URL para retornar
            $urlImagem = base_url($path . $novoNome);

            return $urlImagem;
        } else {
            return false;
        }

    }
}
