(() => {
    const formulario = document.getElementById('movimentacao-individual');
    const campo = formulario.elements.quantidade;
    const resumo = document.getElementById('saldo-previsto');
    const saldoAtual = Number(formulario.dataset.saldo);
    const quantidadeAnterior = Number(formulario.dataset.anterior);
    const sinal = formulario.dataset.tipo === 'saida' ? -1 : 1;
    const unidades = new Intl.NumberFormat('pt-BR');

    function atualizarSaldo() {
        const quantidade = campo.valueAsNumber;
        const quantidadeValida = Number.isInteger(quantidade) && campo.validity.valid;
        const previsto = saldoAtual + sinal * (quantidade - quantidadeAnterior);
        resumo.textContent = quantidadeValida ? 'Saldo após salvar: ' + unidades.format(previsto) + ' un.' : '';
        resumo.classList.toggle('text-danger', quantidadeValida && previsto < 0);
    }

    campo.addEventListener('input', atualizarSaldo);
    atualizarSaldo();
})();
