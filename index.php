<?php
	session_start();
	if($_SESSION){
            session_destroy();
	}
?>
<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>ACCESO</title>
        <link href="./src/bootstrap-5.3.2/css/bootstrap.min.css" rel="stylesheet"/>
		<link rel="shortcut icon" href="/favicon2.ico">
        <style>

            .login-page {
                width: 360px;
                padding: 8% 0 0;
                margin: auto;
            }
            .form {
                position: relative;
                z-index: 1;
                background: #fcf8e3;
                border-radius: 15px;
                max-width: 360px;
                margin: 0 auto 100px;
                padding: 45px;
                text-align: center;
                box-shadow: 0 0 20px 0 rgba(0, 0, 0, 0.2), 0 5px 5px 0 rgba(0, 0, 0, 0.24);
            }
            .form input {
                font-family: "Roboto", sans-serif;
                outline: 0;
                background: #f2f2f2;
                width: 100%;
                border: 0;
                margin: 0 0 15px;
                padding: 15px;
                box-sizing: border-box;
                font-size: 14px;
            }
            .form button {
                font-family: "Roboto", sans-serif;
                text-transform: uppercase;
                outline: 0;
                background: #2aabd2;
                width: 100%;
                border: 0;
                padding: 15px;
                color: #FFFFFF;
                font-size: 14px;
                -webkit-transition: all 0.3 ease;
                transition: all 0.3 ease;
                cursor: pointer;
            }
            .form button:hover,.form button:active,.form button:focus {
                background: #2aabd2;
            }
            .form .message {
                margin: 15px 0 0;
                color: #b3b3b3;
                font-size: 12px;
            }
            .form .message a {
                color: #2aabd2;
                text-decoration: none;
            }
            .form .register-form {
                display: none;
            }
            .container {
                position: relative;
                z-index: 1;
                max-width: 300px;
                margin: 0 auto;
            }
            .container:before, .container:after {
                content: "";
                display: block;
                clear: both;
            }
            .container .info {
                margin: 50px auto;
                text-align: center;
            }
            .container .info h1 {
                margin: 0 0 15px;
                padding: 0;
                font-size: 36px;
                font-weight: 300;
                color: #1a1a1a;
            }
            .container .info span {
                color: #4d4d4d;
                font-size: 12px;
            }
            .container .info span a {
                color: #000000;
                text-decoration: none;
            }
            .container .info span .fa {
                color: #EF3B3A;
            }
            body {
                background: #2aabd2; /* fallback for old browsers */
                background: -webkit-linear-gradient(right, #2aabd2, #204d74);
                background: -moz-linear-gradient(right, #2aabd2, #204d74);
                background: -o-linear-gradient(right, #2aabd2, #204d74);
                background: linear-gradient(to left, #2aabd2, #204d74);
                font-family: "Roboto", sans-serif;
                -webkit-font-smoothing: antialiased;
                -moz-osx-font-smoothing: grayscale;      
            }
        </style>
    </head>
    <body>
        <div class="login-page">
            <div class="form">
                <form class="login-form">

                    <input type="text" id="usuario" placeholder="Usuario" required=""/>
                    <input type="password" id="clave" placeholder="Contraseña" required=""/>

                </form>
                <button onclick="consultar();">Ingresar</button>
            </div>
        </div>
        <script src="./src/js/jquery-3.7.1.min.js"></script>
        <script src="./src/bootstrap-5.3.2/js/bootstrap.min.js"></script>
        <script src="./src/bootbox/bootbox.all.min.js"></script>
        <script src="./src/bootbox/bootbox.locales.min.js"></script>
        <script>

                    $('#clave').keypress(function (evt) {
                        if (evt.which === 13) {
                            consultar();
                        }
                    });


                    function consultar() {
                        $.post("login.php", {usuario: $("#usuario").val(), clave: $("#clave").val()})
                                .done(function (data) {
                                    if (data === "OK") {
                                       // document.location.href = "menu.php";
                                        bootbox.alert({
                                            message: "BIENVENIDO AL SISTEMA",
                                            callback: function () {
                                                console.log('INGRESAMOS AL SISTEMA');
                                                document.location.href = "menu.php";
                                            }
                                        });
                                    } else {
                                        bootbox.alert({
                                            message: "ERROR: " + data,
                                            callback: function () {
                                                console.log('NO SE PUDO INGRESAR');
                                                console.log(data);
                                            }
                                        });
                                    }
                                });
                    }
        </script>
    </body>
</html>
