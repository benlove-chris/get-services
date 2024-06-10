document.getElementById("cadastroForm").addEventListener("submit", function(event) {
    event.preventDefault(); // Evita o envio padrão do formulário
    
    var formData = new FormData(this);
    
    var mensagemErro = document.getElementById("mensagem_erro");
    var mensagemSucesso = document.getElementById("mensagem_sucesso");

    fetch(this.action, {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(data => {
        if (data.includes("sucesso")) {
            mensagemSucesso.style.display = 'block';
            mensagemErro.style.display = 'none';
            this.reset();
        } else {
            mensagemErro.textContent = data;
            mensagemErro.style.display = 'block';
            mensagemSucesso.style.display = 'none';
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        mensagemErro.textContent = 'Erro ao cadastrar usuário';
        mensagemErro.style.display = 'block';
        mensagemSucesso.style.display = 'none';
    });
});
