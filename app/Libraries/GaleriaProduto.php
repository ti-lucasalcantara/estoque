<?php

namespace App\Libraries;

use App\Models\TbProdutoImagem;
use CodeIgniter\Database\BaseConnection;
use CodeIgniter\HTTP\Files\UploadedFile;
use DomainException;
use RuntimeException;

class GaleriaProduto
{
    public const ENQUADRAMENTO = ['zoom' => 100, 'x' => 50, 'y' => 50];

    public static function enquadramento($dados): array
    {
        $dados = is_array($dados) ? $dados : [];
        $ajustes = [];
        foreach (self::ENQUADRAMENTO as $campo => $padrao) {
            $valor = filter_var($dados[$campo] ?? $padrao, FILTER_VALIDATE_INT);
            $ajustes[$campo] = $valor === false ? $padrao : max(
                $campo === 'zoom' ? 100 : 0,
                min($campo === 'zoom' ? 250 : 100, $valor)
            );
        }
        return $ajustes;
    }

    public static function preparar(array $arquivos): array
    {
        $fotos = [];
        foreach ($arquivos as $indice => $arquivo) {
            if (!$arquivo instanceof UploadedFile || $arquivo->getError() === UPLOAD_ERR_NO_FILE) {
                continue;
            }
            if (!$arquivo->isValid() || $arquivo->getSize() > 8 * 1024 * 1024) {
                throw new DomainException('Selecione fotos de até 8 MB por arquivo.');
            }
            $tipo = @getimagesize($arquivo->getTempName())[2] ?? null;
            $extensao = match ($tipo) {
                IMAGETYPE_JPEG => 'jpg',
                IMAGETYPE_PNG => 'png',
                IMAGETYPE_WEBP => 'webp',
                default => null,
            };
            if ($extensao === null) {
                throw new DomainException('Use fotos em formato JPG, PNG ou WebP.');
            }
            $fotos[$indice] = [$arquivo, $extensao];
        }
        return $fotos;
    }

    public static function armazenar(array $fotos, array &$caminhos): array
    {
        $urls = [];
        foreach ($fotos as $indice => [$arquivo, $extensao]) {
            $nome = bin2hex(random_bytes(16)) . '.' . $extensao;
            $relativo = 'uploads/produto/' . $nome;
            $caminhos[] = FCPATH . $relativo;
            $arquivo->move(FCPATH . 'uploads/produto', $nome);
            $urls[$indice] = base_url($relativo);
        }
        return $urls;
    }

    public static function salvar(BaseConnection $db, int $produto, array $urls, array $galeria): void
    {
        $model = new TbProdutoImagem($db);
        $atuais = array_column($model->where('id_produto', $produto)->orderBy('principal', 'DESC')
            ->orderBy('id_produto_imagem')->findAll(), null, 'id_produto_imagem');

        foreach ((array) ($galeria['removidas'] ?? []) as $id) {
            if (is_int($id) && isset($atuais[$id])) {
                if (!$model->delete($id)) {
                    throw new RuntimeException('Não foi possível remover a foto.');
                }
                unset($atuais[$id]);
            }
        }

        $capasNovas = [];
        foreach ($urls as $indice => $url) {
            $id = $model->insert([
                'id_produto' => $produto,
                'imagem' => $url,
                'thumb' => $url,
                'principal' => 0,
                'json' => json_encode(['enquadramento' => self::enquadramento($galeria['novas'][$indice] ?? null)]),
            ]);
            if (!$id) {
                throw new RuntimeException('Não foi possível adicionar a foto.');
            }
            $capasNovas[$indice] = (int) $id;
        }

        foreach ($atuais as $id => $foto) {
            if (isset($galeria['existentes'][$id])) {
                $dados = (array) json_decode($foto['json'] ?? '{}', true);
                $dados['enquadramento'] = self::enquadramento($galeria['existentes'][$id]);
                if (!$model->update($id, ['json' => json_encode($dados, JSON_THROW_ON_ERROR)])) {
                    throw new RuntimeException('Não foi possível salvar o enquadramento.');
                }
            }
        }

        $capa = $galeria['capa'] ?? [];
        $indice = $capa['id'] ?? null;
        $principal = is_int($indice) ? match ($capa['tipo'] ?? '') {
            'existente' => isset($atuais[$indice]) ? $indice : null,
            'nova' => $capasNovas[$indice] ?? null,
            default => null,
        } : null;
        $principal ??= array_key_first($atuais) ?? reset($capasNovas);

        if ($principal) {
            self::definirCapa($db, $produto, $principal);
        }
    }

    public static function definirCapa(BaseConnection $db, int $produto, int $foto): void
    {
        $model = new TbProdutoImagem($db);
        if (!$model->where('id_produto', $produto)->find($foto)) {
            throw new DomainException('A foto selecionada não está mais disponível neste produto.');
        }
        if (!$model->where('id_produto', $produto)->set('principal', 0)->update()
            || !$model->update($foto, ['principal' => 1])) {
            throw new RuntimeException('Não foi possível salvar a foto de capa.');
        }
    }
}
