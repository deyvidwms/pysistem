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

					<img src="../assets/images/Logo.png" alt="Logo do sistema">

				</div>

				<div class="card--title m-b-20">
					
					<h1 id="h1Login">Esqueceu sua senha?</h1>

					<p id="mensagemES">Sem problemas. Basta nos informar seu endereço de e-mail e nós enviaremos um link de redefinição de senha que permitirá que você escolha uma nova.</p>

				</div>
		
				<form id="recoveryPassword" class="card--form">

					<div class="form--row">

						<p>Email</p>

						<input type="text" id="email-recuperar" name="email" class="form-control" placeholder="Digite seu email..." required>

						<input type="hidden" name="bool" value="false">
						
					</div>

					<div class="form--row text-right">
					
						<button id="btnEnviar">Enviar</button>
					
					</div>

					<a class="linkVoltar" href="./login.php">Voltar</a>

				</form>

			</div>

		</div>
		
		<script src="../../assets/libs/jquery/dist/jquery.min.js"></script>

		<script>

			$(function(){

				// permite fazer a requisicao para enviar o email 
				// de esqueceu a senha
				$("#recoveryPassword").on("submit", function(e) {
					e.preventDefault();

					var dados = $(this).serialize();

					$.ajax({
						type:"POST",
						url:"../functions/enviar_email_submit.php",
						data: dados,
						success:function(res) {

							try {
								
								let response = JSON.parse(res);

								if ( typeof response.error == "undefined" ) {
									
									alert("Email enviado com sucesso.");
									window.location.href = response.link;

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

				});

			});

		</script>


	</body>
</html>