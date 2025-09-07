<?php
use App\Models\RefCor;

if ( ! function_exists('abreviarNome') ){
    function abreviarNome( $nome=null ) {
        if ( is_null($nome) ){
            return false;
        }
        $explode = explode(" ", $nome);
        return ( !isset($explode[1]) ) ? $explode[0]: $explode[0]." ".end($explode);
    }
}

if ( ! function_exists('removerAcentos') ){
    function removerAcentos($str) {
        $str = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
        $str = preg_replace('/[^a-zA-Z0-9]/', '', $str); // Substitui caracteres não-alfanuméricos por underscore
        return $str;
    }
}

if ( ! function_exists('formatDate') ){
    function formatDate($date)
    {
        $dateTime = DateTime::createFromFormat('d/m/Y', $date);
        if ($dateTime !== false) {
            return $dateTime->format('Y-m-d');
        }
        return null; // Retorna null se a data for inválida
    }
}

if ( ! function_exists('dataPTBR') ){
    function dataPTBR( $data=null ) {
        if ( is_null($data) || empty($data) ){
            return false;
        }
        return date('d/m/Y', strtotime($data));
    }
}

if ( ! function_exists('dataHoraPTBR') ){
    function dataHoraPTBR( $data=null ) {
        if ( is_null($data) ){
            return false;
        }
        return date('d/m/Y - H:i:s', strtotime($data));
    }
}

if ( ! function_exists('somenteNumeros') ){
    function somenteNumeros( $string=null ) {
        if ( is_null($string) ){
            return false;
        }
        return preg_replace('/[^0-9]/is', '', $string);
    }
}
if ( ! function_exists('formatarParaReal') ){
    function formatarParaReal($valor) {
        if ($valor === null || $valor === '') {
            return 'R$ 0,00';
        }

        $valorNumerico = floatval($valor);
        return 'R$ ' . number_format($valorNumerico, 2, ',', '.');
    }
}


if ( ! function_exists('formatarContador') ){
    function formatarContador( $contador=null, $ano=null ) {
        if ( is_null($contador) || is_null($ano) ){
            return false;
        }
        return str_pad($contador, 4, '0', STR_PAD_LEFT). '/' . $ano;
    }
}

if ( ! function_exists('getDataExtenso') ){
    function getDataExtenso( $data=null ) {
        if ( is_null($data) ){
            $data = date('Y-m-d');
        }

        $meses = array(
            1 => 'janeiro',
            2 => 'fevereiro',
            3 => 'março',
            4 => 'abril',
            5 => 'maio',
            6 => 'junho',
            7 => 'julho',
            8 => 'agosto',
            9 => 'setembro',
            10 => 'outubro',
            11 => 'novembro',
            12 => 'dezembro'
        );

        $dataParts = explode('-', $data);
        $ano = $dataParts[0];
        $mes = intval($dataParts[1]);
        $dia = intval($dataParts[2]);

        return $dia . ' de ' . $meses[$mes] . ' de ' . $ano;
    }
}



if ( ! function_exists('getIdNameTabela') ){
    function getIdNameTabela( $tabela=null ) {
        switch ($tabela) {
            case 'ref_categoria':
                return 'id_categoria';
                break;
            
            case 'ref_local':
                return 'id_local';
                break;
        
            case 'ref_motivo_entrada':
                return 'id_motivo_entrada';
                break;

            case 'ref_motivo_saida':
                return 'id_motivo_saida';
                break;

            case 'ref_cor':
                return 'id_cor';
                break;
                
            case 'tb_usuario':
                return 'id_usuario';
                break;
                
            default:
                return 'id';
                break;
        }
        
    }
}

if (!function_exists('getHexaCorProduto')) {
    function getHexaCorProduto($produto = null) {
        if (!$produto || is_null($produto) || !isset($produto['id_cor'])) {
            return false;
        }
        $refCor = (new RefCor())->find($produto['id_cor']);
        return $refCor['hexadecimal'] ?? '';
    }
}

if (!function_exists('getNomeCorProduto')) {
    function getNomeCorProduto($produto = null) {
        if (!$produto || is_null($produto) || !isset($produto['id_cor'])) {
            return false;
        }
        $refCor = (new RefCor())->find($produto['id_cor']);
        return $refCor['nome'] ?? '';
    }
}

/**
 * Valida se a quantidade informada não ultrapassa o saldo disponível do produto.
 *
 * @param int $id_produto
 * @param int $quantidadeInformada
 * @return bool|string  Retorna true se válido ou uma mensagem de erro (string) se inválido
 */
if (!function_exists('validarQuantidadeSaida')) {
    function validarQuantidadeSaida($id_produto, $quantidadeInformada)
    {
        $TbProduto = new \App\Models\TbProduto();

        $produto = $TbProduto
            ->select('nome, json, fn_saldo_estoque(id_produto) AS saldo')
            ->where('id_produto', $id_produto)
            ->first();

        if (!$produto) {
            return  ['title' => 'Falha ao registrar saída', 'text' => 'Produto não encontrado.'];
        }

        $saldoAtual = $produto['saldo'];

        if ($quantidadeInformada > $saldoAtual) {
            $produtoNome = '<span style="display:inline-block;width:15px;height:15px;background-color:'.getHexaCorProduto($produto).';margin-right:5px;border:1px solid #000;border-radius:3px;"></span> '.$produto['nome']. " (".getNomeCorProduto($produto).") ";

            return  [
                        'title' => "Quantidade indisponível<br>Falha ao registrar saída do produto:<br>$produtoNome", 
                        'text' => "Quantidade Informada: $quantidadeInformada<br>Saldo atual: $saldoAtual",
                    ];
        }

        return true;
    }
}