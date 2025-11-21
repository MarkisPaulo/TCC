// masks.js - SEU ARQUIVO ÚNICO
const MaskUtils = {
    applyMask(element, maskType) {
        // Remove event listeners anteriores para evitar duplicação
        element.replaceWith(element.cloneNode(true));
        const newElement = element.parentElement.lastElementChild;

        switch(maskType) {
            case 'cpf-cnpj':
                this.cpf_cnpjMask(newElement);
                break;
            case 'phone':
                this.phoneMask(newElement);
                break;
            case 'cep':
                this.cepMask(newElement);
                break;
            case 'date':
                this.dateMask(newElement);
                break;
            case 'valor':
                this.valorMask(newElement);
                break;
        }
    },

    cpf_cnpjMask(element) {
        element.addEventListener('input', (e) => {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length <= 11) {
                value = value.replace(/(\d{3})(\d)/, '$1.$2')
                           .replace(/(\d{3})(\d)/, '$1.$2')
                           .replace(/(\d{3})(\d{1,2})$/, '$1-$2');
            }else if(value.length <= 14){
                value = value.replace(/(\d{2})(\d)/, '$1.$2')
                           .replace(/(\d{3})(\d)/, '$1.$2')
                           .replace(/(\d{3})(\d)/, '$1/$2')
                           .replace(/(\d{4})(\d{1,2})$/, '$1-$2');
            }
            e.target.value = value;
        });
    },

    phoneMask(element) {
        element.addEventListener('input', (e) => {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length <= 11) {
                value = value.length <= 10 
                    ? value.replace(/(\d{2})(\d)/, '($1) $2')
                          .replace(/(\d{4})(\d{1,4})$/, '$1-$2')
                    : value.replace(/(\d{2})(\d)/, '($1) $2')
                          .replace(/(\d{5})(\d{1,4})$/, '$1-$2');
            }
            e.target.value = value;
        });
    },

    cepMask(element) {
        element.addEventListener('input', (e) => {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length <= 8) {
                value = value.replace(/(\d{5})(\d)/, '$1-$2');
            }
            e.target.value = value;
        });
    },

    dateMask(element) {
        element.addEventListener('input', (e) => {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length <= 8) {
                value = value.replace(/(\d{2})(\d)/, '$1/$2')
                           .replace(/(\d{2})(\d)/, '$1/$2');
            }
            e.target.value = value;
        });
    },

    valorMask(element) {
        element.addEventListener('input', (e) => {
            let value = e.target.value.replace(/\D/g, '');
            value = (value / 100).toLocaleString('pt-BR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
            e.target.value = value === '0,00' ? '' : value;
        });

        // Formata o valor inicial se existir
        if (element.value) {
            let value = element.value.replace(/\D/g, '');
            element.value = (value / 100).toLocaleString('pt-BR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }
    }
};

// Inicializador automático
function initializeMasks() {
    console.log('🔧 Inicializando máscaras...');
    
    document.querySelectorAll('[data-mask]').forEach(element => {
        const maskType = element.getAttribute('data-mask');
        console.log(`Aplicando máscara: ${maskType} no elemento:`, element);
        MaskUtils.applyMask(element, maskType);
    });
}

// Inicializa quando o DOM estiver carregado
document.addEventListener('DOMContentLoaded', initializeMasks);

// Para uso manual, se necessário
window.MaskUtils = MaskUtils;
window.initializeMasks = initializeMasks;