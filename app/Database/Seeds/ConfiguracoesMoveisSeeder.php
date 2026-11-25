<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use RuntimeException;
use Throwable;

class ConfiguracoesMoveisSeeder extends Seeder
{
    public function run(): void
    {
        if (!$this->db->transBegin()) {
            throw new RuntimeException('Não foi possível iniciar a carga das configurações.');
        }

        try {
            $this->inserirNovos('ref_categoria', 'categoria', $this->registros('categoria', [
                'Sofá', 'Poltrona', 'Pufe', 'Cama', 'Colchão', 'Base box', 'Cabeceira',
                'Guarda-roupa', 'Cômoda', 'Mesa de cabeceira', 'Mesa de jantar', 'Cadeira',
                'Rack', 'Painel para TV', 'Estante', 'Buffet',
            ]));

            $cores = [
                'Branco' => '#FFFFFF',
                'Off-white' => '#F5F1E8',
                'Preto' => '#252525',
                'Cinza' => '#808080',
                'Grafite' => '#45484D',
                'Bege' => '#D5C1A4',
                'Areia' => '#C9AD8D',
                'Caramelo' => '#AD6F3B',
                'Marrom' => '#654534',
                'Azul-marinho' => '#233956',
                'Verde-oliva' => '#696C48',
                'Carvalho' => '#BA9168',
                'Nogueira' => '#73513D',
                'Freijó' => '#AD8057',
                'Amêndoa' => '#BA9675',
            ];
            $this->inserirNovos('ref_cor', 'nome', array_map(
                static fn (string $nome, string $hexadecimal): array => ['nome' => $nome, 'hexadecimal' => $hexadecimal],
                array_keys($cores),
                array_values($cores),
            ));

            $caracteristicas = [
                'linha' => 'Linha / coleção',
                'material' => 'Material principal',
                'acabamento' => 'Acabamento',
                'largura' => 'Largura (cm)',
                'altura' => 'Altura (cm)',
                'profundidade' => 'Profundidade (cm)',
                'comprimento' => 'Comprimento (cm)',
                'revestimento' => 'Revestimento',
                'numero_de_lugares' => 'Número de lugares',
                'tipo_de_sofa' => 'Tipo de sofá',
                'tamanho_do_colchao' => 'Tamanho do colchão',
                'tipo_de_colchao' => 'Tipo de colchão',
                'densidade_da_espuma' => 'Densidade da espuma',
                'firmeza' => 'Firmeza do colchão',
                'peso_suportado' => 'Peso suportado (kg)',
                'garantia' => 'Garantia (meses)',
            ];
            $this->inserirNovos('ref_caracteristica', 'caracteristica', array_map(
                static fn (string $chave, string $rotulo): array => ['caracteristica' => $chave, 'label' => $rotulo],
                array_keys($caracteristicas),
                array_values($caracteristicas),
            ));

            $this->inserirNovos('ref_motivo_entrada', 'motivo_entrada', $this->registros('motivo_entrada', [
                'Compra de fornecedor', 'Devolução de cliente', 'Retorno de assistência técnica',
                'Entrada por troca', 'Saldo inicial', 'Ajuste positivo de inventário',
            ]));
            $this->inserirNovos('ref_motivo_saida', 'motivo_saida', $this->registros('motivo_saida', [
                'Venda', 'Devolução ao fornecedor', 'Saída por troca', 'Envio para assistência técnica',
                'Baixa por avaria', 'Perda ou extravio', 'Ajuste negativo de inventário',
            ]));

            if (!$this->db->transStatus() || !$this->db->transCommit()) {
                throw new RuntimeException('Não foi possível concluir a carga das configurações.');
            }
        } catch (Throwable $erro) {
            $this->db->transRollback();
            throw $erro;
        }
    }

    private function registros(string $campo, array $valores): array
    {
        return array_map(static fn (string $valor): array => [$campo => $valor], $valores);
    }

    private function inserirNovos(string $tabela, string $campo, array $registros): void
    {
        $novos = [];
        foreach ($registros as $registro) {
            $existe = $this->db->table($tabela)->where($campo, $registro[$campo])->countAllResults();
            if (!$existe) {
                $novos[] = $registro;
            }
        }

        if ($novos && $this->db->table($tabela)->insertBatch($novos) !== count($novos)) {
            throw new RuntimeException('Não foi possível preencher a tabela ' . $tabela . '.');
        }
    }
}
