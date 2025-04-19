<div class="modal fade" id="modalRefCategoria" data-bs-backdrop="static"
    data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalRefCategoriaLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="modalRefCategoriaLabel">Criar Categoria</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">x</button>
            </div>
            <div class="modal-body">
                <form id="formRefCategoria" method="POST">
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="modal_ref_categoria_nome" class="form-label">*Categoria</label>
                            <input type="text" class="form-control" id="modal_ref_categoria_nome" name="modal_ref_categoria_nome" placeholder="Informe o nome da categoria">
                            <small class="text-danger pull-right w-100" style="text-align:right" id="response_modal_ref_categoria_nome"></small>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal"><i class="fa fa-remove"></i> Fechar</button>
                <button type="button" id="sendFormRefCategoria" class="btn btn-primary"><i class="fa fa-check"></i> Salvar</button>
            </div>
        </div>
    </div>
</div>


<?= $this->section('js') ?>
<script>
$("#formRefCategoria").submit(async function(e){
    e.preventDefault();
    //$("#sendFormRefCategoria").loading();

    $("#modal_ref_categoria_nome").removeClass('is-invalid');
    $("#response_modal_ref_categoria_nome").html('');

    var dados = new FormData();
    dados.append('categoria', $("#modal_ref_categoria_nome").val() );

    var retorno = await executarAjax(`<?=url_to('restrito.refCategoria.salvar')?>`, dados, method='POST', debug=false);
    
    if(retorno.validation_error){
        $("#modal_ref_categoria_nome").addClass('is-invalid');
        $("#response_modal_ref_categoria_nome").html(retorno.validation_error.categoria)
    }
    
    if(retorno.type == 'success'){

    }

    console.log('retorno', retorno);

});

$("#sendFormRefCategoria").click(function(e){
    $("#formRefCategoria").submit();
});




</script>
<?= $this->endSection() ?>
