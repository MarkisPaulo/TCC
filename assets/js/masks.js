// masks.js - LEVE COM FEEDBACK VISUAL
function initializeMasks() {
  document.querySelectorAll("[data-mask]").forEach((input) => {
    // Adiciona classe para estilização
    input.classList.add("campo-numerico");

    // BLOQUEIA CARACTERES NÃO NUMÉRICOS NA DIGITAÇÃO
    input.addEventListener("keypress", function (e) {
      // Permite apenas números e teclas de controle
      const teclasPermitidas = [
        "Backspace",
        "Delete",
        "Tab",
        "Enter",
        "ArrowLeft",
        "ArrowRight",
        "ArrowUp",
        "ArrowDown",
      ];

      if (!/[0-9]/.test(e.key) && !teclasPermitidas.includes(e.key)) {
        e.preventDefault();
        this.classList.add("campo-invalido");
        setTimeout(() => this.classList.remove("campo-invalido"), 1000);
      }
    });

    // VERIFICA E LIMPA CARACTERES NÃO NUMÉRICOS AO COLAR/DIGITAR
    input.addEventListener("input", function (e) {
      const valorOriginal = e.target.value;
      const valorLimpo = valorOriginal.replace(/\D/g, "");

      // ⭐ VERIFICA SE TEM CARACTERES NÃO NUMÉRICOS ⭐
      if (valorOriginal !== valorLimpo) {
        e.target.value = valorLimpo;
        this.classList.add("campo-invalido");

        // Remove o aviso após 1 segundo
        setTimeout(() => {
          this.classList.remove("campo-invalido");
        }, 2000);
      }

      // Aplica a máscara específica
      if (valorLimpo !== "") {
        aplicarMascara(this, valorLimpo);
        this.classList.add("campo-valido");
      } else {
        this.classList.remove("campo-valido");
      }
    });

    // Remove classes quando ganha foco
    
  });
}

function aplicarMascara(input, valorNumerico) {
  const tipo = input.getAttribute("data-mask");

  switch (tipo) {
    case "cpf-cnpj":
      if (valorNumerico.length <= 11) {
        input.value = valorNumerico
          .replace(/(\d{3})(\d)/, "$1.$2")
          .replace(/(\d{3})(\d)/, "$1.$2")
          .replace(/(\d{3})(\d{1,2})$/, "$1-$2");
      } else if (valorNumerico.length <= 14) {
        input.value = valorNumerico
          .replace(/(\d{2})(\d)/, "$1.$2")
          .replace(/(\d{3})(\d)/, "$1.$2")
          .replace(/(\d{3})(\d)/, "$1/$2")
          .replace(/(\d{4})(\d{1,2})$/, "$1-$2");
      }
      break;

    case "tel":
      if (valorNumerico.length <= 11) {
        input.value =
          valorNumerico.length <= 10
            ? valorNumerico
              .replace(/(\d{2})(\d)/, "($1) $2")
              .replace(/(\d{4})(\d{1,4})$/, "$1-$2")
            : valorNumerico
              .replace(/(\d{2})(\d)/, "($1) $2")
              .replace(/(\d{5})(\d{1,4})$/, "$1-$2");
      }
      break;

    case "cep":
      if (valorNumerico.length <= 8) {
        input.value = valorNumerico.replace(/(\d{5})(\d)/, "$1-$2");
      }
      break;

    case "data":
      if (valorNumerico.length <= 8) {
        input.value = valorNumerico
          .replace(/(\d{2})(\d)/, "$1/$2")
          .replace(/(\d{2})(\d)/, "$1/$2");
      }
      break;

    case "valor":
      if (valorNumerico) {
        const amount = (parseInt(valorNumerico) / 100).toLocaleString("pt-BR", {
          minimumFractionDigits: 2,
          maximumFractionDigits: 2,
        });
        input.value = "R$ " + amount;
      }
      break;

    case "ncm":
      if (valorNumerico.length <= 8) {
        // Formato: 0000.00.00
        let valorFormatado = valorNumerico;

        if (valorNumerico.length > 4) {
          valorFormatado = valorNumerico.replace(/(\d{4})(\d)/, "$1.$2");
        }
        if (valorNumerico.length > 6) {
          valorFormatado = valorFormatado.replace(/(\d{4}\.\d{2})(\d)/, "$1.$2");
        }

        input.value = valorFormatado;
      }
      break;

    case "numerico":
      // Campo apenas numérico - sem formatação, apenas validação
      input.value = valorNumerico;

      // Feedback visual opcional
      if (valorNumerico.length > 0 ) {
        input.classList.add('campo-valido');
      } else {
        input.classList.remove('campo-valido');
      }
      break;
    default:
      // Para tipos desconhecidos, apenas mantém os números
      input.value = valorNumerico;
  }
}

// VALIDAÇÃO SIMPLES NO SUBMIT DO FORMULÁRIO
function validarFormulario(formId) {
  const form = document.getElementById(formId);
  if (!form) return;

  form.addEventListener("submit", function (e) {
    let formularioValido = true;
    const camposInvalidos = [];

    form.querySelectorAll("[data-mask]").forEach((campo) => {
      const valorNumerico = campo.value.replace(/\D/g, "");
      const tipo = campo.getAttribute("data-mask");
      let campoValido = true;

      // Validações específicas por tipo
      switch (tipo) {
        case "cpf-cnpj":
          campoValido =
            valorNumerico.length === 11 || valorNumerico.length === 14 && valorNumerico.length > 13;
          break;
        case "tel":
          campoValido = valorNumerico.length >= 10;
          break;
        case "cep":
          campoValido = valorNumerico.length === 8;
          break;
        case "data":
          campoValido = valorNumerico.length === 8;
          break;
        case "valor":
          campoValido = valorNumerico.length > 0;
          break;
        case "ncm":
          campoValido = valorNumerico.length === 8;
          break;
        case "numerico":
          campoValido = valorNumerico.length > 0;
          break;
        default:
          campoValido = valorNumerico.length > 0;
      }

      if (!campoValido && campo.value !== "") {
        campo.classList.add("campo-invalido");
        campo.classList.remove("campo-valido");
        formularioValido = false;
        camposInvalidos.push(campo);
      }
    });

    if (!formularioValido) {
      e.preventDefault();

      // Foca no primeiro campo inválido
      if (camposInvalidos.length > 0) {
        camposInvalidos[0].focus();
        camposInvalidos[0].scrollIntoView({
          behavior: "smooth",
          block: "center",
        });
      }

      console.log("a");
      alert("Por favor, verifique os campos destacados em vermelho.");
    }
  });
}

// INICIALIZAÇÃO RÁPIDA
document.addEventListener("DOMContentLoaded", function () {
  initializeMasks();

  // Inicializa validação para formulários com data-validate
  document.querySelectorAll("form[data-validate]").forEach((form) => {
    validarFormulario(form.id);
  });
});

// Para uso global (opcional)
window.MaskUtils = { initializeMasks, validarFormulario };
