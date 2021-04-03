<?php 

    /**
     * 
     * Importacao do arquivo de conexao 
     * com o banco de dados
     * 
     */
    include "../config/config.php";


    if ( isset( $_GET['tipo'] ) && $_GET["tipo"] == 1 ){

        /**
         * 
         * Verifica se o id do usuario foi
         * enviado na requisicao
         * 
         */
        if ( isset($_POST["idUsuario"]) && !empty($_POST["idUsuario"]) ) {

            $pagina_atual = ( isset($_GET["pagina"]) && !empty($_GET["pagina"]) ? $_GET["pagina"] : 0 ) * 10;
    
            $usuario = $_POST["idUsuario"];
    
            /**
             * 
             * Pega o total de pacientes do medico
             * 
             */
            $pegaTotal = "SELECT count(*) as Total FROM usuarios 
                            JOIN pacientes_dos_medicos ON pacientes_dos_medicos.id_usuario_paciente = usuarios.id_usuario 
                            WHERE id_usuario_medico = :usuario AND usuarios.apagado = 0 AND usuarios.tipo = 0 ";
            $pegaTotal = $pdo->prepare($pegaTotal);
            $pegaTotal->bindValue(":usuario", $usuario);
            if ( $pegaTotal->execute() != false ) { 
                
                if ( $pegaTotal->rowCount() > 0 ) {
                                    
                    $total = $pegaTotal->fetch();

                }

            } else {
                
                $dados = array(
                    "error" => "Erro ao tentar fazer busca por pacientes." 
                );
                
                echo json_encode($dados);
                
                exit;

            }
            
            /**
             * 
             * Pega os pacientes do medico
             * 
             */
            $pegaUsuarios = "SELECT * FROM usuarios 
                            JOIN pacientes_dos_medicos ON pacientes_dos_medicos.id_usuario_paciente = usuarios.id_usuario 
                            WHERE id_usuario_medico = :usuario AND usuarios.apagado = 0 AND usuarios.tipo = 0 ORDER BY nome ASC LIMIT :pagina_atual, 10";
            $pegaUsuarios = $pdo->prepare($pegaUsuarios);
            $pegaUsuarios->bindValue(":usuario", $usuario);
            $pegaUsuarios->bindValue(":pagina_atual", (int) trim($pagina_atual), PDO::PARAM_INT);
            $sucesso = $pegaUsuarios->execute();
    
            /**
             * 
             * Se a requisicao for bem sucedida
             * vai adicionar os pacientes a um array
             * e vai retornar este array em json
             * 
             */
            if( $sucesso == true){

                $dados = array();
    
                /**
                 * 
                 * Caso obtenha algum 
                 * resultado adiciona ao array
                 * 
                 */
                if($pegaUsuarios->rowCount() > 0){
    
                    $consulta = $pegaUsuarios->fetchAll();
    
                    foreach( $consulta as $usuario ):
    
                        array_push($dados, [
                            "id" => $usuario["id_usuario"],
                            "nome" => utf8_encode( $usuario["nome"] ),
                            "usuario" => $usuario["usuario"],
                            "email" => $usuario["email"],
                            "cpf" => $usuario["cpf"],
                            "telefone" => $usuario["telefone"],
                        ]);
        
                    endforeach;
        
                    if( $total["Total"] - ( ( $_GET["pagina"] + 1 ) * 10 ) > 0 ){
                        array_push( $dados, [ "limite" => false ] );
                    }else{
                        array_push( $dados, [ "limite" => true ] );
                    }
    
                    /**
                     * 
                     * Retorna os valores em formato json
                     * 
                     */
                    echo json_encode($dados);
                    
                    exit;
    
                /**
                 * 
                 * Caso nao haja nenhum resultado
                 * eh retornado uma mensagem em formato json
                 * 
                 */
                } else {

                    $dados = array(
                        "error" => "Nenhum resultado encontrado.",
                    );
                    
                    echo json_encode($dados);
                    
                    exit;
                }
                
            /**
             * 
             * Retorna uma mensagem de 
             * erro em formato json
             * 
             */
            } else { 
                
                $dados = array(
                    "error" => "Erro ao tentar fazer busca por pacientes.",
                );
                
                echo json_encode($dados);
                
                exit;
                
            }
    
        /**
         * 
         * Caso nao tenha sido enviado 
         * eh retornada uma mensagem de erro
         * 
         */
        } else {
            
            echo json_encode(
                [
                    "error" => "Falha ao buscar informações."
                ]
            );

            exit;

        }

    } else {
        header("Location: ./");
    }
    


?>