<?php
use App\Models\RefCategoria;
use App\Models\TbProduto;

$RefCategoria = (new RefCategoria())->orderBy('categoria', 'ASC')->findAll();

$TbProduto = (new TbProduto())
    ->select('tb_produto.*, ref_categoria.categoria, fn_saldo_estoque(tb_produto.id_produto) AS saldoEstoque')
    ->join('ref_categoria', 'ref_categoria.id_categoria = tb_produto.id_categoria')
    ->orderBy('saldoEstoque', 'DESC')
    ->findAll();

foreach ($TbProduto as $key => $produto) {
    $TbProduto[$key]['cor_nome'] = getNomeCorProduto($produto);
    $TbProduto[$key]['cor_hexa'] = getHexaCorProduto($produto);
}

$categoriasSelecionadas = $_GET['ref_categoria'] ?? [];
$produtosSelecionados = $_GET['tb_produtos'] ?? [];
?>

<!-- Categorias -->
<div class="col-md-12">
    <label for="ref_categoria" class="form-label">Categoria(s)</label>
    <select name="ref_categoria[]" id="ref_categoria" class="form-control" multiple required>
        <option value="__all__" <?= in_array('__all__', $categoriasSelecionadas) ? 'selected' : '' ?>>Todos</option>
        <?php foreach ($RefCategoria as $categoria): ?>
            <option value="<?= $categoria['id_categoria'] ?>"
                <?= in_array($categoria['id_categoria'], $categoriasSelecionadas) ? 'selected' : '' ?>>
                <?= esc($categoria['categoria']) ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>

<!-- Produtos -->
<div class="col-md-12">
    <label for="tb_produtos" class="form-label">Produto(s)</label>
    <select name="tb_produtos[]" id="tb_produtos" class="form-control" multiple required>
        <option value="__all__" <?= in_array('__all__', $produtosSelecionados) ? 'selected' : '' ?>>Todos</option>
    </select>
</div>

<?= $this->section('js') ?>
<script>
const produtosData = <?= json_encode($TbProduto) ?>;
const produtosSelecionados = <?= json_encode($produtosSelecionados) ?>;
const categoriasSelecionadas = <?= json_encode($categoriasSelecionadas) ?>;

// Inicializa Select2: categorias simples, produtos com template
$('#ref_categoria').select2({
    width: '100%',
    placeholder: 'Selecione uma ou mais opções'
});

$('#tb_produtos').select2({
    width: '100%',
    placeholder: 'Selecione uma ou mais opções',
    templateResult: formatSelectOption,
    templateSelection: formatSelectSelection
});

// Formatação visual no Select2 (produtos: lista de opções)
function formatSelectOption(data) {
    if (!data.id) return data.text;
    const $option = $(data.element);
    const nome = $option.data('nome') || data.text;
    const codigo = $option.data('codigo') || '';
    const cor = $option.data('cor') || '';
    const hexa = $option.data('hexa') || '';

    if (!hexa) return nome;

    return $(`
        <span>
            <span style="display:inline-block;width:15px;height:15px;background-color:${hexa};margin-right:5px;border:1px solid #000;border-radius:3px;"></span>
            ${nome} (${codigo}) - ${cor}
        </span>
    `);
}

// Formatação visual no Select2 (produtos: seleção ativa)
function formatSelectSelection(data) {
    if (!data.id) return data.text;
    const $option = $(data.element);
    const nome = $option.data('nome') || data.text;
    const codigo = $option.data('codigo') || '';
    const cor = $option.data('cor') || '';

    return `${nome} (${codigo}) - ${cor}`;
}

// Expandir "__all__" para todos
function expandirSelectAll($select) {
    const todosIds = $select.find('option').map(function () {
        const val = $(this).val();
        return val !== '__all__' ? val : null;
    }).get();
    $select.val(todosIds).trigger('change');
}

// Seleção de categorias e produtos
$('#ref_categoria').on('select2:select', function (e) {
    if (e.params.data.id === '__all__') expandirSelectAll($('#ref_categoria'));
});
$('#tb_produtos').on('select2:select', function (e) {
    if (e.params.data.id === '__all__') expandirSelectAll($('#tb_produtos'));
});

// Carregar produtos com base nas categorias selecionadas
function carregarProdutosPorCategoria() {
    const $produtos = $('#tb_produtos');
    const categorias = $('#ref_categoria').val() || [];

    $produtos.empty().append('<option value="__all__">Todos</option>');

    if (categorias.length === 0) {
        $produtos.val(null).trigger('change');
        return;
    }

    if (categorias.includes('__all__')) {
        produtosData.forEach(prod => {
            const isSelecionado = produtosSelecionados.includes(prod.id_produto.toString());
            $produtos.append(new Option(prod.nome, prod.id_produto, false, isSelecionado))
                .find(`option[value="${prod.id_produto}"]`)
                .attr('data-nome', prod.nome)
                .attr('data-codigo', prod.codigo)
                .attr('data-cor', prod.cor_nome)
                .attr('data-hexa', prod.cor_hexa);
        });
        $produtos.trigger('change');
        return;
    }

    const selecionadosValidos = [];
    produtosData.forEach(prod => {
        if (categorias.includes(prod.id_categoria.toString())) {
            const isSelecionado = produtosSelecionados.includes(prod.id_produto.toString());
            $produtos.append(new Option(prod.nome, prod.id_produto, false, isSelecionado))
                .find(`option[value="${prod.id_produto}"]`)
                .attr('data-nome', prod.nome)
                .attr('data-codigo', prod.codigo)
                .attr('data-cor', prod.cor_nome)
                .attr('data-hexa', prod.cor_hexa);
            if (isSelecionado) selecionadosValidos.push(prod.id_produto.toString());
        }
    });

    $produtos.val(selecionadosValidos).trigger('change');
}

// Evento: ao alterar categorias
$('#ref_categoria').on('change', function () {
    carregarProdutosPorCategoria();
});

// Ao carregar a página
$(document).ready(function () {
    produtosData.forEach(p => {
        p.cor_nome = p.cor_nome || '-';
        p.cor_hexa = p.cor_hexa || '#cccccc';
    });
    carregarProdutosPorCategoria();
});
</script>
<?= $this->endSection() ?>