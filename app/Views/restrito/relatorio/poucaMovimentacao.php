<?= $this->extend('restrito/template/principal') ?>
<?= $this->section('conteudo') ?>
<?= $this->include('_componentes/form/ref_categoria_tb_produtos') ?>
<?= view('restrito/relatorio/_posicao', ['modo' => 'giro', 'produtos' => $produtos, 'periodoValido' => $periodoValido], ['saveData' => false]) ?>
<?= $this->endSection() ?>
<?= $this->section('js') ?>
<script src="/assets/js/estoque-relatorios.js"></script>
<?= $this->endSection() ?>
