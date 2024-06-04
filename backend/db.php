
<?php
/*Obter conexao do banco de doa*/
$servidor = "localhost";
$usuario = "root";
$senha = "";
$bancodedados = "servicosdb";

// criar conexao com o banco de dados
$conexao = new mysqli($servidor, $usuario, $senha, $bancodedados);

if ($conexao -> connect_error){
	die ("Falha na conexao: ". $conexao->connect_error);
}

?>