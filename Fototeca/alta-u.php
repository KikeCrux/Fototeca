<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Registro</title>
    <!-- Agregar la referencia al archivo CSS de Bootstrap -->
    <link href="ruta/a/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Estilos personalizados */
        body {
            padding: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="mb-4">Formulario de Registro</h2>
    <form action="/procesar_formulario.php" method="post">
        <div class="row">
            <div class="col-md-4">
                <div class="mb-3">
                    <label for="id">ID:</label>
                    <input type="text" class="form-control" id="id" name="id">
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label for="usuario">Usuario:</label>
                    <input type="text" class="form-control" id="usuario" name="usuario">
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label for="contrasena">Contraseña:</label>
                    <input type="password" class="form-control" id="contrasena" name="contrasena">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="mb-3">
                    <label for="tipo_usuario">Tipo de Usuario:</label>
                    <select class="form-control" id="tipo_usuario" name="tipo_usuario">
                        <option value="1">Administrador</option>
                        <option value="2">Usuario Regular</option>
                    </select>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Registrar</button>
    </form>
</div>

<!-- Agregar la referencia al archivo JavaScript de Bootstrap (opcional) -->
<script src="ruta/a/bootstrap.bundle.min.js"></script>
</body>
</html>
