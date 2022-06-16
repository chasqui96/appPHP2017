<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Menú Principal</title>
		<link rel="shortcut icon" href="/favicon2.ico">
        <link rel="stylesheet" type="text/css" href="./css/reset.css">
        <link rel="stylesheet" type="text/css" href="./css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="./css/menuresponsive.css">
        <style>
            body {
                background: #2aabd2; /* fallback for old browsers */
                background: -webkit-linear-gradient(right, #204d74, #2aabd2);
                background: -moz-linear-gradient(right, #204d74, #2aabd2);
                background: -o-linear-gradient(right, #204d74, #2aabd2);
                background: linear-gradient(to left, #204d74, #2aabd2);
                font-family: "Roboto", sans-serif;
                -webkit-font-smoothing: antialiased;
                -moz-osx-font-smoothing: grayscale;      
            }
        </style>
    </head>
    <body>
        <header class="container">		
            <?php
            require_once './clases/conexion.php';
            require_once './cabecera.php';
            ?>		
        </header>
        <section class="container">
            
        </section>

        <script type="text/javascript" src="./js/jquery-1.12.2.min.js"></script>
        <script type="text/javascript" src="./js/bootstrap.min.js"></script>
        <script type="text/javascript" src="./js/menuresponsive.js"></script>

    </body>
</html>