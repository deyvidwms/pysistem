<?php

    /**
     * 
     * Importa o arquivo de
     * conexao com o banco de dados
     * 
     */
    include "../config/config.php";


    /**
     * 
     * Pega os sentimentos cadastrados pelo paciente
     * 1 - sentimentos da semana atual
     * 2 - sentimentos do mes atual
     * 3 - dados do sentimento especifico 
     * 
     */
    if ( isset($_GET["tipo"]) && !empty($_GET["tipo"]) && $_GET["tipo"] == 1 ) {
        
        /**
         * 
         * Verifica se foi enviado algum valor
         * chamado usuario contendo o id do usuario
         * 
         */
        if ( isset( $_POST["usuario"] ) && !empty( $_POST["usuario"] ) ) {

            $pagina_atual = ( isset($_GET["pagina"]) && !empty($_GET["pagina"]) ? $_GET["pagina"] : 0 ) * 10;

            $usuario = $_POST["usuario"];

            /**
             * 
             * Pega o total de sentimentos do usuario da semana atual 
             * 
             */
            $pegaTotal = "SELECT count(*) as Total FROM sentimentos WHERE id_usuario = :usuario AND YEARWEEK(criado, 0) = YEARWEEK(CURDATE(), 0) AND apagado = 0";
            $pegaTotal = $pdo->prepare($pegaTotal);
            $pegaTotal->bindValue(":usuario", $usuario);
            if ( $pegaTotal->execute() != false ) { 
                
                if ( $pegaTotal->rowCount() > 0 ) {
                                    
                    $total = $pegaTotal->fetch();

                }

            } else {
                
                $dados = array(
                    "error" => "Erro ao tentar fazer busca por sentimentos." 
                );
                
                echo json_encode($dados);
                
                exit;

            }
            
            /**
             * 
             * Pega os sentimentos do usuario da semana atual
             * 
             */
            $pegaSentimentos = "SELECT * FROM sentimentos WHERE id_usuario = :usuario AND YEARWEEK(criado, 0) = YEARWEEK(CURDATE(), 0) AND apagado = 0 ORDER BY criado DESC LIMIT :pagina_atual, 10";
            $pegaSentimentos = $pdo->prepare($pegaSentimentos);
            $pegaSentimentos->bindValue(":usuario", $usuario);
            $pegaSentimentos->bindValue(":pagina_atual", (int) trim($pagina_atual), PDO::PARAM_INT);
            $sucesso = $pegaSentimentos->execute();
    

            /**
             * 
             * Caso a requisicao tenha sido
             * bem sucedida
             * 
             */
            if( $sucesso == true){

                $dados = array();
    
                /**
                 * 
                 * Caso tenha algum resultado
                 * vai adicionar ao array e retornar
                 * 
                 */
                if($pegaSentimentos->rowCount() > 0){
    
                    $consulta = $pegaSentimentos->fetchAll();
    
                    foreach( $consulta as $sentimento ):
    
                        array_push($dados, [
                            "id" => $sentimento["id_sentimento"],
                            "categoria" => $sentimento["id_categoria_dos_sentimentos"],
                            "nota" => $sentimento["nota_sentimento"],
                            "descricao" => utf8_encode( $sentimento["descricao"] ),
                            "data" => date( "d/m/Y", strtotime( $sentimento["criado"] ) ),
                        ]);
        
                    endforeach;
        
                    /**
                     * 
                     * Diz se chegou ao limite
                     * de itens
                     * 
                     */
                    if( $total["Total"] - ( ( $_GET["pagina"] + 1 ) * 10 ) > 0 ){
                        array_push( $dados, [ "limite" => false ] );
                    }else{
                        array_push( $dados, [ "limite" => true ] );
                    }
    
                    /**
                     * 
                     * retorna o json contendo 
                     * as informacoes solicitadas
                     * 
                     */
                    echo json_encode($dados);
                    exit;

                /**
                 * 
                 * Retorna uma mensagem dizendo 
                 * que nao encontrou nenhum resultado
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
             * Caso tenha ocorrido algum erro 
             * ao tentar fazer a requisicao
             * 
             */
            } else { 
                
                $dados = array(
                    "error" => "Erro ao tentar fazer busca por sentimentos do paciente.",
                );
                
                echo json_encode($dados);
                
                exit;
                
            }
    

        /**
         * 
         * Caso tenha ocorrido algum erro 
         * durante o processo de pegar o id
         * do usuario retorna uma mensagem
         * de erro
         * 
         */
        } else {

            echo json_encode(
                [
                    "error" => "Falha ao tentar buscar informações."
                ]
            );

            exit;

        }

    } else if ( isset($_GET["tipo"]) && !empty($_GET["tipo"]) && $_GET["tipo"] == 2 ) {

        if ( isset( $_POST["idUsuario"] ) && !empty( $_POST["idUsuario"] ) ) {

            $pagina_atual = ( isset($_GET["pagina"]) && !empty($_GET["pagina"]) ? $_GET["pagina"] : 0 ) * 10;

            $usuario = $_POST["idUsuario"];

            $pegaTotal = "SELECT count(*) as Total FROM sentimentos WHERE id_usuario = :usuario AND YEAR(criado) = YEAR(CURRENT_TIMESTAMP) AND MONTH(criado) = MONTH(CURRENT_TIMESTAMP) AND apagado = 0";
            $pegaTotal = $pdo->prepare($pegaTotal);
            $pegaTotal->bindValue(":usuario", $usuario);
            if ( $pegaTotal->execute() != false ) { 
                
                if ( $pegaTotal->rowCount() > 0 ) {
                                    
                    $total = $pegaTotal->fetch();

                }

            } else {
                
                $dados = array(
                    "error" => "Erro ao tentar fazer busca por sentimentos." 
                );
                
                echo json_encode($dados);
                
                exit;

            }
            
            $pegaSentimentos = "SELECT * FROM sentimentos 
                                WHERE id_usuario = :usuario AND YEAR(criado) = YEAR(CURRENT_TIMESTAMP) AND MONTH(criado) = MONTH(CURRENT_TIMESTAMP) AND apagado = 0 
                                ORDER BY criado DESC LIMIT :pagina_atual, 10";
            $pegaSentimentos = $pdo->prepare($pegaSentimentos);
            $pegaSentimentos->bindValue(":usuario", $usuario);
            $pegaSentimentos->bindValue(":pagina_atual", (int) trim($pagina_atual), PDO::PARAM_INT);
            $sucesso = $pegaSentimentos->execute();
    
            if( $sucesso == true){

                $dados = array();
    
                if( $pegaSentimentos->rowCount() > 0){
    
                    $consulta = $pegaSentimentos->fetchAll();
    
                    foreach( $consulta as $sentimento ):
    
                        array_push($dados, [
                            "id" => $sentimento["id_sentimento"],
                            "categoria" => $sentimento["id_categoria_dos_sentimentos"],
                            "nota" => $sentimento["nota_sentimento"],
                            "descricao" => utf8_encode( $sentimento["descricao"] ),
                            "data" => date( "d/m/Y", strtotime( $sentimento["criado"] ) ),
                        ]);
        
                    endforeach;
        
                    if( $total["Total"] - ( ( $_GET["pagina"] + 1 ) * 10 ) > 0 ){
                        array_push( $dados, [ "limite" => false ] );
                    }else{
                        array_push( $dados, [ "limite" => true ] );
                    }
    
                    echo json_encode($dados);
                    exit;
    
                } else {

                    $dados = array(
                        "error" => "Nenhum resultado encontrado.",
                    );
                    
                    echo json_encode($dados);
                    
                    exit;
                }
                
            } else { 
                
                $dados = array(
                    "error" => "Erro ao tentar fazer busca por sentimentos do paciente.",
                );
                
                echo json_encode($dados);
                
                exit;
                
            }

        } else {

            echo json_encode(
                [
                    "error" => "Falha ao tentar buscar informações."
                ]
            );

            exit;

        }
        
    } else {
        header("Location: index.php");
    }