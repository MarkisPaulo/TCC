function buscarCEP() {
    // Obtém referências aos elementos do DOM
    const cepInput = document.getElementById('cep');
    const logradouroInput = document.getElementById('logradouro');
    const bairroInput = document.getElementById('bairro');
    const cidadeInput = document.getElementById('cidade');
    const ufInput = document.getElementById('uf');
    const enderecoInput = document.getElementById('endereco');

    // Verifica se todos os elementos existem
    if (!cepInput) {
        console.error('Campo CEP não encontrado');
        return;
    }

    // Função para limpar os campos de endereço
    function limparCamposEndereco() {
        if (cepInput) cepInput.value = '';
        if (logradouroInput) logradouroInput.value = '';
        if (bairroInput) bairroInput.value = '';
        if (cidadeInput) cidadeInput.value = '';
        if (ufInput) ufInput.value = '';
        if (enderecoInput) enderecoInput.value = '';
    }

    // Função para exibir carregamento
    function exibirCarregamento() {
        if (logradouroInput) logradouroInput.value = 'Buscando...';
        if (bairroInput) bairroInput.value = '...';
        if (cidadeInput) cidadeInput.value = '...';
        if (ufInput) ufInput.value = '';
        if (enderecoInput) enderecoInput.value = '...';
    }

    // Adiciona o evento blur ao campo CEP
    cepInput.addEventListener('blur', async () => {
        // Pega o valor do CEP e remove caracteres não numéricos
        const cep = cepInput.value.replace(/\D/g, '');

        // Validação do CEP
        if (cep.length !== 8) {
            if (cep.length > 0) {
                alert('CEP inválido. Digite um CEP com 8 dígitos.');
                limparCamposEndereco();
                cepInput.focus();
                cepInput.classList.add('campo-invalido');
                setTimeout(() => cepInput.classList.remove('campo-invalido'), 2000);
            }
            return;
        }

        // Remove classe de erro se existir
        cepInput.classList.remove('campo-invalido');

        // Exibe indicador de carregamento
        exibirCarregamento();

        try {
            // Constrói a URL da API
            const url = `https://viacep.com.br/ws/${cep}/json/`;

            // Faz a requisição usando fetch
            const response = await fetch(url);
            
            // Verifica se a resposta foi bem sucedida
            if (!response.ok) {
                throw new Error(`Erro na requisição: ${response.status}`);
            }
            
            const data = await response.json();

            // Verifica se houve erro na resposta da API
            if (data.erro) {
                alert('CEP não encontrado.');
                limparCamposEndereco();
                cepInput.focus();
                cepInput.classList.add('campo-invalido');
                setTimeout(() => cepInput.classList.remove('campo-invalido'), 2000);
                return;
            }

            // Preenche os campos do formulário com os dados retornados
            if (logradouroInput) logradouroInput.value = data.logradouro || '';
            if (bairroInput) bairroInput.value = data.bairro || '';
            if (cidadeInput) cidadeInput.value = data.localidade || '';
            
            // Para o campo UF (select)
            if (ufInput && data.uf) {
                const ufValue = data.uf.toUpperCase();
                ufInput.value = ufValue;
            }
            
            // Para o campo endereço completo (combina logradouro e complemento)
            if (enderecoInput) {
                let enderecoCompleto = data.logradouro || '';
                if (data.complemento && data.complemento.trim() !== '') {
                    enderecoCompleto += ', ' + data.complemento;
                }
                enderecoInput.value = enderecoCompleto;
            }

            // Adiciona classe de sucesso aos campos preenchidos
            const camposSucesso = [logradouroInput, bairroInput, cidadeInput, ufInput, enderecoInput];
            camposSucesso.forEach(campo => {
                if (campo && campo.value.trim() !== '') {
                    campo.classList.add('campo-valido');
                }
            });

        } catch (error) {
            console.error('Erro ao buscar CEP:', error);
            
            // Tenta uma URL alternativa (sem HTTPS) caso haja problemas de certificado
            if (error.message.includes('Failed to fetch') || error.message.includes('NetworkError')) {
                try {
                    console.log('Tentando conexão HTTP...');
                    const urlHttp = `http://viacep.com.br/ws/${cep}/json/`;
                    const responseHttp = await fetch(urlHttp);
                    const dataHttp = await responseHttp.json();
                    
                    if (!dataHttp.erro) {
                        // Preenche os campos com a resposta HTTP
                        if (logradouroInput) logradouroInput.value = dataHttp.logradouro || '';
                        if (bairroInput) bairroInput.value = dataHttp.bairro || '';
                        if (cidadeInput) cidadeInput.value = dataHttp.localidade || '';
                        if (ufInput && dataHttp.uf) {
                            ufInput.value = dataHttp.uf.toUpperCase();
                        }
                        return;
                    }
                } catch (httpError) {
                    console.error('Erro na requisição HTTP:', httpError);
                }
            }
            
            alert('Ocorreu um erro ao buscar o CEP. Verifique sua conexão e tente novamente.');
            limparCamposEndereco();
        }
    });

    // Adiciona validação em tempo real enquanto digita
    cepInput.addEventListener('input', function() {
        const cep = this.value.replace(/\D/g, '');
        
        // Limpa os campos se o CEP for apagado
        if (cep.length === 0) {
            limparCamposEndereco();
            // Remove classes de validação
            const campos = [logradouroInput, bairroInput, cidadeInput, ufInput, enderecoInput, cepInput];
            campos.forEach(campo => {
                if (campo) {
                    campo.classList.remove('campo-invalido', 'campo-valido');
                }
            });
        }
        
        // Remove classe de erro se começar a digitar novamente
        this.classList.remove('campo-invalido');
    });


    console.log('Módulo de busca de CEP inicializado com sucesso!');
}

// Inicialização quando o DOM estiver carregado
document.addEventListener('DOMContentLoaded', function() {
    // Verifica se existe campo CEP na página
    if (document.getElementById('cep')) {
        buscarCEP();
    }
});

// Para uso global (opcional)
window.BuscarCEP = {
    buscarCEP,
    limparCampos: function() {
        const cepInput = document.getElementById('cep');
        if (cepInput) {
            cepInput.value = '';
            cepInput.dispatchEvent(new Event('input'));
        }
    }
};