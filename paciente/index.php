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

    /**
     * 
     * Pega os sentimentos do usuario logado
     * 
     */
    $selectSentimentos = "SELECT * FROM sentimentos WHERE id_usuario = :usuario AND apagado = 0";
    $selectSentimentos = $pdo->prepare($selectSentimentos);
    $selectSentimentos->bindValue(":usuario", $dadosUsuario["id_usuario"] );
    if ( $selectSentimentos->execute() == false || $selectSentimentos->rowCount() == 0 ) {
        $sentimentos = [];
    } else {
        $sentimentos = $selectSentimentos->fetchAll();
    }

?> 

    <div class="row d-flex justify-content-center">
		
        <div class="col-lg-12">
            
            <h2 class="card-title">Meus Sentimentos</h2>
	
        </div>

	</div>

    
	<div class="row">

        <div class="col-lg-12 card p-0">

            <?php if( $selectSentimentos->rowCount() > 0 ): ?>

                <input type="hidden" name="idUsuario" value="<?php echo $dadosUsuario["id_usuario"]; ?>">

                <input type="hidden" name="pagina" value="0">

                <div class="">

                    <table class="table table-hover table-striped table-responsive-lg" id="TabelaUser">

                        <thead>

                            <tr>

                                <th class="text-left">Categoria</th>

                                <th class="text-center">Nota</th>

                                <th class="text-center">Descrição</th>

                                <th class="text-right">Ação</th>

                            </tr>                

                        </thead>                 

                        <tbody id="sentimentos"></tbody>

                    </table>

                </div>

                <div class="row">

                    <div class="col-lg m-3 text-center">

                        <button class="btn btn-secondary mx-2" id="voltar">Voltar</button>

                        <button class="btn btn-secondary mx-2" id="proximo">Próximo</button>

                    </div>

                </div>

            <?php else: ?>

                <div class="px-5 pt-3">

                    <p class="font-20">Ops... Parece que nenhum sentimento foi encontrado. <i class="mx-2 mdi mdi-emoticon-sad"></i></p>

                </div>

            <?php endif; ?>

        </div>

    </div>


    <div class="modal" id="modalExcluir" role="dialog">

        <div class="modal-dialog modal-dialog-centered" role="document">

            <div class="modal-content">
                
                <input type="hidden" id="sentimentoExcluir">

                <div class="modal-body modal-body pt-4 pb-0">
                    
                    <p>Você deseja deletar esse sentimento?</p>

                </div>

                <div class="modal-footer border-top-0">
                    
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>

                    <button type="button" class="btn btn-primary" onclick="confirmarExclusao()">Excluir</button>

                </div>

            </div>

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

    <div class="modal" id="modal-dados" role="dialog">
        
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

    <script>
    
        // inicia funções importantes
        $(document).ready( function () {
            
            iniciaBuscaSentimentos();
        
        } );

        function iniciaBuscaSentimentos () {
            var usuario = $("input[name=idUsuario]").val();
            var pagina = $("input[name=pagina]").val();

            $("#proximo").click( function () {
                proximo();
            } );

            $("#voltar").click( function () {
                voltar();
            } );

            consultarSentimentos(usuario, pagina);
        }

        // consulta usuarios
        function consultarSentimentos (idUsuario, pagina) {
            var paginaAtual = pagina != undefined ? pagina : 0;
            
            $.ajax({
                url:"pega_sentimentos.php?tipo=1&pagina="+paginaAtual,
                type:"POST",
                data:{idUsuario: idUsuario},
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
            
                            $("#sentimentos").empty();
                            
                            for( var i = 0; i < dados.length - 1; i++ ){
                                
                                let categoria = "";

                                if ( dados[i]["categoria"] == 1 ) {
                                    categoria = "Família";
                                } else if ( dados[i]["categoria"] == 2 ) {
                                    categoria = "Amigos";
                                } else if ( dados[i]["categoria"] == 3 ) {
                                    categoria = "Alimentação";
                                } else if ( dados[i]["categoria"] == 4 ) {
                                    categoria = "Ocupação";
                                } else if ( dados[i]["categoria"] == 5 ) {
                                    categoria = "Lazer";
                                } else if ( dados[i]["categoria"] == 6 ) {
                                    categoria = "Você mesmo";
                                }

                                let nota = "";

                                if ( dados[i]["nota"] == 1 ) {
                                    nota = "Péssima";
                                } else if ( dados[i]["nota"] == 2 ) {
                                    nota = "Ruim";
                                } else if ( dados[i]["nota"] == 3 ) {
                                    nota = "Mais ou menos";
                                } else if ( dados[i]["nota"] == 4 ) {
                                    nota = "Boa";
                                } else if ( dados[i]["nota"] == 5 ) {
                                    nota = "Ótima";
                                }

                                $("#sentimentos").append(`<tr class="sentimento" data-desc="${dados[i]["descricao"].replaceAll("\"", "\'")}" data-criado="${dados[i]['data']}" data-id="${dados[i]['id']}">`+
                                    "<th class='text-left align-middle categoria'>"+categoria+"</th>"+
                                    "<th class='text-center align-middle nota'>"+nota+"</th>"+
                                    `<th class='text-center align-middle descricao' title="${dados[i]["descricao"].replaceAll("\"", "\'")}"><p style='width: 100px;text-overflow: ellipsis;overflow: hidden;white-space: nowrap; margin: auto'>${dados[i]["descricao"]}</p></th>`+
                                    "<th class='text-right align-middle acao pr-3'>"+
                                        "<a href='edicao_sentimento.php?sentimento="+dados[i]["id"]+"' title='editar sentimento' class='mx-2' style='color: black; font-size: 18px'><i class='mdi mdi-pencil'></i></a>"+
                                        "<a title='visualizar informações' onclick='exibirModal(this)' class='mx-2' style='color: black; font-size: 18px'><i class='mdi mdi-magnify'></i></a>"+
                                        "<a onclick='deletarSentimento("+dados[i]["id"]+")' title='excluir sentimento' class='mx-2' style='cursor: pointer; color: red; font-size: 18px'><i class='mdi mdi-delete'></i></a>"+
                                    "</th>"+
                                "</tr>");

                            }
            
                        } else if (typeof dados.error != "undefined") {

                            $("#sentimentos").empty();

                            $("#sentimentos").append('<tr>'+
                                '<th class="text-left">—</th>'+
                                '<th class="text-center">—</th>'+
                                '<th class="text-center">—</th>'+
                                '<th class="text-right">—</th>'+
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
            var usuario = $("input[name=idUsuario]").val();
            var pagina = parseInt( $("input[name=pagina]").val() ) + 1;
            consultarSentimentos(usuario, pagina);
            $("input[name=pagina]").val( parseInt(pagina) );
        }

        // volta para a pagina anterior
        function voltar () {
            var usuario = $("input[name=idUsuario]").val();
            var pagina = parseInt( $("input[name=pagina]").val() ) - 1;
            consultarSentimentos(usuario, pagina);
            $("input[name=pagina]").val( parseInt(pagina) );
        }

        // prepara informacoes do paciente para exclusao
        function deletarSentimento (sentimento) {

            $("#sentimentoExcluir").val(sentimento);

            $("#modalExcluir").modal("show");
 
        
        }

        // confirma a exclusao do paciente
        function confirmarExclusao () {

            $("#modalExcluir").modal("hide");

            let usuario = $("input[name=idUsuario]").val();

            let sentimento = $("#sentimentoExcluir").val();

            $.ajax({
                url:"excluir_sentimento_submit.php",
                type:"POST",
                data:{
                    usuario,
                    sentimento
                },
                success:function(res) {

                    try {

                        let response = JSON.parse(res);

                        if ( typeof response.error != "undefined" ) {

                            $("#mensagemRetorno").text(response.error);
	
                            $("#modal").modal("show");
                    
                        } else if ( typeof response.success != "undefined" ) {
                            
                            $("#mensagemRetorno").text(response.success);
									
                            $("#modal").modal("show");

                            $("#fecharModal").click( () => {
                                setTimeout( () => {
                                    window.location.reload();
                                }, 1000);
                            });

                        }


                    } catch (e) {
                        return null;
                    }

                },
                error:function(){

                    $("#mensagemRetorno").text(response.error);
	
                    $("#modal").modal("show");

                },
            });
        }

        function exibirModal(elemento) {

            let elementoPai = $(elemento).closest("tr.sentimento");

            let categoria = $( $(elementoPai).children()[0] ).text();

            let nota = $( $(elementoPai).children()[1] ).text();

            let data = $(elementoPai).attr("data-criado");

            let descricao = $(elementoPai).attr("data-desc") || "Nenhuma descrição.";

            $("#categoria").html(`<strong>Categoria:</strong> ${categoria}`);			
            $("#nota").html(`<strong>Nota:</strong> ${nota}`);			
            $("#data").html(`<strong>Data:</strong> ${data}`);			
            $("#descricao").html(`<strong>Descrição:</strong> ${descricao.replaceAll("\'","\"")}`);			

            $("#modal-dados").modal("show");

        }

    </script>

<?php
   
    /**
     * 
     * Importacao do rodape da pagina
     * 
     */
    include "rod.php";
    
?> 