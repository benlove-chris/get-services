// Para mostrar ou ocultar a descição do serviço para o contratante
function toggleDescricao(element) {
            var descricao = element.closest('.user-item, .service-item').querySelector('.descricao-servico');
            descricao.style.display = descricao.style.display === 'none' ? 'block' : 'none';
            var icon = element.querySelector('i');
            if (descricao.style.display === 'none') {
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            } else {
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
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
