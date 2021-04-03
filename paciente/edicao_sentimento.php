<?php 

	/**
	 * 
	 * Importacao do arquivo de 
	 * conexao com o banco de dados
	 * 
	 */
	include "../config/config.php";

	/**
	 * 
	 * Importacao do arquivo de cabecalho
	 * 
	 */
	include "./cab.php";

	/**
	 * 
	 * Verifica se o id do sentimento
	 * foi enviado na url
	 * 
	 */
	if ( isset( $_GET["sentimento"] ) && !empty( $_GET["sentimento"] ) ) {

		$sentimento = $_GET["sentimento"];

		/**
		 * 
		 * Pega informacoes do sentimento
		 * 
		 */
		$getSentimento = "SELECT * FROM sentimentos WHERE id_sentimento = :sentimento";
		$getSentimento = $pdo->prepare($getSentimento);
		$getSentimento->bindValue(":sentimento", $sentimento);
		if ( $getSentimento->execute() == false || $getSentimento->rowCount() == 0 ) {
			header("Location: index.php");
		} else if ( $getSentimento->rowCount() > 0 ) {

			$dadosSentimento = $getSentimento->fetch();

		}

	} else {
		header("Location: index.php");
	}

?> 

	<style>
		.required:after {
			content:" *"; 
			color: red;
		}
	</style>

	<div class="row">
		
		<!-- <div class="col-lg-1"></div> -->
		
		<div class="col-lg-10">
			
			<h2 class="card-title">Editar Sentimento</h2>
			
		</div>
		
		<!-- <div class="col-lg-1"></div> -->

	</div>

	<div class="row d-flex justify-content-center">

		<div class="col-lg-10 card p-5">

			<!-- Formulário -->
			<form id="editSentimento">
		
				<input type="hidden" name="idUsuario" value="<?php echo $dadosUsuario["id_usuario"]; ?>">

				<input type="hidden" name="sentimento" value="<?php echo $dadosSentimento["id_sentimento"]; ?>">

				<div class="form-row">
		
					<div class="form-group col-md-12" id="divCadSent">
		
						<label class="required">Como você se sente com</label></br>     
												
						<label class="mx-2" for="familia">

							<input type="radio" name="categoria" value="1" id="familia" <?php echo $dadosSentimento["id_categoria_dos_sentimentos"] == 1 ? "checked" : ""; ?> >

							Sua família

						</label>
							
						<label class="mx-2" for="amigos">

							<input type="radio" name="categoria" value="2" id="amigos" <?php echo $dadosSentimento["id_categoria_dos_sentimentos"] == 2 ? "checked" : ""; ?>>

							Seus amigos

						</label>
						
						<label class="mx-2" for="alimentacao">

							<input type="radio" name="categoria" value="3" id="alimentacao" <?php echo $dadosSentimento["id_categoria_dos_sentimentos"] == 3 ? "checked" : ""; ?>>

							Sua alimentação

						</label>
	
						<label class="mx-2" for="ocupacao">

							<input type="radio" name="categoria" value="4" id="ocupacao" <?php echo $dadosSentimento["id_categoria_dos_sentimentos"] == 4 ? "checked" : ""; ?>>

							Sua ocupação

						</label>
				
						<label class="mx-2" for="lazer">

							<input type="radio" name="categoria" value="5" id="lazer" <?php echo $dadosSentimento["id_categoria_dos_sentimentos"] == 5 ? "checked" : ""; ?>>

							Seu lazer

						</label>
						
						<label class="mx-2" for="voceMesmo">

							<input type="radio" name="categoria" value="6" id="voceMesmo" <?php echo $dadosSentimento["id_categoria_dos_sentimentos"] == 6 ? "checked" : ""; ?>>

							Você mesmo
							
						</label>
	
					</div>
		
				</div> 
		
				<!-- --------------------------- -->
				
				<div class="form-row">
		
					<div class="form-group col-md-8" id="divCadSent">
		
						<label class="required">Avalie essa relação em:</label><br>     
						
						<label class="mx-2" for="pessima">

							<input type="radio" name="nota" value="1" id="pessima" <?php echo $dadosSentimento["nota_sentimento"] == 1 ? "checked" : ""; ?> >

							Péssima

						</label>
							
						<label class="mx-2" for="ruim">

							<input type="radio" name="nota" value="2" id="ruim" <?php echo $dadosSentimento["nota_sentimento"] == 2 ? "checked" : ""; ?> >

							Ruim
							
						</label>
					
						<label class="mx-2" for="maisOuMenos">

							<input type="radio" name="nota" value="3" id="maisOuMenos" <?php echo $dadosSentimento["nota_sentimento"] == 3 ? "checked" : ""; ?> >
							
							Mais ou menos

						</label>
								
						<label class="mx-2" for="boa">

							<input type="radio" name="nota" value="4" id="boa" <?php echo $dadosSentimento["nota_sentimento"] == 4 ? "checked" : ""; ?> >

							Boa

						</label>
				
						<label class="mx-2" for="otima">

							<input type="radio" name="nota" value="5" id="otima" <?php echo $dadosSentimento["nota_sentimento"] == 5 ? "checked" : ""; ?> >

							Ótima

						</label>
				
					</div>
		
				</div>     
			
				<!-- --------------------------- -->
		
				<div class="form-row">
					
					<div class="col-md-12">
					
						<label for="nome">Por que você acha isso? Desabafe aqui:</label>
						
						<textarea class="form-control" name="feedback" style="height: 150px;"><?php echo !empty( $dadosSentimento["descricao"] ) ? utf8_encode( $dadosSentimento["descricao"] ) : ""; ?></textarea>
		
					</div>
		
				</div>
		
				<div class="row">
					
					<div class="col-md-12 mt-4">
		
						<button type="submit" class="btnPadrao">Editar</button>
		
					</div>
		
				</div>
			
			</form>

		</div>

	</div>

	<div class="modal" id="modal" role="dialog" data-backdrop="static">

		<div class="modal-dialog modal-dialog-centered" role="document">

			<div class="modal-content">

				<div class="modal-body pt-4 pb-0">

					<h3 class="text-dark m-0" id="mensagemRetorno">Mensagem de retorno.</h3>

				</div>

				<div class="modal-footer text-right border-top-0">

					<button type="button" id="fecharModal" class="btn btn-secondary" data-dismiss="modal">Concluir</button>

				</div>

			</div>

		</div>

	</div>


	<script>

		// inicia funcoes importantes
		$(window).ready( function () {

			editarSentimento();

		} );

		// permite a edicao das informacoes do usuario
		function editarSentimento () {

			$("#editSentimento").on("submit", function (e) {
				e.preventDefault();

				let data = $(this).serializeArray();

				$.ajax({
					url: "./cadastrar_sentimento_submit.php?tipo=2",
					type: "POST",
					data: data,
					success: function(res) {

						try {

							let response = JSON.parse(res);

							if ( typeof response.error == "undefined" ) {

								$("#mensagemRetorno").text(response.success);
								
								$("#modal").modal("show");

								$("#fecharModal").click( () => {
									setTimeout( () => {
										window.location.href = "./index.php";
									}, 1000);
								});

							} else if ( typeof response.error != "undefined" ) {

								$("#mensagemRetorno").text(response.error);

								$("#modal").modal("show");

							}

						} catch (e) {
							return null;
						}

					},
					error: function() {

						$("#mensagemRetorno").text("Falha ao tentar fazer requisição.");

						$("#modal").modal("show");

						setTimeout( () => {
							$("#modal").modal("hide");
						}, 3000);


					}
				});

			} );

		}


	</script>

<?php

	/**
	 * 
	 * Importacao do arquivo de rodape
	 * 
	 */
	include "rod.php";
	
?> 