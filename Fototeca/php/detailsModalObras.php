<div class="modal fade" id="detailsModal<?php echo $row['ID_Tecnica']; ?>" tabindex="-1" aria-labelledby="detailsModalLabel<?php echo $row['ID_Tecnica']; ?>" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailsModalLabel<?php echo $row['ID_Tecnica']; ?>">Sección Cultural</h5>
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
                <p><strong>Fecha Asunto:</strong> <?php echo isset($row['FechaAsunto']) ? $row['FechaAsunto'] : 'No disponible'; ?></p>
                <p><strong>Fecha Toma:</strong> <?php echo isset($row['FechaToma']) ? $row['FechaToma'] : 'No disponible'; ?></p>
                <p><strong>Lugar Asunto:</strong> <?php echo isset($row['LugarAsunto']) ? htmlspecialchars($row['LugarAsunto']) : 'No disponible'; ?></p>
                <p><strong>Lugar Toma:</strong> <?php echo isset($row['LugarToma']) ? htmlspecialchars($row['LugarToma']) : 'No disponible'; ?></p>
                <p><strong>Época:</strong> <?php echo isset($row['Epoca']) ? htmlspecialchars($row['Epoca']) : 'No disponible'; ?></p>
                <p><strong>Autor:</strong> <?php echo isset($row['Autor']) ? htmlspecialchars($row['Autor']) : 'No disponible'; ?></p>
                <p><strong>Autor Primigenio:</strong> <?php echo isset($row['AutorPrimigenio']) ? htmlspecialchars($row['AutorPrimigenio']) : 'No disponible'; ?></p>
                <p><strong>Agencia Estudio:</strong> <?php echo isset($row['AgenciaEstudio']) ? htmlspecialchars($row['AgenciaEstudio']) : 'No disponible'; ?></p>
                <p><strong>Editor Coleccionista:</strong> <?php echo isset($row['EditorColeccionista']) ? htmlspecialchars($row['EditorColeccionista']) : 'No disponible'; ?></p>
                <p><strong>Lema:</strong> <?php echo isset($row['Lema']) ? htmlspecialchars($row['Lema']) : 'No disponible'; ?></p>
                <p><strong>Sello:</strong> <?php echo isset($row['Sello']) ? htmlspecialchars($row['Sello']) : 'No disponible'; ?></p>
                <p><strong>Cuño:</strong> <?php echo isset($row['Cuño']) ? htmlspecialchars($row['Cuño']) : 'No disponible'; ?></p>
                <p><strong>Firma:</strong> <?php echo isset($row['Firma']) ? htmlspecialchars($row['Firma']) : 'No disponible'; ?></p>
                <p><strong>Etiqueta:</strong> <?php echo isset($row['Etiqueta']) ? htmlspecialchars($row['Etiqueta']) : 'No disponible'; ?></p>
                <p><strong>Imprenta:</strong> <?php echo isset($row['Imprenta']) ? htmlspecialchars($row['Imprenta']) : 'No disponible'; ?></p>
                <p><strong>Otro:</strong> <?php echo isset($row['Otro']) ? htmlspecialchars($row['Otro']) : 'No disponible'; ?></p>
                <p><strong>Título Origen:</strong> <?php echo isset($row['TituloOrigen']) ? htmlspecialchars($row['TituloOrigen']) : 'No disponible'; ?></p>
                <p><strong>Título Catalográfico:</strong> <?php echo isset($row['TituloCatalografico']) ? htmlspecialchars($row['TituloCatalografico']) : 'No disponible'; ?></p>
                <p><strong>Título Serie:</strong> <?php echo isset($row['TituloSerie']) ? htmlspecialchars($row['TituloSerie']) : 'No disponible'; ?></p>
                <p><strong>Tema Principal:</strong> <?php echo isset($row['TemaPrincipal']) ? htmlspecialchars($row['TemaPrincipal']) : 'No disponible'; ?></p>
                <p><strong>Descriptores:</strong> <?php echo isset($row['Descriptores']) ? nl2br(htmlspecialchars($row['Descriptores'])) : 'No disponible'; ?></p>
                <p><strong>Personajes:</strong> <?php echo isset($row['Personajes']) ? nl2br(htmlspecialchars($row['Personajes'])) : 'No disponible'; ?></p>
                <p><strong>Inscripción Original:</strong> <?php echo isset($row['InscripcionOriginal']) ? nl2br(htmlspecialchars($row['InscripcionOriginal'])) : 'No disponible'; ?></p>
                <p><strong>Conjunto:</strong> <?php echo isset($row['Conjunto']) ? htmlspecialchars($row['Conjunto']) : 'No disponible'; ?></p>
                <p><strong>Anotaciones:</strong> <?php echo isset($row['Anotaciones']) ? nl2br(htmlspecialchars($row['Anotaciones'])) : 'No disponible'; ?></p>
                <p><strong>Números Intersección:</strong> <?php echo isset($row['NumerosInterseccion']) ? nl2br(htmlspecialchars($row['NumerosInterseccion'])) : 'No disponible'; ?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>