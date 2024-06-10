function toggleDescricao(button) {
            var description = button.parentNode.previousElementSibling.querySelector('.descricao-servico');
            if (description.style.display === 'none' || description.style.display === '') {
                description.style.display = 'block';
                button.textContent = 'Ler menos';
            } else {
                description.style.display = 'none';
                button.textContent = 'Ler mais';
            }
        }

function apagarServico(id) {
    if (confirm("Tem certeza de que deseja apagar este serviço?")) {
        fetch(`../backend/apagar_servico.php?id=${id}`, { method: 'GET' })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.text();
        })
        .then(data => {
            alert(data);
            location.reload();
        })
        .catch(error => {
            console.error('Erro:', error);
        });
    }
}
