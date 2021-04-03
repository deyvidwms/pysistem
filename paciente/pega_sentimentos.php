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
             * Pega o total de sentimentos do paciente
             * 
             */
            $pegaTotal = "SELECT count(*) as Total FROM sentimentos WHERE id_usuario = :usuario AND apagado = 0";
            $pegaTotal = $pdo->prepare($pegaTotal);
            $pegaTotal->bindValue(":usuario", $usuario);
            if ( $pegaTotal->execute() != false ) { 
                                
                if ( $pegaTotal->rowCount() > 0 ) {
                                    
                    $total = $pegaTotal->fetch();

                }

            } else if ( $pegaTotal == false ) {
                
                $dados = array(
                    "error" => "Erro ao tentar fazer busca por sentimentos." 
                );
                
                echo json_encode($dados);
                
                exit;

            }

            /**
             * 
             * Pega os sentimentos do paciente
             * 
             */
            $pegaSentimentos = "SELECT * FROM sentimentos WHERE id_usuario = :usuario AND apagado = 0 ORDER BY criado DESC LIMIT :pagina_atual, 10";
            $pegaSentimentos = $pdo->prepare($pegaSentimentos);
            $pegaSentimentos->bindValue(":usuario", $usuario);
            $pegaSentimentos->bindValue(":pagina_atual", (int) trim($pagina_atual), PDO::PARAM_INT);
            $sucesso = $pegaSentimentos->execute();
    
            /**
             * 
             * Se a requisicao for bem sucedida
             * vai adicionar os sentimentos a um array
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
                if($pegaSentimentos->rowCount() > 0){
    
                    $consulta = $pegaSentimentos->fetchAll();
    
                    foreach( $consulta as $sentimento ):
    
                        array_push($dados, [
                            "id" => $sentimento["id_sentimento"],
                            "categoria" => $sentimento["id_categoria_dos_sentimentos"],
                            "nota" => $sentimento["nota_sentimento"],
                            "descricao" => !empty( $sentimento["descricao"] ) ? utf8_encode( $sentimento["descricao"] ) : "Nenhuma descrição.",
                            "data" => date("d/m/Y", strtotime( $sentimento["criado"] ) ),
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
                    "error" => "Erro ao tentar fazer busca por sentimentos.",
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