document.getElementById("cadastroForm").addEventListener("submit", function(event) {
    
            event.preventDefault(); // Evita o envio padrão do formulário
            
            var formData = new FormData(this);

            fetch(this.action, {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Falha ao cadastrar usuário');
                }
                return response.text();
            })
            .then(data => {
                document.getElementById("mensagem").textContent = data;
                // Limpar campos do formulário após o envio bem-sucedido
                this.reset();
            })
            .catch(error => {
                console.error('Erro:', error);
                document.getElementById("mensagem").textContent = 'Erro ao cadastrar usuário';
            });
        });