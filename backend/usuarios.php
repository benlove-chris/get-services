<?php

// importando banco de dados 
require 'db.php';

//consulta sql para selecionar todos os registros da tabela usuarios
$sql= "SELECT * FROM usuarios";

$resultado = $conexao->query($sql);


if ($resultado-> num_rows > 0 ){
	while($linha = $resultado->fetch_assoc()){
		echo "ID: " . $linha["id"]. " - Nome: " . $linha["nome"]. " - Email: " . $linha["email"].  "- Senha: " . $linha["senha"]. "`- tipo: ". $linha["tipo"]. "<br>";
        }
    } else {
        echo "0 resultados";
 }
 ?>
