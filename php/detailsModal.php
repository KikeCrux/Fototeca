<!-- Modal Structure -->
<div class="modal fade" id="detailsModal<?php echo $row['ID_DatosGenerales']; ?>" tabindex="-1" aria-labelledby="detailsModalLabel<?php echo $row['ID_DatosGenerales']; ?>" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailsModalLabel<?php echo $row['ID_DatosGenerales']; ?>">Detalles del Registro</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>ID:</strong> <?php echo $row['ID_DatosGenerales']; ?></p>
                <p><strong>Autores:</strong> <?php echo htmlspecialchars($row['Autores']); ?></p>
                <p><strong>Objeto/Obra:</strong> <?php echo htmlspecialchars($row['ObjetoObra']); ?></p>
                <p><strong>Ubicación:</strong> <?php echo htmlspecialchars($row['Ubicacion']); ?></p>
                <p><strong>Número de Inventario:</strong> <?php echo htmlspecialchars($row['NoInventario']); ?></p>
                <p><strong>Número de Vale:</strong> <?php echo htmlspecialchars($row['NoVale']); ?></p>
                <p><strong>Fecha de Préstamo:</strong> <?php echo $row['FechaPrestamo'] ? date("d-m-Y", strtotime($row['FechaPrestamo'])) : 'N/A'; ?></p>
                <p><strong>Características:</strong> <?php echo nl2br(htmlspecialchars($row['Caracteristicas'])); ?></p>
                <p><strong>Observaciones:</strong> <?php echo nl2br(htmlspecialchars($row['Observaciones'])); ?></p>
                <p><strong>Imagen de Oficio/Vale:</strong> <?php echo $row['ImagenOficioVale'] ? '<a href="verPDF.php?id=' . $row['ID_DatosGenerales'] . '&type=oficio">Ver PDF</a>' : 'No disponible'; ?></p>
                <p><strong>Imagen de Obra:</strong> <?php echo $row['ImagenObra'] ? '<a href="verPDF.php?id=' . $row['ID_DatosGenerales'] . '&type=obra">Ver PDF</a>' : 'No disponible'; ?></p>
                <p><strong>Clave Resguardante:</strong> <?php echo $row['ClaveResguardante'] . " - " . $row['NombreResguardante']; ?></p>
                <p><strong>Clave Asignado:</strong> <?php echo $row['ClaveAsignado'] . " - " . $row['NombreAsignado']; ?></p>
                <p><strong>Tipo Obra:</strong> <?php echo htmlspecialchars($row['TipoObra']); ?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>