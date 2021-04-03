<!DOCTYPE html>
<html>
	<head>
		<title>PSystem - LOGIN</title>
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
		<div class="container">

			<div class="card">

				<div class="card--logo">

					<img src="../assets/images/Logo.png" alt="Logo do sistema">

				</div>

				<div class="card--title">
					
					<h1 class="title">Paciente</h1>

				</div>

				<form id="login" class="card--form">

					<div class="form--row">

						<p>Login</p>

						<input type="text" name="usuario" class="form-control" placeholder="Digite seu usuario ou email..." required>

					</div>

					<div class="form--row">

						<p>Senha</p>
						
						<input type="password" name="senha" class="form-control" placeholder="Digite sua senha..." required>
						
					</div>
					
					<div class="form--row">
						
						<a class="esqsenha" href="esqueceu_senha.php">Esqueci minha senha</a>

					</div>

					<div class="form--row">
					
						<button id="btnEntrar">Entrar</button>
					
					</div>

				</form>

			</div>

		</div>
		
		<script src="../../assets/libs/jquery/dist/jquery.min.js"></script>

		<script>

			$(function(){
				
				// permite fazer requisicao de login
				$("#login").on("submit", function(e) {
					e.preventDefault();

					var dados = $(this).serialize();

					$.ajax({
						type:"POST",
						url:"login_submit.php",
						data:dados,
						success:function(msg) {
							window.location.href = msg;
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