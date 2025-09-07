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

if ( ! function_exists('copiarImagemTempParaProduto') ){
    /**
     * Copia a imagem temporária para a pasta do produto
     * @param string $url_imagem Caminho da imagem temporária
     * @param int $id_produto ID do produto
     * @return string|false Novo caminho da imagem ou false em caso de erro
     */
    function copiarImagemTempParaProduto($url_imagem, $id_produto)
    {
        // Se vier uma URL, extrai apenas o caminho relativo
        if (filter_var($url_imagem, FILTER_VALIDATE_URL)) {
            $parsed = parse_url($url_imagem);
            $url_imagem = ltrim($parsed['path'], '/');
        }
        $origem = FCPATH . $url_imagem;
        $destinoDir = FCPATH . "uploads/produto/$id_produto/";
        if (!is_dir($destinoDir)) {
            mkdir($destinoDir, 0777, true);
        }
        $nomeArquivo = basename($url_imagem);
        $destino = $destinoDir . $nomeArquivo;
        if (copy($origem, $destino)) {
            return base_url("uploads/produto/$id_produto/" . $nomeArquivo);
        }
        return false;
    }
}

if ( ! function_exists('limparPastaTemp') ){
    /**
     * Remove todos os arquivos da pasta public/temp/
     * @return void
     */
    function limparPastaTemp()
    {
        $tempDir = FCPATH . 'temp/';
        if (is_dir($tempDir)) {
            $arquivos = glob($tempDir . '*');
            foreach ($arquivos as $arquivo) {
                if (is_file($arquivo)) {
                    unlink($arquivo);
                }
            }
        }
    }
}
