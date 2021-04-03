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
	
	/**
	 * 
	 * Pega o nome do medico do paciente logado
	 * 
	 */
	$getMedico = "SELECT nome FROM usuarios JOIN pacientes_dos_medicos ON id_usuario = id_usuario_medico WHERE id_usuario_paciente = :paciente";
	$getMedico = $pdo->prepare($getMedico);
	$getMedico->bindValue(":paciente", $dadosUsuario["id_usuario"]);
	if ( $getMedico->execute() == false ) {
		$medico = "Erro ao buscar médico";
	} else if ( $getMedico->rowCount() == 0 ) {
		$medico = "Erro ao buscar médico";
	} else if ( $getMedico->rowCount() > 0 ) {
		$dadosMedico = $getMedico->fetch();
		$medico = ucwords( strtolower( utf8_encode( $dadosMedico["nome"] ) ) );
	}

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

		<div class="form-group col-md-6">

			<label>Médico:</label>

			<p><?php echo $medico; ?></p>    

		</div>

	</div>


<?php

	/**
	 * 
	 * Importa o rodape da pagina
	 * 
	 */
	include "rod.php"; 

?> 
