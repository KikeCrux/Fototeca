<!-- Modal Structure -->
<div class="modal fade" id="detailsModal<?php echo $row['ID_Tecnica']; ?>" tabindex="-1" aria-labelledby="detailsModalLabel<?php echo $row['ID_Tecnica']; ?>" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailsModalLabel<?php echo $row['ID_Tecnica']; ?>">Seccion Cultural</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>ID Técnica:</strong> <?php echo $row['ID_Tecnica']; ?></p>
                <p><strong>Número Inventario:</strong> <?php echo htmlspecialchars($row['NumeroInventario']); ?></p>
                <p><strong>Clave Técnica:</strong> <?php echo htmlspecialchars($row['ClaveTecnica']); ?></p>
                <p><strong>Proceso Fotográfico:</strong> <?php echo htmlspecialchars($row['ProcesoFotografico']); ?></p>
                <p><strong>Fondo Colección:</strong> <?php echo htmlspecialchars($row['FondoColeccion']); ?></p>
                <p><strong>Formato:</strong> <?php echo htmlspecialchars($row['Formato']); ?></p>
                <p><strong>Número Negativo/Copia:</strong> <?php echo htmlspecialchars($row['NumeroNegativoCopia']); ?></p>
                <p><strong>Tipo:</strong> <?php echo htmlspecialchars($row['Tipo']); ?></p>
                <p><strong>ID Cultural:</strong> <?php echo $row['ID_Cultural']; ?></p>
                <p><strong>ID Datación:</strong> <?php echo $row['ID_Datacion']; ?></p>
                <p><strong>Fecha Asunto:</strong> <?php echo $row['FechaAsunto']; ?></p>
                <p><strong>Fecha Toma:</strong> <?php echo $row['FechaToma']; ?></p>
                <p><strong>ID Ubicación:</strong> <?php echo $row['ID_Ubicacion']; ?></p>
                <p><strong>Lugar Asunto:</strong> <?php echo htmlspecialchars($row['LugarAsunto']); ?></p>
                <p><strong>Lugar Toma:</strong> <?php echo htmlspecialchars($row['LugarToma']); ?></p>
                <p><strong>ID Época:</strong> <?php echo $row['ID_Epoca']; ?></p>
                <p><strong>Época:</strong> <?php echo htmlspecialchars($row['Epoca']); ?></p>
                <p><strong>ID Autoría:</strong> <?php echo $row['ID_Autoria']; ?></p>
                <p><strong>Autor:</strong> <?php echo htmlspecialchars($row['Autor']); ?></p>
                <p><strong>Autor Primigenio:</strong> <?php echo htmlspecialchars($row['AutorPrimigenio']); ?></p>
                <p><strong>Agencia Estudio:</strong> <?php echo htmlspecialchars($row['AgenciaEstudio']); ?></p>
                <p><strong>Editor Coleccionista:</strong> <?php echo htmlspecialchars($row['EditorColeccionista']); ?></p>
                <p><strong>Lema:</strong> <?php echo htmlspecialchars($row['Lema']); ?></p>
                <p><strong>ID Indicativo:</strong> <?php echo $row['ID_Indicativo']; ?></p>
                <p><strong>Sello:</strong> <?php echo htmlspecialchars($row['Sello']); ?></p>
                <p><strong>Cuño:</strong> <?php echo htmlspecialchars($row['Cuño']); ?></p>
                <p><strong>Firma:</strong> <?php echo htmlspecialchars($row['Firma']); ?></p>
                <p><strong>Etiqueta:</strong> <?php echo htmlspecialchars($row['Etiqueta']); ?></p>
                <p><strong>Imprenta:</strong> <?php echo htmlspecialchars($row['Imprenta']); ?></p>
                <p><strong>Otro:</strong> <?php echo htmlspecialchars($row['Otro']); ?></p>
                <p><strong>ID Denominación:</strong> <?php echo $row['ID_Denominacion']; ?></p>
                <p><strong>Título Origen:</strong> <?php echo htmlspecialchars($row['TituloOrigen']); ?></p>
                <p><strong>Título Catalográfico:</strong> <?php echo htmlspecialchars($row['TituloCatalografico']); ?></p>
                <p><strong>Título Serie:</strong> <?php echo htmlspecialchars($row['TituloSerie']); ?></p>
                <p><strong>ID Descriptores:</strong> <?php echo $row['ID_Descriptores']; ?></p>
                <p><strong>Tema Principal:</strong> <?php echo htmlspecialchars($row['TemaPrincipal']); ?></p>
                <p><strong>Descriptores:</strong> <?php echo nl2br(htmlspecialchars($row['Descriptores'])); ?></p>
                <p><strong>ID Protagonistas:</strong> <?php echo $row['ID_Protagonistas']; ?></p>
                <p><strong>Personajes:</strong> <?php echo nl2br(htmlspecialchars($row['Personajes'])); ?></p>
                <p><strong>ID Observaciones:</strong> <?php echo $row['ID_Observaciones']; ?></p>
                <p><strong>Inscripción Original:</strong> <?php echo nl2br(htmlspecialchars($row['InscripcionOriginal'])); ?></p>
                <p><strong>Conjunto:</strong> <?php echo htmlspecialchars($row['Conjunto']); ?></p>
                <p><strong>Anotaciones:</strong> <?php echo nl2br(htmlspecialchars($row['Anotaciones'])); ?></p>
                <p><strong>Números Intersección:</strong> <?php echo nl2br(htmlspecialchars($row['NumerosInterseccion'])); ?></p>
                <p><strong>Documentación Asociada:</strong> <?php echo nl2br(htmlspecialchars($row['DocumentacionAsociada'])); ?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>