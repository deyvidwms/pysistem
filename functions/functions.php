<?php

    /*
     * 
     * Funcao para gerar uma token aleatorio 
     * Tem de passar o tamannho da token, e qual se ela vai conter
     * letras maiusculas, minusculas, numeros e ou simbolos.
     * Tamanho, valor inteiro representando o tamanho da token
     * Maiusculas, valor booleano, informa se havera caracteres maiusculos
     * Minusculas, valor booleano, informa se havera caracteres minusculos
     * Numeros, valor booleano, informa se havera caracteres nummericos
     * Simbolos, valor booleano, informa se havera simbolos
     * 
     */
    function gerar_token($tamanho, $maiusculas, $minusculas, $numeros, $simbolos){
        $ma = "ABCDEFGHIJKLMNOPQRSTUVYXWZ"; // $ma contem as letras maiúsculas
        $mi = "abcdefghijklmnopqrstuvyxwz"; // $mi contem as letras minusculas
        $nu = "0123456789"; // $nu contem os números
        $si = "!@#$%¨&*()_+="; // $si contem os símbolos
       
        $token = "";

        if ($maiusculas){
            // se $maiusculas for "true", a variável $ma é embaralhada e adicionada para a variável $token
            $token .= str_shuffle($ma);
        }
       
        if ($minusculas){
            // se $minusculas for "true", a variável $mi é embaralhada e adicionada para a variável $token
            $token .= str_shuffle($mi);
        }
       
        if ($numeros){
            // se $numeros for "true", a variável $nu é embaralhada e adicionada para a variável $token
            $token .= str_shuffle($nu);
        }
       
        if ($simbolos){
            // se $simbolos for "true", a variável $si é embaralhada e adicionada para a variável $token
            $token .= str_shuffle($si);
        }
       
        // retorna a token embaralhada com "str_shuffle" com o tamanho definido pela variável $tamanho
        return substr(str_shuffle($token),0,$tamanho);
    }

    /*
     * 
     * Funcao para enviar email com token de recuperacao de senha
     * O parametro eh o email e o token e uma variavel bool -> true eh medico e false eh paciente
     * 
     */
    function sendRecoveryEmail( $email, $token, $bool ) {
     
        ini_set('display_errors', 1);

        // error_reporting(E_ALL);

        $subject = "PSyistem - Alteração de Senha";

        $message = '
            <!DOCTYPE html>
            <html lang="pt-br">
            <head>
                <meta charset="utf-8" />
            </head>
            <body>
        
                <div class="content" style="background-color: #fff; text-align: center;">
        
                    <div style="text-align: center; margin: 8% 0px 6% 0px;">
                        <img src="https://deyvid.dev.br/assets/images/Logo.png" height="100" alt="">
                    </div>
                    
                    <div class="texto" style="text-align: center; margin: 5% 10%; color: #000 !important;">
                        
                        <h2 style="font-family: Verdana, Arial, Helvetica, sans-serif; font-weight: 100; color: #000 !important; text-align: center;">
                            Olá, <span style="font-family: Verdana, Arial, Helvetica, sans-serif; font-weight: 100; color: #000 !important; text-align: center;"> segue o link para cadastrar uma nova senha.</span>
                        </h2>

                        <h2>
                            <span style="font-family: Verdana, Arial, Helvetica, sans-serif; font-weight: 100; color: #000 !important; text-align: center; text-decoration: none !important;">https://deyvid.dev.br/'.( $bool == "false" ? 'paciente' : 'medico').'/recuperar_senha.php?token='.$token.'</span>
                        </h2>
                        
                        <h2>
                            <span style="font-family: Verdana, Arial, Helvetica, sans-serif; font-weight: 100; color: #000 !important; text-align: center; text-decoration: none !important;">Caso você não tenha solicitado a mudança de senha, apenas ignore esse email.</span>
                        </h2>

                    </div>
                    
                    <footer class="footer" style="padding: 10% 5% 0% 5%;text-align: center;font-family: Verdana, Arial, Helvetica, sans-serif;font-size: 15px;color: #000 !important;">
                        <strong>PSyistem </strong>© Todos os direitos reservados. <span> | Desenvolvido por <strong><a href="https://www.deyvid.dev.br/" style="color:black;text-decoration: none !important;">escassosDev</a></strong></span>
                    </footer>
        
                </div>    
        
            </body>
            </html>
        ';

        $headers = "Content-type: text/html\r\n";

        return mail($email, $subject, $message, $headers);
        // return mail("deyvidwms@gmail.com", $subject, $message, $headers);

    }