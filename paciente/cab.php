<?php

    /**
     * 
     * Inicia a sessao
     * 
     */
    session_start();

    /**
     * 
     * Verifica se o usuario ta logado
     * e se nao estiver redireciona para 
     * a pagina de login
     * 
     */
    if ( !isset( $_SESSION["id"] ) || empty( $_SESSION["id"] ) ) {
        header("Location: login.php");
    }

    /**
     * 
     * Pega os dados do usuario logado
     * 
     */
    $selectUserInfo = "SELECT * FROM usuarios WHERE id_usuario = :usuario AND tipo = 0";
    $selectUserInfo = $pdo->prepare($selectUserInfo);
    $selectUserInfo->bindValue(":usuario", $_SESSION["id"]);
    if ( $selectUserInfo->execute() == false || $selectUserInfo->rowCount() == 0 ) {

        header("Location: login.php");

    } else if ( $selectUserInfo->rowCount() > 0 ) {

        $dadosUsuario = $selectUserInfo->fetch();

    }

?>
<!DOCTYPE html>
<html dir="ltr" lang="pt-br">
    <head>
        <meta charset="utf-8">
        <title> PSYSTEM </title>
        <!-- Favicon icon -->
        <link rel="icon" type="image/png" sizes="16x16" href="../../assets/images/MiniLogo.png">
        <link href="../../dist/css/style.min.css" rel="stylesheet">
        <link href="../../dist/css/styleindex.css" rel="stylesheet">
        <link href="../../dist/css/style.css" rel="stylesheet">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <script src="../../assets/libs/jquery/dist/jquery.min.js"></script>
    </head>

<body>

    <div class="preloader">

        <div class="lds-ripple">
    
            <div class="lds-pos"></div>

            <div class="lds-pos"></div>

        </div>

    </div>

    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div id="main-wrapper" data-navbarbg="skin6" data-theme="light" data-layout="vertical" data-sidebartype="full"
        data-boxed-layout="full">
        <header class="topbar" data-navbarbg="skin6">
            
            <nav class="navbar top-navbar navbar-expand-md navbar-light">
                <div class="navbar-header" data-logobg="skin5">
                
                    <a class="nav-toggler waves-effect waves-light d-block d-md-none" href="javascript:void(0)">
                        <i class="ti-menu ti-close"></i>
                    </a>  
                <div class="hide-menu" style="background-color: #0A915E;width: 25px;height: 64px;">   
                </div>           
                </div>
            </nav>
        </header>

        <!-- ============================================================== -->
        <!-- Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <aside class="left-sidebar" data-sidebarbg="skin5">
            <!-- Sidebar scroll-->
            <div class="scroll-sidebar">
                <!-- Sidebar navigation-->
                <nav class="sidebar-nav" id="sbn">
                   
                    <ul id="sidebarnav">
                        <li class="sidebar-item">
                            <center> <a href="index.php" class="hide-menu">
                                <img src="../../assets/images/Logo.png" id="logoPSystem">
                                </a>
                            </center>
                        </li>
                        <li class="sidebar-item">
                            <div class="sidebar-link "> 
                                <i class="mdi mdi-account"></i>
                                <span class="hide-menu" id="nomeUser"><?php echo ucwords( strtolower( utf8_encode( $_SESSION["usuario"] ) ) ); ?></span>
                            </div>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link waves-effect waves-dark sidebar-link" href="index.php"
                                aria-expanded="false">
                                <i class="mdi mdi-account-multiple"></i>
                                <span class="hide-menu">Meus sentimentos</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link waves-effect waves-dark sidebar-link" href="cadastro_sentimento.php"
                                aria-expanded="false">
                                <i class="mdi mdi-account-plus"></i>
                                <span class="hide-menu">Cadastrar sentimento</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link waves-effect waves-dark sidebar-link" href="meus_dados.php"
                                aria-expanded="false">
                                <i class="mdi mdi-message-text"></i>
                                <span class="hide-menu">Meus Dados</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link waves-effect waves-dark sidebar-link" href="sair.php"
                                aria-expanded="false">
                                <i class="mdi mdi-logout"></i>
                                <span class="hide-menu">Sair</span>
                            </a>
                        </li>
                    </ul>
                </nav>
                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
        </aside>
        <!-- ============================================================== -->
        <!-- End Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->

        <!-- ============================================================== -->
        <!-- Page wrapper  -->
        <!-- ============================================================== -->
        <div class="page-wrapper">

            <!-- ============================================================== -->
            <!-- Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <div class="container-fluid" id="DivContainer">
                
                            