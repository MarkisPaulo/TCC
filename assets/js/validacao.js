/**
 * Validação de CPF/CNPJ
 * Arquivo independente que valida CPF e CNPJ baseado na quantidade de dígitos
 */

// Validação de CPF (mantém igual - está correto)
function validarCPF(cpf) {
    cpf = cpf.replace(/\D/g, '');
    
    if (cpf.length !== 11) return false;
    
    // Verifica se todos os dígitos são iguais
    if (/^(\d)\1+$/.test(cpf)) return false;
    
    // Validação do primeiro dígito verificador
    let soma = 0;
    for (let i = 0; i < 9; i++) {
        soma += parseInt(cpf.charAt(i)) * (10 - i);
    }
    let resto = (soma * 10) % 11;
    if (resto === 10 || resto === 11) resto = 0;
    if (resto !== parseInt(cpf.charAt(9))) return false;
    
    // Validação do segundo dígito verificador
    soma = 0;
    for (let i = 0; i < 10; i++) {
        soma += parseInt(cpf.charAt(i)) * (11 - i);
    }
    resto = (soma * 10) % 11;
    if (resto === 10 || resto === 11) resto = 0;
    if (resto !== parseInt(cpf.charAt(10))) return false;
    
    return true;
}

// Validação de CNPJ 
function validarCNPJ(cnpj) {
    cnpj = cnpj.replace(/[^\d]+/g, '');
    
    if (cnpj === '') return false;
    if (cnpj.length !== 14) return false;
    
    // Elimina CNPJs inválidos conhecidos
    if (cnpj === "00000000000000" ||
        cnpj === "11111111111111" ||
        cnpj === "22222222222222" ||
        cnpj === "33333333333333" ||
        cnpj === "44444444444444" ||
        cnpj === "55555555555555" ||
        cnpj === "66666666666666" ||
        cnpj === "77777777777777" ||
        cnpj === "88888888888888" ||
        cnpj === "99999999999999")
        return false;
        
    // Valida DVs
    let tamanho = cnpj.length - 2;
    let numeros = cnpj.substring(0, tamanho);
    let digitos = cnpj.substring(tamanho);
    let soma = 0;
    let pos = tamanho - 7;
    
    for (let i = tamanho; i >= 1; i--) {
        soma += numeros.charAt(tamanho - i) * pos--;
        if (pos < 2) pos = 9;
    }
    
    let resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
    if (resultado != digitos.charAt(0)) return false;
    
    tamanho = tamanho + 1;
    numeros = cnpj.substring(0, tamanho);
    soma = 0;
    pos = tamanho - 7;
    
    for (let i = tamanho; i >= 1; i--) {
        soma += numeros.charAt(tamanho - i) * pos--;
        if (pos < 2) pos = 9;
    }
    
    resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
    if (resultado != digitos.charAt(1)) return false;
    
    return true;
}

// Função principal que decide se é CPF ou CNPJ baseado no tamanho
function validarCpfCnpj(valor) {
    const numero = valor.replace(/\D/g, '');
    
    // Remove mensagem de erro anterior
    removerMensagemErro();
    
    if (numero.length === 11) {
        const valido = validarCPF(valor);
        console.log('CPF válido?', valido, 'Valor:', valor);
        return valido;
    } else if (numero.length === 14) {
        const valido = validarCNPJ(valor);
        console.log('CNPJ válido?', valido, 'Valor:', valor);
        return valido;
    }
    
    console.log('Tamanho inválido:', numero.length);
    return false; // Tamanho inválido
}


// Exibe mensagem de erro estilizada
function exibirMensagemErro(input, mensagem) {
    // Remove mensagem anterior se existir
    removerMensagemErro();
    
    // Cria elemento de mensagem
    const erroDiv = document.createElement('div');
    erroDiv.className = 'cpf-cnpj-erro';
    erroDiv.textContent = mensagem;
    erroDiv.style.cssText = `
        color: #d32f2f;
        font-size: 12px;
        margin-top: 5px;
        padding: 5px;
        background-color: #ffebee;
        border-radius: 4px;
        border-left: 3px solid #d32f2f;
    `;
    
    // Insere após o campo
    input.parentNode.insertBefore(erroDiv, input.nextSibling);
    
    // Adiciona classe de erro ao input
    input.classList.add('campo-invalido');
    
    // Foca no campo
    input.focus();
}

// Remove mensagem de erro
function removerMensagemErro() {
    const erroAtual = document.querySelector('.cpf-cnpj-erro');
    if (erroAtual) {
        erroAtual.remove();
    }
    
    // Remove classe de erro de todos os campos
    document.querySelectorAll('.campo-invalido').forEach(campo => {
        campo.classList.remove('campo-invalido');
    });
}

// Validação em tempo real durante a digitação
function configurarValidacaoEmTempoReal() {
    const campoCpfCnpj = document.getElementById('cpf-cnpj');
    
    if (!campoCpfCnpj) return;
    
    campoCpfCnpj.addEventListener('blur', function() {
        const valor = this.value.replace(/\D/g, '');
        
        // Só valida se tiver preenchido
        if (valor.length === 0) {
            removerMensagemErro();
            return;
        }
        
        // Valida conforme o tamanho
        if (valor.length === 11 || valor.length === 14) {
            if (!validarCpfCnpj(this.value)) {
                exibirMensagemErro(this, valor.length === 11 
                    ? 'CPF inválido. Por favor, verifique o número.' 
                    : 'CNPJ inválido. Por favor, verifique o número.');
            } else {
                removerMensagemErro();
                // Adiciona classe de sucesso
                this.classList.add('campo-valido');
                this.classList.remove('campo-invalido');
            }
        } else {
            exibirMensagemErro(this, 'Documento inválido. CPF precisa ter 11 dígitos, CNPJ precisa ter 14 dígitos.');
        }
    });
    
    // Remove erro quando começar a digitar novamente
    campoCpfCnpj.addEventListener('input', function() {
        this.classList.remove('campo-invalido', 'campo-valido');
        removerMensagemErro();
        
        // Aplica validação automática quando atingir o tamanho correto
        const valor = this.value.replace(/\D/g, '');
        if (valor.length === 11 || valor.length === 14) {
            setTimeout(() => {
                if (!validarCpfCnpj(this.value)) {
                    this.classList.add('campo-invalido');
                } else {
                    this.classList.add('campo-valido');
                }
            }, 500);
        }
    });
}

// Validação no envio do formulário
function validarAntesEnvio(formId) {
    const form = document.getElementById(formId);
    if (!form) return true; // Permite submit se não encontrar o formulário
    
    const campoCpfCnpj = document.getElementById('cpf-cnpj');
    if (!campoCpfCnpj) return true;
    
    const valor = campoCpfCnpj.value.replace(/\D/g, '');
    
    // Se estiver vazio, deixa a validação required do HTML trabalhar
    if (valor.length === 0) {
        return true;
    }
    
    // Valida CPF/CNPJ
    const valido = validarCpfCnpj(campoCpfCnpj.value);
    
    if (!valido) {
        // Evita o envio do formulário
        event.preventDefault();
        
        // Exibe mensagem de erro
        if (valor.length === 11) {
            exibirMensagemErro(campoCpfCnpj, 'CPF inválido. Por favor, corrija antes de enviar.');
        } else if (valor.length === 14) {
            exibirMensagemErro(campoCpfCnpj, 'CNPJ inválido. Por favor, corrija antes de enviar.');
        } else {
            exibirMensagemErro(campoCpfCnpj, 'Documento inválido. CPF precisa ter 11 dígitos, CNPJ precisa ter 14 dígitos.');
        }
        
        // Rola até o campo
        campoCpfCnpj.scrollIntoView({
            behavior: 'smooth',
            block: 'center'
        });
        
        return false;
    }
    
    return true;
}



// Inicialização quando o DOM estiver carregado
document.addEventListener('DOMContentLoaded', function() {
    // Configura validação em tempo real
    configurarValidacaoEmTempoReal();
    
    // Adiciona validação ao submit do formulário
    const form = document.getElementById('form');
    if (form) {
        form.addEventListener('submit', function(event) {
            return validarAntesEnvio('form');
        });
    }
});

// Para uso global (opcional)
window.ValidarCpfCnpj = {
    validarCPF,
    validarCNPJ,
    validarCpfCnpj,
    configurarValidacaoEmTempoReal,
    validarAntesEnvio,
};