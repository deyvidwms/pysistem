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

	<div class="row d-flex justify-content-center">

		<div class="col-lg-10">

			<h2 class="card-title">Cadastrar Paciente</h2>

		</div>

	</div>

	<div class="row">

		<div class="col-lg-1"></div>

		<div class="col-lg-10 pb-5 pt-3 px-4 card rounded shadow">

			<!-- Formulário -->
			<form id="cadUser">
		
				<input type="hidden" name="idUsuario" value="<?php echo $dadosUsuario["id_usuario"]; ?>" >

				<div class="form-row">
		
					<div class="form-group col-md-6">

						<label for="nome">Nome</label>

						<input type="text" class="form-control" id="nome" name="nome" placeholder="Ex.: João Medeiros da Silva" required>

					</div>
		
					<div class="form-group col-md-6">

						<label for="usuario">Usuário</label>

						<input type="text" class="form-control" id="usuario" pattern="^[a-zA-Z0-9]*$" name="usuario" placeholder="Ex.: JoaoMdrs" required>

					</div>
		
				</div>
		
				<!-- --------------------------- -->
		
				<div class="form-row">
		
					<div class="form-group col-md-6">
						
						<label for="email">Email</label>

						<input type="email" class="form-control" id="email" name="email" placeholder="Ex.: exemplo@gmail.com" required>

					</div>
		
					<div class="form-group col-md-6">

						<label for="cpf">CPF</label>

						<input type="text" class="form-control" id="cpf" name="cpf" minlength="14" maxlength="14" placeholder="Ex.: 000.000.000-00" required>

					</div>
		
				</div>
		
				<!-- --------------------------- -->
		
				<div class="form-row">
		
					<div class="form-group col-md-6">

						<label for="telefone">Telefone</label>

						<input type="text" class="form-control" id="telefone" name="telefone" minlength="14" maxlength="15" placeholder="Ex.: (00) 90000-0000" required>

					</div>
		
				</div>
		
				<!-- --------------------------- -->
				
				<div class="form-row">
		
					<div class="form-group col-md-6">

						<label for="senha">Senha</label>

						<input type="password" class="form-control" minlength="8" id="senha" placeholder="Digite a senha..." name="senha" required>

					</div>
		
					<div class="form-group col-md-6">

						<label for="senha">Confirmar Senha</label>

						<input type="password" class="form-control" minlength="8" id="confSenha" placeholder="Confirme a senha digitada..." required>

					</div>
		
				</div>
		
				<!-- --------------------------- -->
				<div class="form-row">

					<div class="col-lg">

						<button type="submit" class="btnPadrao">Cadastrar</button>

					</div>

				</div>
				
			</form>

		</div>

		<div class="col-lg-1"></div>

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

	<script src="../dist/js/jquery.mask.js"></script>

	<script>
		// inicia funcoes importantes
		$(window).ready( function () {
			
			$("#cpf").mask("000.000.000-00");
			
			$("#telefone").mask("(00) 00000-0000");

			cadastrarUsuario();

		} );

		// permite o cadastro de usuario
		function cadastrarUsuario () {

			$("#cadUser").on("submit", function (e) {
				e.preventDefault();

				let data = $(this).serializeArray();

				if ( $("#senha").val() == $("#confSenha").val() ) {

					$.ajax({
						url: "./cadastrar_paciente_submit.php?tipo=1",
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

				} else {
				
					$("#mensagemRetorno").text("Atenção! Senhas diferentes.");
	
					$("#modal").modal("show");

					setTimeout( () => {
						$("#modal").modal("hide");
					}, 3000);

				}


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