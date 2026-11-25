<?php if (session()->has('show_toast')): ?>
<script>
    showToast(
        <?= json_encode(session()->getFlashdata('title') ?? 'Atenção!', JSON_HEX_TAG | JSON_INVALID_UTF8_SUBSTITUTE) ?>,
        <?= json_encode(session()->getFlashdata('text') ?? '', JSON_HEX_TAG | JSON_INVALID_UTF8_SUBSTITUTE) ?>,
        <?= json_encode(session()->getFlashdata('type') ?? 'default', JSON_HEX_TAG | JSON_INVALID_UTF8_SUBSTITUTE) ?>
    );
</script>
<?php endif ?>