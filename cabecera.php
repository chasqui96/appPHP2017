<!-- <nav class="navbar">
    <div class="container-fluid">			    
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#menu">			        
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="menu.php"><img src="./img/logo.png" width="100px"/></a>
        </div>
        <div class="collapse navbar-collapse" id="menu">
            <ul class="navbar-nav">

                <?php
                require_once './sesion_estado.php';
                ?>
                <?php if ($_SESSION['id_perfil'] == 1) { ?>
                    <li class="dropdown">

                        <a href="#" class="dropdown-toggle"><span class="glyphicon glyphicon-cog"></span> Referenciales<b class="caret"></b></a>
                        <ul class="dropdown-menu">

                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle">Ref. de Empresa<b class="caret right"></b></a>
                                <ul class="dropdown-menu">

                                    <li><a href="./referenciales/ref_empresa/depositos/">Depósitos</a></li>
                                    <li><a href="./referenciales/ref_empresa/sucursales/">Sucursales</a></li>
                                    <li><a href="./referenciales/ref_empresa/ciudades/">Ciudades</a></li>
                                    <li><a href="#">Funcionarios</a></li>
                                    <li><a href="#">Usuarios</a></li>
                                    <li><a href="#">Mercaderías</a></li>
                                    <li><a href="./referenciales/ref_empresa/cajas/">Cajas</a></li>
                                    <li><a href="./referenciales/ref_empresa/cargos/">Cargos</a></li>
                                    <li><a href="./referenciales/ref_empresa/perfiles/">Perfiles</a></li>

                                </ul>
                            </li>

                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle">Ref. de Compras<b class="caret right"></b></a>
                                <ul class="dropdown-menu">

                                    <li><a href="#">Proveedores</a></li>

                                </ul>
                            </li>

                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle">Ref. de Ventas<b class="caret right"></b></a>
                                <ul class="dropdown-menu">

                                    <li><a href="./referenciales/ref_ventas/clientes/">Clientes</a></li>
                                    <li><a href="#">Marcas de Tarjetas</a></li>
                                    <li><a href="#">Tarjetas</a></li>
                                    <li><a href="#">Entidades Emisoras</a></li>

                                </ul>
                            </li>

                        </ul>
                    </li>
                <?php } ?>

                <?php if ($_SESSION['id_perfil'] == 3) { ?>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle"><span class="glyphicon glyphicon-shopping-cart"></span> Compras<b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="#">Pedidos de Compras</a></li>
                            <li><a href="#">Ordenes de Compras</a></li>
                            <li><a href="#">Registros de Compras</a></li>
                        </ul>
                    </li>
                <?php } ?>
                <?php if ($_SESSION['id_perfil'] == 2) { ?>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle"><span class="glyphicon glyphicon-usd"></span> Ventas<b class="caret"></b></a>
                        <ul class="dropdown-menu">
                            <li><a href="ventas/apertura_cierres">Apertura de Caja</a></li>
                            <li><a href="./ventas/facturacion/">Facturación</a></li>
                            <li><a href="./ventas/cobros/">Cobranza</a></li>
                            <li><a href="#">Notas de Crédito</a></li>
                        </ul>
                    </li>
                <?php } ?>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle"><span class="glyphicon glyphicon-list-alt"></span> Informes<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <?php if ($_SESSION['id_perfil'] == 3) { ?>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle">Inf. de Compras<b class="caret right"></b></a>
                                <ul class="dropdown-menu">

                                    <li><a href="#">Inf. de Pedidos de Compras</a></li>
                                    <li><a href="#">Inf. de Ordenes de Compras</a></li>
                                    <li><a href="#">Inf. de Registros de Compras</a></li>

                                </ul>
                            </li>
                        <?php } ?>
                        <?php if ($_SESSION['id_perfil'] == 2) { ?>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle">Inf. de Ventas<b class="caret right"></b></a>
                                <ul class="dropdown-menu">

                                    <li><a href="#">Inf. de Arqueos de Cajas</a></li>
                                    <li><a href="#">Inf. de Facturaciones</a></li>
                                    <li><a href="#">Inf. de Cobranzas</a></li>

                                </ul>
                            </li>
                        <?php } ?>
                        <?php if ($_SESSION['id_perfil'] == 1) { ?>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle">Inf. de Referenciales<b class="caret right"></b></a>
                                <ul class="dropdown-menu">

                                    <li><a href="#">Listado de Clientes</a></li>
                                    <li><a href="#">Listado de Proveedores</a></li>
                                    <li><a href="#">Listado de Funcionarios</a></li>

                                </ul>
                            </li>
                        <?php } ?>
                    </ul>
                </li>

            </ul>			      
            <ul class="nav navbar-nav navbar-right">

                <li class="dropdown">
                    <a href="#" class="dropdown-toggle"><span class="glyphicon glyphicon-user"></span> <?php echo $_SESSION['fun_nombres'] . ' ' . $_SESSION['fun_apellidos']; ?> <b class="caret"></b></a>

                    <ul class="dropdown-menu">
                        <li><a href="#"><span class="glyphicon glyphicon-briefcase"></span> <?php echo $_SESSION['per_descripcion']; ?> </a></li>
                        <li><a href="#"><span class="glyphicon glyphicon-home"></span> <?php echo $_SESSION['suc_descripcion']; ?> </a></li>
                        <li><a href="index.php"><span class="glyphicon glyphicon-off"></span> Cerrar Sesión </a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav> -->
<nav class="navbar" style="background-color: #e3f2fd;">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <a class="navbar-brand" href="#">FACTERERE</a>
       

        



        <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasNavbarLabel">MENU</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Link</a>
                    </li>
                    <?php if ($_SESSION['id_perfil'] == 2) { ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Ventas
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item"  href="ventas/apertura_cierres">Apertura de Caja</a></li>
                            <li><a class="dropdown-item"  href="./ventas/facturacion/">Facturación</a></li>
                            <li><a class="dropdown-item"  href="./ventas/cobros/">Cobranza</a></li>
                            <li><a class="dropdown-item"  href="#">Notas de Crédito</a></li>
                        </ul>
                       
                    </li>
                 <?php } ?>
                 
                </ul>
                <form class="d-flex mt-3" role="search">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </form>
            </div>
        </div>
    </div>
</nav>