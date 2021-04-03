<?php

    /**
	 * 
	 * Importacao do arquivo de conexao
	 * com o banco de dados
	 * 
	 */
    include "../config/config.php";

    /**
     * 
     * Tipo 1 - Cadastro de usuario
     * Tipo 2 - Edicao de usuario
     * 
     */
    if ( isset( $_GET["tipo"] ) && !empty( $_GET["tipo"] ) && $_GET["tipo"] == 1 ) {

        /**
         * 
         * Verifica se enviou o cpf do usuario
         * 
         */
        if ( isset( $_POST["cpf"] ) && !empty( $_POST["cpf"] ) ) {

            $usuarioMedico = $_POST["idUsuario"];

            $nome = utf8_decode( $_POST["nome"] );        
            $usuario = $_POST["usuario"];        
            $email = $_POST["email"];        
            $cpf = $_POST["cpf"];
            $telefone = $_POST["telefone"];
            $senha = md5( $_POST["senha"] );
    
            /**
             * 
             * Verifica se existe algum paciente com o
             * mesmo nome de usuario, email ou cpf
             * 
             */
            $selectUser = "SELECT * FROM usuarios WHERE ( usuario = :usuario OR email = :email OR cpf = :cpf ) AND apagado = 0";
            $selectUser = $pdo->prepare($selectUser);
            $selectUser->bindValue(":usuario", $usuario);
            $selectUser->bindValue(":email", $email);
            $selectUser->bindValue(":cpf", $cpf);
            if ( $selectUser->execute() == false ) {
                
                echo json_encode(
                    [
                        "error" => "Falha ao buscar usuário."
                    ]
                );
    
                exit;
    
            } else if ( $selectUser->rowCount() > 0 ) {
    
                echo json_encode(
                    [
                        "error" => "Já existe um usuário com esse nome de Usuário, Email e ou CPF."
                    ]
                );
    
                exit;
    
            } else if ( $selectUser->rowCount() == 0 ) {
    
                /**
                 * 
                 * Insere o novo usuario no 
                 * banco de dados
                 * 
                 */
                $insertUser = "INSERT INTO usuarios ( nome, usuario, email, cpf, telefone, senha, tipo ) 
                                VALUES ( :nome, :usuario, :email, :cpf, :telefone, :senha, 0 ) ";
                $insertUser = $pdo->prepare($insertUser);
                $insertUser->bindValue(":nome", $nome);
                $insertUser->bindValue(":usuario", $usuario);
                $insertUser->bindValue(":email", $email);
                $insertUser->bindValue(":cpf", $cpf);
                $insertUser->bindValue(":telefone", $telefone);
                $insertUser->bindValue(":senha", $senha);
                if ( $insertUser->execute() == false ) {
                    
                    echo json_encode(
                        [
                            "error" => "Falha ao tentar cadastrar usuário."
                        ]
                    );
    
                    exit;
    
                } else {

                    $usuarioAdicionado = $pdo->lastInsertId();

                    /**
                     * 
                     * Insere a ligação do usuario medico ao usuario paciente
                     * 
                     */
                    $insertPacienteMedico = "INSERT INTO pacientes_dos_medicos ( id_usuario_medico, id_usuario_paciente ) VALUES ( :medico, :paciente )";
                    $insertPacienteMedico = $pdo->prepare($insertPacienteMedico);
                    $insertPacienteMedico->bindValue(":medico", $usuarioMedico);
                    $insertPacienteMedico->bindValue(":paciente", $usuarioAdicionado);
                    if ( $insertPacienteMedico->execute() == false ) {
    
                        $deleteUser = "UPDATE usuarios SET apagado = 1 WHERE id_usuario = :usuario";
                        $deleteUser = $pdo->prepare($deleteUser);
                        $deleteUser->bindValue(":usuario", $usuarioAdicionado);
                        $deleteUser->execute();
    
                        echo json_encode(
                            [
                                "error" => "Falha ao ligar paciente ao médico."
                            ]
                        );
    
                        exit;
    
                    } else {
    
                        /**
                         * 
                         * Retorna mensagem de sucesso
                         * 
                         */
                        echo json_encode(
                            [
                                "success" => "Usuário cadastrado com sucesso."
                            ]
                        );
            
                        exit;

                    }
        
                }

            }
    
        /**
         * 
         * Retorna uma mensagem de erro
         * 
         */
        } else {
         
            echo json_encode(
                [
                    "error" => "Falha ao tentar cadastrar paciente."
                ]
            );
    
            exit;
    
        }

    } else if ( isset( $_GET["tipo"] ) && !empty( $_GET["tipo"] ) && $_GET["tipo"] == 2 ) {

        /**
         * 
         * Verifica se o id do usuario foi enviado
         * 
         */
        if ( isset( $_POST["idUsuario"] ) && !empty( $_POST["idUsuario"] ) ) {

            $usuario = $_POST["idUsuario"];

            $nome = $_POST["nome"];

            $email = $_POST["email"];
            
            $cpf = $_POST["cpf"];
            
            $telefone = $_POST["telefone"];

            /**
             * 
             * Verifica se foi enviada alguma senha tambem
             * e faz a atualizacao das informacoes
             * 
             */
            if ( isset( $_POST["senha"] ) && !empty( $_POST["senha"] ) ) :
                
                $senha = md5($_POST["senha"]);

                $updateUser = "UPDATE usuarios SET nome = :nome, email = :email, cpf = :cpf, telefone = :telefone, senha = :senha WHERE id_usuario = :usuario";
                
            else :
                    
                $updateUser = "UPDATE usuarios SET nome = :nome, email = :email, cpf = :cpf, telefone = :telefone WHERE id_usuario = :usuario";

            endif;

            $updateUser = $pdo->prepare($updateUser);
            $updateUser->bindValue(":nome", $nome);
            $updateUser->bindValue(":email", $email);
            $updateUser->bindValue(":cpf", $cpf);
            $updateUser->bindValue(":telefone", $telefone);

            if ( isset( $_POST["senha"] ) && !empty( $_POST["senha"] ) )
                $updateUser->bindValue(":senha", $senha);

            $updateUser->bindValue(":usuario", $usuario);
            if ( $updateUser->execute() == false ) {

                echo json_encode(
                    [
                        "error" => "Falha ao atualizar dados do paciente."
                    ]
                );

                exit;

            } 

            /**
             * 
             * retorna mensagem de sucesso
             * 
             */
            echo json_encode(
                [
                    "success" => "Paciente atualizado com sucesso."
                ]
            );

            exit;

        /**
         * 
         * retorna mensagem de erro
         * 
         */
        } else {

            echo json_encode(
                [
                    "error" => "Falha ao tentar editar paciente."
                ]
            );
    
            exit;

        }

    /**
     * 
     * Caso nao haja um tipo o
     * usuario eh redirecionado para a pagina
     * de login
     * 
     *  */
    } else {

        header("Location: index.php");

    }