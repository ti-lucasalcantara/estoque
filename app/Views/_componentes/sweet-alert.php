<?php if (session()->has('show_sweetalert')): ?>
<?php
$tipo = session()->getFlashdata('type') ?? 'info';
$alerta = [
    'titleText' => session()->getFlashdata('title') ?? 'Atenção',
    'icon' => $tipo === 'danger' ? 'error' : $tipo,
    'text' => html_entity_decode(strip_tags(session()->getFlashdata('text') ?? ''), ENT_QUOTES, 'UTF-8'),
    'showCloseButton' => true,
    'showCancelButton' => (bool) session()->getFlashdata('showCancelButton'),
    'confirmButtonText' => session()->getFlashdata('confirmButtonText') ?? 'OK',
    'cancelButtonText' => session()->getFlashdata('cancelButtonText') ?? 'Cancelar',
];
$saldoInsuficiente = session()->getFlashdata('saldo_insuficiente');
if ($saldoInsuficiente) {
    ob_start();
    ?>
    <div class="stock-shortage">
        <p class="stock-shortage-product"><?= esc($saldoInsuficiente['produto']) ?></p>
        <dl class="stock-shortage-amounts">
            <div>
                <dt>Disponível</dt>
                <dd><?= number_format($saldoInsuficiente['disponivel'], 0, ',', '.') ?> <small>un.</small></dd>
            </div>
            <div>
                <dt>Solicitado</dt>
                <dd><?= number_format($saldoInsuficiente['solicitado'], 0, ',', '.') ?> <small>un.</small></dd>
            </div>
        </dl>
        <p class="text-muted mb-0">Reduza a quantidade solicitada para registrar a saída.</p>
    </div>
    <?php
    $alerta['html'] = ob_get_clean();
    unset($alerta['text']);
    $alerta['icon'] = 'warning';
}
?>
<script>
    $(function () {
        Swal.fire(<?= json_encode($alerta, JSON_HEX_TAG | JSON_INVALID_UTF8_SUBSTITUTE) ?>);
    });
</script>
<?php endif ?>
