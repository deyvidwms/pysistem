<?php

	/** 
	 * 
	 * Importacao do arquivo
	 * de conexao com o banco de dados
	 * 
	 */
	include "../config/config.php";

	/**
	 * 
	 * Verifica se ha algum token na url
	 * se nao o usuario eh retornado a 
	 * pagina de login
	 * 
	 */
	if ( isset( $_GET["token"] ) && !empty( $_GET["token"] ) ) {

		$token = $_GET["token"];

		/**
		 * 
		 * Verifica se o token existe
		 * e se ele nao foi usado
		 * 
		 */
		$selectUsuarioToken = "SELECT * 
								FROM usuarios 
								JOIN tokens_recuperacao_senhas ON tokens_recuperacao_senhas.id_usuario = usuarios.id_usuario
								WHERE token = :token AND tokens_recuperacao_senhas.apagado = 0";
		$selectUsuarioToken = $pdo->prepare($selectUsuarioToken);
		$selectUsuarioToken->bindValue(":token", $token);
		if ( $selectUsuarioToken->execute() == false || $selectUsuarioToken->rowCount() == 0 ) {
			header("Location: index.php");
		} else if ( $selectUsuarioToken->rowCount() > 0 ) {

			$dadosUsuarioToken = $selectUsuarioToken->fetch();

		}

	/**
	 * 
	 * Retorna o usuario para a pagina inicial
	 * caso tenha dado algum erro
	 * 
	 */
	} else {
		header("Location: index.php");
	}

?>
<!DOCTYPE html>
<html>
	<head>
		<title>PSystem - RECUPERAR SENHA</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta charset="UTF-8">
		<link rel="icon" type="image/png" sizes="16x16" href="../assets/images/MiniLogo.png">
		<link rel="stylesheet" type="text/css" href="../dist/css/main.css">
		<link rel="stylesheet" type="text/css" href="../dist/css/util.css">
		<link rel="stylesheet" type="text/css" href="../dist/css/login.css">

		<link rel="preconnect" href="https://fonts.gstatic.com">
		<link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">

	</head>
	<body>

		<div class="container" style="background-color: #53d8a5">

			<div class="card" style="background-color: #FFF">

				<div class="card--logo">

					<img src="../assets/images/Logo.png" alt="Logo do site">

				</div>

				<div class="card--title m-b-20">

					<h1 id="h1Login">Definir nova senha</h1>

				</div>

				<form id="recoveryPassword" class="card--form">

					<input type="hidden" name="idUsuario" value="<?php echo $dadosUsuarioToken["id_usuario"];?>">

					<div class="form--row">

						<p>Nova Senha</p>

						<input type="password" id="novaSenha" name="senha" class="form-control" minlength="8" placeholder="Digite sua nova senha..." required>

					</div>

					<div class="form--row">

						<p>Confirmar Senha</p>

						<input type="password" id="confNovaSenha" class="form-control" minlength="8" placeholder="Confirme a senha digitada..." required>

					</div>

					<div class="form--row text-right">
					
						<button id="btnEnviar">Editar</button>
					
					</div>

				</form>

			</div>

		</div>
		
		<script src="../../assets/libs/jquery/dist/jquery.min.js"></script>

		<script>

			$(function(){

				// ativa a funcao de submit do formulario
				$("#recoveryPassword").on("submit", function(e) {
					e.preventDefault();

					var dados = $(this).serialize();

					// verifica se as senhas sao iguais
					if ( $("#novaSenha").val() == $("#confNovaSenha").val() ) {

						// realiza a requisicao de trocar a senha
						$.ajax({
							type:"POST",
							url:"./recuperar_senha_submit.php",
							data: dados,
							success:function(res) {
		
								// pega o json retornado e trata
								// sua resposta
								try {
									
									let response = JSON.parse(res);
		
									if ( typeof response.error == "undefined" ) {
										
										alert(response.success);
										window.location.href = "./login.php";
		
									} else {
		
										alert( response.error );
		
									}
		
								} catch (e) {
									return null;
								}
		
							},
							error:function(){
								alert('Ops... Parece que ocorreu algum erro. :(\nTente novamente em instantes...');
							},
						});
					
					} else {
					
						alert('Atenção! As senhas diferentes');
					
					}


				});

			});

		</script>

	</body>
</html>