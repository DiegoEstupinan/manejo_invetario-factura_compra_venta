<!-- Main Sidebar Container -->

<aside class="main-sidebar <?= $_SESSION['UserInSession']['rol'] ?> sidebar-dark-primary <?= $_SESSION['UserInSession']['rol'] ?> elevation-4 ">
    <!-- Brand Logo -->
    <a  class="brand-link">
        <img src="<?= $baseURL ?>/views/public/img/LOGOFERROGAMEZA.jpg"
             alt="AdminLTE Logo"
             class="brand-image img-circle elevation-3"
             style="opacity: .8">
        <span class="brand-text font-weight-light"><?= $_ENV['ALIASE_SITE'] ?> </span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar ">
        <!-- Sidebar user (optional) -->
        <div class="user-panel mt-3 d-flex">
            <div class="image align-middle">
                <img src="<?= $baseURL ?>/views/public/img/worker.png" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="d-flex flex-column">
                <div class="<?= $_SESSION['UserInSession']['rol'] ?> info">
                    <a href="<?= "$baseURL/views/modules/personas/show.php?id=" .($_SESSION['UserInSession']['id'] ?? '')?>" class="d-block">
                        <?= $_SESSION['UserInSession']['nombre'] ?? '' ?>
                    </a>
                </div>
                <div class="info">
                    <a href="#" class="d-block">


                    </a>
                </div>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                     with font-awesome or any other icon font library -->
                <li class="nav-item">
                    <a href="<?= $baseURL; ?>/views/index.php" class="nav-link">
                        <i class="fas fa-home"></i>
                        <p>
                            Inicio
                        </p>
                    </a>
                </li>
                <li class="nav-header">Menu</li>

                <li class="nav-item has-treeview <?= strpos($_SERVER['REQUEST_URI'],'personas') ? 'menu-open' : '' ?>">
                    <a href="#" class="nav-link <?= strpos($_SERVER['REQUEST_URI'],'personas') ? 'active' : '' ?>">
                        <i class="fas fa-user"></i>
                        <p>
                          Personas
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= $baseURL ?>/views/modules/personas/index.php" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Ver</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= $baseURL ?>/views/modules/personas/create.php" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Crear</p>
                            </a>
                        </li>
                    </ul>

                </li>



                <li class="nav-item has-treeview <?= strpos($_SERVER['REQUEST_URI'],'clasificaciones') ? 'menu-open' : '' ?>">
                    <a href="#" class="nav-link <?= strpos($_SERVER['REQUEST_URI'],'clasificaciones') ? 'active' : '' ?>">
                        <i class="fas fa-box-open"></i>
                        <p>
                            Clasificaciones
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= $baseURL ?>/views/modules/clasificaciones/index.php" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Ver</p>
                            </a>
                        </li>
                        <?php if($_SESSION['UserInSession']['rol'] == 'administrador') {?>
                        <li class="nav-item">
                            <a href="<?= $baseURL ?>/views/modules/clasificaciones/create.php" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Registrar</p>
                            </a>
                        </li>
                        <?php } ?>
                    </ul>
                </li>
                <?php if($_SESSION['UserInSession']['rol'] == 'administrador') {?>
                <li class="nav-item has-treeview <?= strpos($_SERVER['REQUEST_URI'],'materiales') ? 'menu-open' : '' ?>">
                    <a href="#" class="nav-link <?= strpos($_SERVER['REQUEST_URI'],'materiales') ? 'active' : '' ?>">
                        <i class="fas fa-layer-group"></i>
                        <p>
                            Material
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= $baseURL ?>/views/modules/materiales/index.php" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Ver</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= $baseURL ?>/views/modules/materiales/create.php" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Registrar</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <?php } ?>
                <?php if($_SESSION['UserInSession']['rol'] == 'administrador') {?>
                <li class="nav-item has-treeview <?= strpos($_SERVER['REQUEST_URI'],'marca') ? 'menu-open' : '' ?>">
                    <a href="#" class="nav-link <?= strpos($_SERVER['REQUEST_URI'],'marca') ? 'active' : '' ?>">
                        <i class="nav-icon fa fa-tags"></i>
                        <p>
                            Marca
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= $baseURL ?>/views/modules/marcas/index.php" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Ver</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= $baseURL ?>/views/modules/marcas/create.php" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Registrar</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <?php } ?>
                <?php if($_SESSION['UserInSession']['rol'] == 'administrador') {?>
                <li class="nav-item has-treeview <?= strpos($_SERVER['REQUEST_URI'],'medida') ? 'menu-open' : '' ?>">
                    <a href="#" class="nav-link <?= strpos($_SERVER['REQUEST_URI'],'medida') ? 'active' : '' ?>">
                        <i class="nav-icon fa fa-ruler"></i>
                        <p>
                            Medida
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= $baseURL ?>/views/modules/medidas/index.php" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Ver</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= $baseURL ?>/views/modules/medidas/create.php" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Registrar</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <?php } ?>
                <li class="nav-item has-treeview <?= strpos($_SERVER['REQUEST_URI'],'productos') ? 'menu-open' : '' ?>">
                    <a href="#" class="nav-link <?= strpos($_SERVER['REQUEST_URI'],'productos') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-dolly"></i>
                        <p>
                            Productos
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= $baseURL ?>/views/modules/productos/index.php" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Ver</p>
                            </a>
                        </li>
                        <?php if($_SESSION['UserInSession']['rol'] == 'administrador') {?>
                            <li class="nav-item">
                                <a href="<?= $baseURL ?>/views/modules/productos/create.php" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Registrar</p>
                                </a>
                            </li>
                        <?php } ?>
                    </ul>
                </li>

                <li class="nav-item has-treeview <?= strpos($_SERVER['REQUEST_URI'],'ventas') ? 'menu-open' : '' ?>">
                    <a href="#" class="nav-link <?= strpos($_SERVER['REQUEST_URI'],'ventas') ? 'active' : '' ?>">
                        <i class="nav-icon fas fa-store"></i>
                        <p>
                            Ventas
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="<?= $baseURL ?>/views/modules/facturaVentas/index.php" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Ver</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= $baseURL ?>/views/modules/facturaVentas/create.php" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Registrar</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <?php if($_SESSION['UserInSession']['rol'] == 'administrador') {?>
                    <li class="nav-item has-treeview <?= strpos($_SERVER['REQUEST_URI'],'compras') ? 'menu-open' : '' ?>">
                        <a href="#" class="nav-link <?= strpos($_SERVER['REQUEST_URI'],'compras') ? 'active' : '' ?>">
                            <i class="nav-icon fas fa-shopping-basket"></i>
                            <p>
                                Compras
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="<?= $baseURL ?>/views/modules/facturaCompras/index.php" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Ver</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= $baseURL ?>/views/modules/facturaCompras/create.php" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Registrar</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php } ?>
                <?php if($_SESSION['UserInSession']['rol'] == 'administrador') {?>
                    <li class="nav-item has-treeview <?= strpos($_SERVER['REQUEST_URI'],'compras') ? 'menu-open' : '' ?>">
                        <a href="#" class="nav-link <?= strpos($_SERVER['REQUEST_URI'],'compras') ? 'active' : '' ?>">
                            <i class=" fas fa-address-book"></i>
                            <p>
                                 Manuales
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="<?= $baseURL ?>/database/MANUAL_USUARIO_FERRETERIA.pdf " class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Usuario</p>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="<?= $baseURL ?>/database/MANUAL_PROGRAMADOR_FERRETERIA%20.pdf" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Programador</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php } ?>



            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>