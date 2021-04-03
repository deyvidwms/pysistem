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
	 * Importacao do cabecalho da pagina
	 * 
	 */
	include "cab.php";

	/**
	 * 
	 * Verifica se a url possui o id do paciente
	 * 
	 */
	if ( isset( $_GET["paciente"] ) && !empty( $_GET["paciente"] ) ) {

		$usuario = $_GET["paciente"];

		/**
		 * 
		 * Pega as informacoes do paciente
		 * 
		 */
		$getInfoPaciente = "SELECT * FROM usuarios 
							JOIN pacientes_dos_medicos ON pacientes_dos_medicos.id_usuario_paciente = usuarios.id_usuario 
							WHERE pacientes_dos_medicos.id_usuario_medico = :medico AND usuarios.id_usuario = :usuario AND usuarios.apagado = 0";
		$getInfoPaciente = $pdo->prepare($getInfoPaciente);
		$getInfoPaciente->bindValue(":medico", $dadosUsuario["id_usuario"]);
		$getInfoPaciente->bindValue(":usuario", $usuario);
		if ( $getInfoPaciente->execute() == false ) {

			echo "<script> window.location.href='./index.php'; </script>";

		} else if ( $getInfoPaciente->rowCount() == 0 ) {

			echo "<script> window.location.href='./index.php'; </script>";

		} else {

			$dadosPaciente = $getInfoPaciente->fetch();

			/**
			 * 
			 * Pega as informações do sentimentos cadastrados
			 * na semana atual
			 * 
			 */
			$selectSentimentosSemanal = "SELECT nota_sentimento as nota, id_categoria_dos_sentimentos as categoria, DAYNAME(criado) as dia FROM sentimentos WHERE id_usuario = :usuario AND YEARWEEK(criado, 0) = YEARWEEK(CURDATE(), 0) AND apagado = 0";
			$selectSentimentosSemanal = $pdo->prepare($selectSentimentosSemanal);
			$selectSentimentosSemanal->bindValue( ":usuario", $dadosPaciente["id_usuario"] );
			if ( $selectSentimentosSemanal->execute() == false ) {
				echo "<script> window.location.href='./index.php'; </script>";
			} else if ( $selectSentimentosSemanal->rowCount() > 0 ) {
				
				$dadosSentimentosSemanal = $selectSentimentosSemanal->fetchAll();

				$arrayAuxSemanal = array(
					1 => [],
					2 => [],
					3 => [],
					4 => [],
					5 => [],
					6 => [],
				);

				$dias = [
					"sunday" => 1,
					"monday" => 2,
					"tuesday" => 3,
					"wednesday" => 4,
					"thursday" => 5,
					"friday" => 6,
					"saturday" => 7,
				];

				foreach( $dadosSentimentosSemanal as $sentimento ):
					
					array_push( 
						$arrayAuxSemanal[ $sentimento["categoria"] ], [
							"dia" => $dias[ strtolower( $sentimento["dia"] ) ],
							"nota" => $sentimento["nota"]
						]
					);

				endforeach;

			}

			/**
			 * 
			 * Pega as informações do sentimentos cadastrados
			 * no mes atual
			 * 
			 */
			$selectSentimentosMensal = "SELECT AVG(nota_sentimento) as mediaCategoria, id_categoria_dos_sentimentos 
									FROM sentimentos 
									WHERE id_usuario = :usuario AND YEAR(criado) = YEAR(CURRENT_TIMESTAMP) AND MONTH(criado) = MONTH(CURRENT_TIMESTAMP) AND apagado = 0
									GROUP BY id_categoria_dos_sentimentos ORDER BY id_categoria_dos_sentimentos ASC";
			$selectSentimentosMensal = $pdo->prepare($selectSentimentosMensal);
			$selectSentimentosMensal->bindValue( ":usuario", $dadosPaciente["id_usuario"] );
			if ( $selectSentimentosMensal->execute() == false ) {
				echo "<script> window.location.href='./index.php'; </script>";
			} else if ( $selectSentimentosMensal->rowCount() > 0 ) {
				
				$dadosSentimentosMensal = $selectSentimentosMensal->fetchAll();

				$arrayAuxMensal = [];

				foreach( $dadosSentimentosMensal as $mediaCategoria ):

					$arrayAuxMensal[ $mediaCategoria["id_categoria_dos_sentimentos"] ] = number_format($mediaCategoria["mediaCategoria"], 2);

				endforeach;

			}


		}

	} else {

		header("Location: index.php");

	}

?> 
	<link href="../assets/libs/chartist/dist/chartist.min.css" rel="stylesheet">

	<div class="row">
	
		<div class="col-lg">
			
			<div class="card-body">

				<h2 class="card-title">Relatório</h2>

				<h4 id="nomePaciente"><?php echo utf8_encode( $dadosPaciente["nome"] ); ?></h4>

			</div>
			
			<div class="card-body">

				<ul class="nav nav-tabs" role="tablist">
					
					<li class="nav-item"><a class="nav-link active" data-toggle="tab" onclick="gerarGraficoSemanal()" href="#semanal" role="tab">Semanal</a></li>
					
					<li class="nav-item"><a class="nav-link" data-toggle="tab" onclick="gerarGraficoMensal()" href="#mensal" role="tab">Mensal</a></li>

				</ul><!-- Tab panes -->

				<div class="tab-content">

					<div class="tab-pane bg-white active p-3" id="semanal" role="tabpanel">

						<div class="row">
							
							<div class="col-lg-12 pt-5 pb-3">
								
								<div id="graficoSemanal"></div>

								<div class="legenda-grafico">

									<label for=""> Familia</label>
									<label for=""> Amigos</label>
									<label for=""> Alimentação</label>
									<label for=""> Ocupação</label>
									<label for=""> Lazer</label>
									<label for=""> Você Mesmo</label>

								</div>

							</div>

						</div>

						<div class="row">

							<div class="col-lg-12 p-0 mt-5">

								<input type="hidden" name="usuario" value="<?php echo $usuario; ?>">

								<input type="hidden" name="pagina" value="0">

								<table class="table table-hover" id="TabelaUser">

									<thead>

										<tr>
											<th class="text-left">Nota</th>
											<th class="text-center">Categoria</th>
											<th class="text-center">Data</th>
											<th class="text-right">Ação</th>
										</tr>

									</thead>

									<tbody id="sentimentoSemanal">

										<tr>
											<th>Ótima</th>
											<th>Família</th>
											<th>05/03/2021</th>
											<th><i class="mdi mdi-magnify"></i></th>
										</tr>

									</tbody>

								</table>

							</div>

						</div>

						<div class="row">

							<div class="col-lg m-3 text-center">

								<button class="btn btn-secondary mx-2" id="voltar">Voltar</button>

								<button class="btn btn-secondary mx-2" id="proximo">Próximo</button>

							</div>

						</div>

					</div>


					<div class="tab-pane bg-white p-3" id="mensal" role="tabpanel">

						<div class="row">
							
							<div class="col-lg-12 pt-5">
							
								<input type="hidden" id="mediaFamilia" value="<?php echo isset( $arrayAuxMensal[1] ) && !empty( $arrayAuxMensal[1] ) ?  $arrayAuxMensal[1] : 0; ?>">
								<input type="hidden" id="mediaAmigos" value="<?php echo isset( $arrayAuxMensal[2] ) && !empty( $arrayAuxMensal[2] ) ?  $arrayAuxMensal[2] : 0; ?>">
								<input type="hidden" id="mediaAlimentacao" value="<?php echo isset( $arrayAuxMensal[3] ) && !empty( $arrayAuxMensal[3] ) ?  $arrayAuxMensal[3] : 0; ?>">
								<input type="hidden" id="mediaOcupacao" value="<?php echo isset( $arrayAuxMensal[4] ) && !empty( $arrayAuxMensal[4] ) ?  $arrayAuxMensal[4] : 0; ?>">
								<input type="hidden" id="mediaLazer" value="<?php echo isset( $arrayAuxMensal[5] ) && !empty( $arrayAuxMensal[5] ) ?  $arrayAuxMensal[5] : 0; ?>">
								<input type="hidden" id="mediaVoceMesmo" value="<?php echo isset( $arrayAuxMensal[6] ) && !empty( $arrayAuxMensal[6] ) ?  $arrayAuxMensal[6] : 0; ?>">

								<div id="graficoMensal"></div>

								<div class="legenda-grafico">

									<label for=""> Familia</label>
									<label for=""> Amigos</label>
									<label for=""> Alimentação</label>
									<label for=""> Ocupação</label>
									<label for=""> Lazer</label>
									<label for=""> Você Mesmo</label>

								</div>

							</div>

						</div>

						<div class="row">

							<div class="col-lg-12 p-0 mt-5">

								<input type="hidden" name="idUsuario" value="<?php echo $usuario; ?>">

								<input type="hidden" name="paginaMens" value="0">

								<table class="table table-hover" id="TabelaUser">

									<thead>

										<tr>
											<th class="text-left">Nota</th>
											<th class="text-center">Categoria</th>
											<th class="text-center">Data</th>
											<th class="text-right">Ação</th>
										</tr>

									</thead>

									<tbody id="sentimentoMensal"></tbody>

								</table>

							</div>

						</div>

						<div class="row">

							<div class="col-lg m-3 text-center">

								<button class="btn btn-secondary mx-2" id="voltarMens">Voltar</button>

								<button class="btn btn-secondary mx-2" id="proximoMens">Próximo</button>

							</div>

						</div>

					</div>

				</div>

			</div>

		</div>

	</div>

	<div class="modal" id="modal" role="dialog">
        
		<div class="modal-dialog modal-dialog-centered" role="document">
		
            <div class="modal-content">

				<div class="modal-header">

					<h5 class="modal-title m-0">Dados do Sentimento</h5>
					
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">

						<span aria-hidden="true">&times;</span>

					</button>

				</div>

                <div class="modal-body pt-4 pb-0">
		
					<div class="row">
						
						<div class="col-lg-6 text-left">
						
							<p id="categoria"><strong>Categoria:</strong> Família</p>

							<p id="nota"><strong>Nota:</strong> Boa</p>

						</div>

						<div class="col-lg-6 text-left text-lg-right">
						
							<p id="data"><strong>Data:</strong> 05/03/2021</p>

						</div>

						<div class="col-lg-12 text-left">

							<p id="descricao" style="line-break: anywhere;"><strong>Descrição:</strong> Descrição</p>

						</div>

					</div>
		
                </div>
		
            </div>
		
        </div>

	</div>
     
	<!--chartis chart-->
	<script src="../assets/libs/chartist/dist/chartist.min.js"></script>

	<script>

		// inicia funcoes importantes
		$(window).ready( function () {

			gerarGraficoSemanal();

			iniciaBuscaSentimentosSemanal();

			iniciaBuscaSentimentosMensal();

		});
		
		// gera o grafico semanal de sentimentos do paciente
		function gerarGraficoSemanal () {

			let data = {
				labels: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
				series: [

					<?php 
						
						if ( $selectSentimentosSemanal->rowCount() > 0 ):

							for( $i = 1; $i <= count( $arrayAuxSemanal ); $i++ ):

								$arrayAux = [0,0,0,0,0,0,0];
			
								if ( count( $arrayAuxSemanal[$i] ) > 0 ):
								
								for ( $j = 0; $j < count( $arrayAuxSemanal[$i] ); $j++ ):

									$arrayAux[ $arrayAuxSemanal[$i][$j]["dia"] - 1 ] = $arrayAuxSemanal[$i][$j]["nota"];
									
								endfor;
								
									echo '['.$arrayAux[0].','.$arrayAux[1].','.$arrayAux[2].','.$arrayAux[3].','.$arrayAux[4].','.$arrayAux[5].','.$arrayAux[6].'],'.PHP_EOL;
								
								else:
								
									echo '[0,0,0,0,0,0,0],'.PHP_EOL;
								
								endif;
								
							endfor; 

						else:
					?>

						[0,0,0,0,0,0,0],
						[0,0,0,0,0,0,0],
						[0,0,0,0,0,0,0],
						[0,0,0,0,0,0,0],
						[0,0,0,0,0,0,0],
						[0,0,0,0,0,0,0],

					<?php 
						endif;

					?>

				]
			};

			let options = {
				high: 5,
				low: 0,
				seriesBarDistance: 100
			};

			new Chartist.Line('#graficoSemanal', data, options);
		}

		// gera o grafico mensal de sentimentos do paciente
		function gerarGraficoMensal () {
			
			let familia = $("#mediaFamilia").val();
			let amigos = $("#mediaAmigos").val();
			let alimentacao = $("#mediaAlimentacao").val();
			let ocupacao = $("#mediaOcupacao").val();
			let lazer = $("#mediaLazer").val();
			let voceMesmo = $("#mediaVoceMesmo").val();

			let data = {
				labels: ['Familia', 'Amigos', 'Alimentação', 'Ocupação', 'Lazer', 'Você Mesmo'],
				series: [familia, amigos, alimentacao, ocupacao, lazer, voceMesmo]
			};

			let options = {
				high: 5,
				low: 0,
				distributeSeries: true
			};

			new Chartist.Bar('#graficoMensal', data, options);
		}
    
		// inicia a busca de sentimentos semanal
        function iniciaBuscaSentimentosSemanal () {
            var usuario = $("input[name=usuario]").val();
            var pagina = $("input[name=pagina]").val();

            $("#proximo").click( function () {
                proximo();
            } );

            $("#voltar").click( function () {
                voltar();
            } );

            consultarSentimentoSemanal(usuario, pagina);
        }

        // consulta sentimentos semanais
        function consultarSentimentoSemanal (usuario, pagina) {
            var paginaAtual = pagina != undefined ? pagina : 0;
            
            $.ajax({
                url:"pega_sentimentos.php?tipo=1&pagina="+paginaAtual,
                type:"POST",
                data:{usuario: usuario},
                success: function (res) {
                    try {

                        var dados = JSON.parse(res);

                        var tam = dados.length;
                        
                        if( typeof dados.error == "undefined" ) {

                            if(dados[tam-1]["limite"] == true ){
                                $("#proximo").attr("disabled", "true");
                            }else if(dados[tam-1]["limite"] == false){
                                $("#proximo").removeAttr("disabled");
                            }
            
                            if (pagina == 0) {
                                $("#voltar").attr("disabled", "disabled");
                            } else {
                                $("#voltar").removeAttr("disabled");
                            }
            
                            $("#sentimentoSemanal").empty();
                            
                            for( var i = 0; i < dados.length - 1; i++ ){

								let nota = '';

								if ( dados[i]["nota"] == 1 )
									nota = 'Péssima';
								else if ( dados[i]["nota"] == 2 )
									nota = 'Ruim';
								else if ( dados[i]["nota"] == 3 )
									nota = 'Mais ou Menos';
								else if ( dados[i]["nota"] == 4 )
									nota = 'Boa';
								else if ( dados[i]["nota"] == 5 )
									nota = 'Ótima';

								
								let categoria = '';

								if ( dados[i]["categoria"] == 1 )
									categoria = 'Família';
								else if ( dados[i]["categoria"] == 2 )
									categoria = 'Família';
								else if ( dados[i]["categoria"] == 3 )
									categoria = 'Alimentação';
								else if ( dados[i]["categoria"] == 4 )
									categoria = 'Ocupação';
								else if ( dados[i]["categoria"] == 5 )
									categoria = 'Lazer';
								else if ( dados[i]["categoria"] == 6 )
									categoria = 'Você Mesmo';
								

                                $("#sentimentoSemanal").append(`<tr class="sentimento" data-desc="${dados[i]["descricao"].replaceAll("\"", "\'")}"  data-id="${dados[i]['id']}">`+
                                    "<th class='text-left align-middle nota'>"+nota+"</th>"+
                                    "<th class='text-center align-middle categoria'>"+categoria+"</th>"+
                                    "<th class='text-center align-middle data'>"+dados[i]["data"]+"</th>"+
                                    "<th class='text-right align-middle acao pr-3'>"+
                                        "<a title='visualizar informações' onclick='exibirModal(this)' class='mx-2' style='color: black; font-size: 18px'><i class='mdi mdi-magnify'></i></a>"+
                                    "</th>"+
                                "</tr>");

                            }
            
                        } else if (typeof dados.error != "undefined") {

                            $("#sentimentoSemanal").empty();

                            $("#sentimentoSemanal").append('<tr>'+
                                '<th>—</th>'+
                                '<th>—</th>'+
                                '<th>—</th>'+
                                '<th>—</th>'+
                            '</tr>');

                        }

                    } catch (e) {
                        return null;
                    }
                }
            });

        }

        // passa para a proxima pagina
        function proximo () {
            var usuario = $("input[name=usuario]").val();
            var pagina = parseInt( $("input[name=pagina]").val() ) + 1;
            consultarSentimentoSemanal(usuario, pagina);
            $("input[name=pagina]").val( parseInt(pagina) );
        }

        // volta para a pagina anterior
        function voltar () {
            var usuario = $("input[name=usuario]").val();
            var pagina = parseInt( $("input[name=pagina]").val() ) - 1;
            consultarSentimentoSemanal(usuario, pagina);
            $("input[name=pagina]").val( parseInt(pagina) );
        }

		// inicia a busca de sentimentos mensal
		function iniciaBuscaSentimentosMensal () {
            var usuario = $("input[name=idUsuario]").val();
            var pagina = $("input[name=paginaMens]").val();

            $("#proximoMens").click( function () {
                proximoMens();
            } );

            $("#voltarMens").click( function () {
                voltarMens();
            } );

            consultarSentimentoMensal(usuario, pagina);
        }

        // consulta sentimentos mensais
        function consultarSentimentoMensal (idUsuario, pagina) {
            var paginaAtual = pagina != undefined ? pagina : 0;
            
            $.ajax({
                url:"pega_sentimentos.php?tipo=2&pagina="+paginaAtual,
                type:"POST",
                data:{idUsuario: idUsuario},
                success: function (res) {
                    try {

                        var dados = JSON.parse(res);

                        var tam = dados.length;
                        
                        if( typeof dados.error == "undefined" ) {

                            if(dados[tam-1]["limite"] == true ){
                                $("#proximoMens").attr("disabled", "true");
                            }else if(dados[tam-1]["limite"] == false){
                                $("#proximoMens").removeAttr("disabled");
                            }
            
                            if (pagina == 0) {
                                $("#voltarMens").attr("disabled", "disabled");
                            } else {
                                $("#voltarMens").removeAttr("disabled");
                            }
            
                            $("#sentimentoMensal").empty();
                            
                            for( var i = 0; i < dados.length - 1; i++ ){

								let nota = '';

								if ( dados[i]["nota"] == 1 )
									nota = 'Péssima';
								else if ( dados[i]["nota"] == 2 )
									nota = 'Ruim';
								else if ( dados[i]["nota"] == 3 )
									nota = 'Mais ou Menos';
								else if ( dados[i]["nota"] == 4 )
									nota = 'Boa';
								else if ( dados[i]["nota"] == 5 )
									nota = 'Ótima';

								
								let categoria = '';

								if ( dados[i]["categoria"] == 1 )
									categoria = 'Família';
								else if ( dados[i]["categoria"] == 2 )
									categoria = 'Família';
								else if ( dados[i]["categoria"] == 3 )
									categoria = 'Alimentação';
								else if ( dados[i]["categoria"] == 4 )
									categoria = 'Ocupação';
								else if ( dados[i]["categoria"] == 5 )
									categoria = 'Lazer';
								else if ( dados[i]["categoria"] == 6 )
									categoria = 'Você Mesmo';

                                $("#sentimentoMensal").append(`<tr class="sentimento" data-desc="${dados[i]["descricao"].replaceAll("\"", "\'")}" data-id="${dados[i]['id']}">`+
                                    "<th class='text-left nota'>"+nota+"</th>"+
                                    "<th class='text-center categoria'>"+categoria+"</th>"+
                                    "<th class='text-center data'>"+dados[i]["data"]+"</th>"+
                                    "<th class='text-right acao pr-3'>"+
                                        "<a title='visualizar informações' onclick='exibirModal(this)' style='color: black'><i class='mdi mdi-magnify'></i></a>"+
                                    "</th>"+
                                "</tr>");

                            }
            
                        } else if (typeof dados.error != "undefined") {

                            $("#sentimentoMensal").empty();

                            $("#sentimentoMensal").append('<tr>'+
                                '<th>—</th>'+
                                '<th>—</th>'+
                                '<th>—</th>'+
                                '<th>—</th>'+
                            '</tr>');

                        }

                    } catch (e) {
                        return null;
                    }
                }
            });

        }

        // passa para a proxima pagina
        function proximoMens () {
            var usuario = $("input[name=idUsuario]").val();
            var pagina = parseInt( $("input[name=paginaMens]").val() ) + 1;
            consultarSentimentoMensal(usuario, pagina);
            $("input[name=paginaMens]").val( parseInt(pagina) );
        }

        // volta para a pagina anterior
        function voltarMens () {
            var usuario = $("input[name=idUsuario]").val();
            var pagina = parseInt( $("input[name=paginaMens]").val() ) - 1;
            consultarSentimentoMensal(usuario, pagina);
            $("input[name=paginaMens]").val( parseInt(pagina) );
        }

		// exibe o modal com as informacoes do sentimento
		function exibirModal(elemento) {

			let elementoPai = $(elemento).closest("tr.sentimento");

			let nota = $( $(elementoPai).children()[0] ).text();
			
			let categoria = $( $(elementoPai).children()[1] ).text();
			
			let data = $( $(elementoPai).children()[2] ).text();
			
			let descricao = $(elementoPai).attr("data-desc") || "Nenhuma descrição.";
		
			$("#categoria").html(`<strong>Categoria:</strong> ${categoria}`);			
			$("#nota").html(`<strong>Nota:</strong> ${nota}`);			
			$("#data").html(`<strong>Data:</strong> ${data}`);			
			$("#descricao").html(`<strong>Descrição:</strong> ${descricao.replaceAll("\'","\"")}`);			

			$("#modal").modal("show");

		}

    </script>

<?php 

	/**
	 * 
	 * Importacao do arquivo de 
	 * rodape da pagina
	 * 
	 */
	include "rod.php";
	
?> 