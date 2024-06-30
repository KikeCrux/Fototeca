<!DOCTYPE html>
<html>

<head>
    <title>Dashboard</title>
    <!-- Enlaza la hoja de estilos específica para los estilos del menú -->
    <link rel="stylesheet" href="../css/menu.css">
</head>

<body>
    <div class="container">
        <!-- Título centralizado para la sección de inicio de sesión -->
        <h2 class="text-center">Inicios de sesión</h2>
        <br>
        <!-- Barra de navegación para sección de consultas relacionadas con el inicio de sesión -->
        <nav class="menu navbar navbar-expand-lg">
            <div class="collapse navbar-collapse justify-content-center" id="navbarSupportedContent">
                <ul class="navbar-nav text-center">
                    <li class="nav-item">
                        <a class="nav-link" href="consultaIniciosSes.php">Consultas</a>
                    </li>
                </ul>
            </div>
        </nav>

        <br>
        <!-- Título para la sección de usuarios -->
        <h2 class="text-center">Usuarios</h2>
        <br>
        <!-- Menú para las operaciones de alta, baja y consulta de usuarios -->
        <nav class="menu2 navbar navbar-expand-lg">
            <div class="collapse navbar-collapse justify-content-center" id="navbarSupportedContent">
                <ul class="navbar-nav text-center">
                    <li class="nav-item">
                        <a class="nav-link" href="altaUsuarios.php">Altas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="bajaUsuarios.php">Bajas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="consultaUsuarios.php">Consultas</a>
                    </li>
                </ul>
            </div>
        </nav>

        <br>
        <!-- Título para la sección de personal -->
        <h2 class="text-center">Personal</h2>
        <br>
        <!-- Menú para las operaciones de alta, baja, cambio y consulta de personal -->
        <nav class="menu navbar navbar-expand-lg">
            <div class="collapse navbar-collapse justify-content-center" id="navbarSupportedContent">
                <ul class="navbar-nav text-center">
                    <li class="nav-item">
                        <a class="nav-link" href="altaPersonal.php">Altas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="bajaPersonal.php">Bajas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="cambioPersonal.php">Cambios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="consultaPersonal.php">Consultas</a>
                    </li>
                </ul>
            </div>
        </nav>

        <br>
        <!-- Título para la sección de datos generales -->
        <h2 class="text-center">Datos Generales</h2>
        <br>
        <!-- Menú para las operaciones de alta, baja, cambio y consulta de datos generales -->
        <nav class="menu2 navbar navbar-expand-lg">
            <div class="collapse navbar-collapse justify-content-center" id="navbarSupportedContent">
                <ul class="navbar-nav text-center">
                    <li class="nav-item">
                        <a class="nav-link" href="altaDatosGen.php">Altas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="bajaDatosGen.php">Bajas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="cambioDatosGen.php">Cambios</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="consultaDatosGen.php">Consultas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="consultaHistorialDatosGen.php">Historial</a>
                    </li>
                </ul>
            </div>
        </nav>

        <br>
        <!-- Título para la sección de personal -->
        <h2 class="text-center">Estadisticas</h2>
        <br>
        <!-- Menú para las operaciones de alta, baja, cambio y consulta de personal -->
        <nav class="menu navbar navbar-expand-lg">
            <div class="collapse navbar-collapse justify-content-center" id="navbarSupportedContent">
                <ul class="navbar-nav text-center">
                    <li class="nav-item">
                        <a class="nav-link" href="estadisticas.php">Consultas</a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
    <br>
</body>

</html>