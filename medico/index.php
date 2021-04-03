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
     * Pega os pacientes do usuario logado
     * 
     */
    $selectPacientes = "SELECT * FROM usuarios JOIN pacientes_dos_medicos ON pacientes_dos_medicos.id_usuario_paciente = usuarios.id_usuario WHERE id_usuario_medico = :usuario AND usuarios.apagado = 0 AND tipo = 0";
    $selectPacientes = $pdo->prepare($selectPacientes);
    $selectPacientes->bindValue(":usuario", $_SESSION["id"]);
    if ( $selectPacientes->execute() == false || $selectPacientes->rowCount() == 0 ) {
        $pacientes = [];
    } else {
        $pacientes = $selectPacientes->fetchAll();
    }

?> 
    <div class="row d-flex justify-content-center">

        <div class="col-lg-12">
        
            <h2 class="card-title">Meus Pacientes</h2>

		</div>

	</div>

	<div class="row">

        <div class="col-lg-12 card p-0">

            <?php if($selectPacientes->rowCount() > 0 ): ?>
                
                <input type="hidden" name="idUsuario" value="<?php echo $dadosUsuario["id_usuario"]; ?>">

                <input type="hidden" name="pagina" value="0">

                <table class="table table-hover table-striped table-responsive-lg" id="TabelaUser">

                    <thead>

                        <tr>

                            <th class="text-left">Nome</th>

                            <th class="text-center">Usuário</th>

                            <th class="text-center">Email</th>

                            <th class="text-center">Telefone</th>

                            <th class="text-center">CPF</th>

                            <th class="text-right">Ação</th>

                        </tr>   

                    </thead>              

                    <tbody id="pacientes"> 
                                       
                    </tbody>

                </table>
        
                <div class="row">

                    <div class="col-lg m-3 text-center">

                        <button class="btn btn-secondary mx-2" id="voltar">Voltar</button>

                        <button class="btn btn-secondary mx-2" id="proximo">Próximo</button>

                    </div>

                </div>

            <?php else: ?>

                <div class="px-5 pt-3">

                    <p class="font-20">Ops... Parece que nenhum paciente foi encontrado. <i class="mx-2 mdi mdi-emoticon-sad"></i></p>

                </div>

            <?php endif; ?>

        </div>

    </div>


    <div class="modal" id="modalExcluir" role="dialog">

        <div class="modal-dialog modal-dialog-centered" role="document">

            <div class="modal-content">
                
                <input type="hidden" id="usuarioExcluir">

                <div class="modal-body modal-body pt-4 pb-0">

                    <p>Você deseja deletar esse paciente?.</p>

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


    <script>
    
        // inicia funções importantes
        $(document).ready( function () {
            
            iniciaBuscaUsuarios();
        
        } );

        // inicia a busca por usuarios 
        function iniciaBuscaUsuarios () {
            var usuario = $("input[name=idUsuario]").val();
            var pagina = $("input[name=pagina]").val();

            $("#proximo").click( function () {
                proximo();
            } );

            $("#voltar").click( function () {
                voltar();
            } );

            consultarUsuarios(usuario, pagina);
        }

        // consulta usuarios e exibe eles na tela
        function consultarUsuarios (idUsuario, pagina) {
            var paginaAtual = pagina != undefined ? pagina : 0;
            
            $.ajax({
                url:"pega_pacientes.php?tipo=1&pagina="+paginaAtual,
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
            
                            $("#pacientes").empty();
                            
                            for( var i = 0; i < dados.length - 1; i++ ){

                                $("#pacientes").append("<tr class='usuario' data-id='"+dados[i]['id']+"'>"+
                                    "<th class='text-left align-middle nome'>"+dados[i]["nome"]+"</th>"+
                                    "<th class='text-center align-middle usuario'>"+dados[i]["usuario"]+"</th>"+
                                    "<th class='text-center align-middle plano'>"+dados[i]["email"]+"</th>"+
                                    "<th class='text-center align-middle telefone'>"+dados[i]["telefone"]+"</th>"+
                                    "<th class='text-center align-middle cpf'>"+dados[i]["cpf"]+"</th>"+
                                    "<th class='text-right align-middle acao pr-3'>"+
                                        "<a href='detalhes_paciente.php?paciente="+dados[i]["id"]+"' class='mx-2' title='visualizar informações' style='color: black; font-size: 18px'><i class='mdi mdi-magnify'></i></a>"+
                                        "<a href='edicao_paciente.php?paciente="+dados[i]["id"]+"' class='mx-2 btnVisualizar' title='editar paciente' style='color: black; font-size: 18px'><i class='mdi mdi-pencil'></i></a>"+
                                        "<a onclick='deletarPaciente("+dados[i]["id"]+")' class='mx-2 btnExcluir' title='excluir paciente' style='color: red; font-size: 18px'><i class='mdi mdi-delete'></i></a>"+
                                    "</th>"+
                                "</tr>");

                            }
            
                        } else if (typeof dados.error != "undefined") {

                            $("#pacientes").empty();

                            $("#pacientes").append('<tr>'+
                                '<th class="text-left">—</th>'+
                                '<th class="text-center">—</th>'+
                                '<th class="text-center">—</th>'+
                                '<th class="text-center">—</th>'+
                                '<th class="text-center">—</th>'+
                                '<th class="text-right">—</th>'+
                            '</tr>');

                            $("#proximo").attr("disabled", "true");

                            $("#voltar").attr("disabled", "disabled");

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
            consultarUsuarios(usuario, pagina);
            $("input[name=pagina]").val( parseInt(pagina) );
        }

        // volta para a pagina anterior
        function voltar () {
            var usuario = $("input[name=idUsuario]").val();
            var pagina = parseInt( $("input[name=pagina]").val() ) - 1;
            consultarUsuarios(usuario, pagina);
            $("input[name=pagina]").val( parseInt(pagina) );
        }

        // prepara informacoes do paciente para exclusao
        function deletarPaciente (usuarioExcluir) {

            $("#usuarioExcluir").val(usuarioExcluir);

            $("#modalExcluir").modal("show");
 
        }

        // confirma a exclusao do paciente
        function confirmarExclusao () {

            $("#modalExcluir").modal("hide");

            let usuario = $("input[name=idUsuario]").val();

            let usuarioExcluir = $("#usuarioExcluir").val();

            $.ajax({
                url:"excluir_paciente_submit.php",
                type:"POST",
                data:{
                    usuario,
                    usuarioExcluir
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

    </script>

<?php 

    include "./rod.php";

?> 