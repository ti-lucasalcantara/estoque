<script>
    $(document).on('click', '.modalExcluir', function (evento) {
        evento.preventDefault();
        const {idExcluir, tabelaExcluir, urlExcluir, mensagemExcluir, tituloExcluir, confirmarExcluir, csrfNome, csrfValor} = this.dataset;
        Swal.fire({
            title: tituloExcluir || 'Excluir registro?',
            text: mensagemExcluir || 'Confirme a exclusão deste registro.',
            icon: 'warning',
            showCancelButton: true,
            focusCancel: true,
            cancelButtonText: 'Cancelar',
            confirmButtonText: confirmarExcluir || 'Excluir',
            confirmButtonColor: '#a71c32',
            showLoaderOnConfirm: true,
            allowOutsideClick: () => !Swal.isLoading(),
            preConfirm: async () => {
                const dados = new FormData();
                dados.append('id', idExcluir);
                dados.append('tabela', tabelaExcluir || '');
                if (csrfNome) dados.append(csrfNome, csrfValor);
                try {
                    const resultado = await $.ajax({
                        url: urlExcluir,
                        method: 'POST',
                        data: dados,
                        dataType: 'json',
                        contentType: false,
                        processData: false
                    });
                    if (resultado.type !== 'success') {
                        Swal.showValidationMessage('Não foi possível excluir o registro.');
                        return false;
                    }
                    return resultado;
                } catch (erro) {
                    const mensagem = erro.responseJSON?.text || 'Não foi possível excluir o registro. Tente novamente.';
                    const texto = document.createElement('span');
                    texto.textContent = mensagem;
                    Swal.showValidationMessage(texto.innerHTML);
                    return false;
                }
            }
        }).then(async ({isConfirmed, value}) => {
            if (!isConfirmed) return;
            await Swal.fire({title: value.title || 'Registro excluído', text: value.text || '', icon: 'success'});
            window.location.reload();
        });
    });
</script>
