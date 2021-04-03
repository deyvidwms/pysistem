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
	include "cab.php";
	
?> 

	<div class="card-body">
        
		<h2 class="card-title">Meus dados</h2>

	</div>

	<div class="row" id="divDados">

		<div class="form-group col-md-6">
			
			<label>Nome:</label>

			<p><?php echo ucwords( strtolower( utf8_encode( $dadosUsuario["nome"] ) ) ); ?></p>	
			
		</div>

		<div class="form-group col-md-6">

			<label>Usuário:</label>
		
			<p><?php echo $dadosUsuario["usuario"]; ?></p>	
		
		</div>

	</div>

	<div class="row" id="divDados">

		<div class="form-group col-md-6">

			<label>Email:</label>
		
			<p><?php echo $dadosUsuario["email"]; ?></p>	
		
		</div>
		
		<div class="form-group col-md-6">

			<label>Telefone:</label>
		
			<p><?php echo $dadosUsuario["telefone"]; ?></p>	
		
		</div>

	</div>

	<div class="row" id="divDados">
		
		<div class="form-group col-md-6">

			<label>CPF:</label>
			
			<p><?php echo $dadosUsuario["cpf"]; ?></p>	

		</div>
		
	</div>

<?php 

	/**
	 * 
	 * Importacao do rodape da pagina
	 * 
	 */
	include "rod.php";
	
?> 
