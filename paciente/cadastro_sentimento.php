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
	 * Importacao do cabecalho
	 * da pagina
	 * 
	 */
	include "./cab.php";

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
			
			<h2 class="card-title">Cadastrar Sentimento</h2>
			
		</div>
		
		<!-- <div class="col-lg-1"></div> -->

	</div>

	<div class="row d-flex justify-content-center">

		<div class="col-lg-10 card p-5">

			<!-- Formulário -->
			<form id="cadSentimento">
		
				<input type="hidden" name="usuario" value="<?php echo $dadosUsuario["id_usuario"]; ?>">

				<div class="form-row">
		
					<div class="form-group col-md-12" id="divCadSent">
		
						<label class="required">Como você se sente com</label></br>     
												
						<label class="mx-2" for="familia">

							<input type="radio" name="categoria" value="1" id="familia" checked>

							Sua família

						</label>
							
						<label class="mx-2" for="amigos">

							<input type="radio" name="categoria" value="2" id="amigos">

							Seus amigos

						</label>
						
						<label class="mx-2" for="alimentacao">

							<input type="radio" name="categoria" value="3" id="alimentacao">

							Sua alimentação

						</label>
	
						<label class="mx-2" for="ocupacao">

							<input type="radio" name="categoria" value="4" id="ocupacao">

							Sua ocupação

						</label>
				
						<label class="mx-2" for="lazer">

							<input type="radio" name="categoria" value="5" id="lazer">

							Seu lazer

						</label>
						
						<label class="mx-2" for="voceMesmo">

							<input type="radio" name="categoria" value="6" id="voceMesmo">

							Você mesmo

						</label>
	
					</div>
		
				</div> 
		
				<!-- --------------------------- -->
				
				<div class="form-row">
		
					<div class="form-group col-md-8" id="divCadSent">
		
						<label class="required">Avalie essa relação em:</label><br>     
						
						<label class="mx-2" for="pessima">

							<input type="radio" name="nota" value="1" id="pessima" checked>

							Péssima

						</label>
							
						<label class="mx-2" for="ruim">

							<input type="radio" name="nota" value="2" id="ruim">
						
							Ruim

						</label>
					
						<label class="mx-2" for="maisOuMenos">
							
							<input type="radio" name="nota" value="3" id="maisOuMenos">

							Mais ou menos

						</label>
								
						<label class="mx-2" for="boa">

							<input type="radio" name="nota" value="4" id="boa">

							Boa

						</label>
				
						<label class="mx-2" for="otima">

							<input type="radio" name="nota" value="5" id="otima">

							Ótima

						</label>
				
					</div>
		
				</div>     
			
				<!-- --------------------------- -->
		
				<div class="form-row">
					
					<div class="col-md-12">
					
						<label for="nome">Por que você acha isso? Desabafe aqui:</label>
						
						<textarea class="form-control" name="feedback" style="height: 150px;"></textarea>
		
					</div>
		
				</div>
		
				<div class="row">
					
					<div class="col-md-12 mt-4">
		
						<button type="submit" class="btnPadrao">Cadastrar</button>
		
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

			cadastrarSentimento();

		} );

			// permite o cadastro de sentimentos
		function cadastrarSentimento () {

			$("#cadSentimento").on("submit", function (e) {
				e.preventDefault();

				let data = $(this).serializeArray();

				$.ajax({
					url: "./cadastrar_sentimento_submit.php?tipo=1",
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
	 * Importacao do rodape
	 * da pagina
	 * 
	 */
	include "./rod.php";
	
?> 