<!DOCTYPE html>
<html>
	<head>
		<title>PSystem - LOGIN</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta charset="UTF-8">
		<link rel="icon" type="image/png" sizes="16x16" href="./assets/images/MiniLogo.png">
		<link rel="stylesheet" type="text/css" href="./dist/css/main.css">
		<link rel="stylesheet" type="text/css" href="./dist/css/util.css">
		<link rel="preconnect" href="https://fonts.gstatic.com">
		<link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">

		<style>

			* {
				padding: 0px;
				margin: 0px;
				box-sizing: border-box;
				font-family: roboto, sans-serif;
			}

			.container {
				width: 100vw;
				height: 100vh;
				display: flex;
				justify-content: center;
				align-items: center;
				background-color: #53d8a5;
			}

			.card {
				background-color: #f1f1f1;
				width: 25vw;
				padding: 50px;
				border-radius: 10px;
				box-shadow: 0px 0px 10px rgba(127, 127, 127, 0.8);
				transition: all ease 0.3s;
			}

			.card--logo {
				text-align: center;
				margin-bottom: 25px;
			}

			.card--buttons {
				display: flex;
				flex-direction: column;
				text-align: center;
			}
				
			.card--buttons > p {
				font-size: 20px;
			}			

			.card--buttons > button {
				padding: 10px 50px;
				background-color: #0B9E66;
				color: #FFF;
				margin: 10px;
				border-radius: 5px;
				font-size: 18px;
				transition: all ease 0.3s;
			}

			.card--buttons > button:hover,
			.card--buttons > button:focus {
				background-color: #008f58;
				transform: scale(1.05) ;
			}

			@media (max-width: 1560px) {
				.card {
					width: 40vw;
				}
			}

			@media (max-width: 980px) {
				.card {
					width: 50vw;
				}
			}

			@media (max-width: 690px) {
				.card {
					width: 80vw;
				}
			}

			@media (max-width: 480px) {
				.card {
					padding: 20px 10px;
				}
				.card--buttons > p {
					font-size: 18px;
				}			
			}


		</style>

	</head>
	<body>
	
		<div class="container">

			<div class="card">

				<div class="card--logo">
					
					<img src="./assets/images/Logo.png" alt="Logo do sistema">

				</div>

				<div class="card--buttons">

					<p>Como você deseja logar?</p>

					<button onclick=" window.location.href='./medico/' ">Médico</button>

					<button onclick=" window.location.href='./paciente/' ">Paciente</button>

				</div>

			</div>

		</div>
	
	</body>
</html>