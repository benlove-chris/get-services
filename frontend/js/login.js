document.getElementById("loginForm").addEventListener("submit", function(event) {
    event.preventDefault(); // Evita o envio padrão do formulário
            
    var formData = new FormData(this);

    fetch(this.action, {
        method: 'POST',
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            return response.text().then(text => { throw new Error(text); });
        }
        return response.text();
    })
    .then(data => {
        // Verifica se a resposta é uma mensagem de sucesso
        if (data.trim() === "Login bem-sucedido") {
            window.location.href = "dashboard.php"; // Redirecionar após login bem-sucedido
        } else {
            document.getElementById("mensagem").textContent = data;
            document.getElementById("mensagem").style.display = 'block'; // Exibe a mensagem de erro
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        document.getElementById("mensagem").textContent = error.message;
        document.getElementById("mensagem").style.display = 'block'; // Exibe a mensagem de erro
    });
});
