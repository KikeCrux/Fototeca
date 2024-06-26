<!-- Modal Structure -->
<div class="modal fade" id="detailsModal<?php echo $row['ID_Personal']; ?>" tabindex="-1" aria-labelledby="detailsModalLabel<?php echo $row['ID_Personal']; ?>" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailsModalLabel<?php echo $row['ID_Personal']; ?>">Detalles del Personal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>Clave:</strong> <?php echo htmlspecialchars($row['Clave']); ?></p>
                <p><strong>Nombre:</strong> <?php echo htmlspecialchars($row['Nombre']); ?></p>
                <p><strong>Puesto / Departamento:</strong> <?php echo htmlspecialchars($row['PuestoDepartamento']); ?></p>
                <p><strong>Observaciones:</strong> <?php echo htmlspecialchars($row['Observaciones']); ?></p>
                <p><strong>Estatus:</strong> <?php echo htmlspecialchars($row['Estatus']); ?></p>
                <h5>Obras Resguardante:</h5>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Autor(es)</th>
                            <th>Objeto / Obra</th>
                            <th>Ubicación</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql_resguardante = "SELECT ID_DatosGenerales, Autores, ObjetoObra, Ubicacion FROM DatosGenerales WHERE ID_Resguardante = ?";
                        $stmt_resguardante = $conn->prepare($sql_resguardante);
                        $stmt_resguardante->bind_param("i", $row['ID_Personal']);
                        $stmt_resguardante->execute();
                        $result_resguardante = $stmt_resguardante->get_result();
                        if ($result_resguardante->num_rows > 0) {
                            while ($obra = $result_resguardante->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $obra["ID_DatosGenerales"] . "</td>";
                                echo "<td>" . htmlspecialchars($obra["Autores"]) . "</td>";
                                echo "<td>" . htmlspecialchars($obra["ObjetoObra"]) . "</td>";
                                echo "<td>" . htmlspecialchars($obra["Ubicacion"]) . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4'>No hay obras asignadas como resguardante</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
                <h5>Obras Asignado:</h5>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Autor(es)</th>
                            <th>Objeto / Obra</th>
                            <th>Ubicación</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql_asignado = "SELECT ID_DatosGenerales, Autores, ObjetoObra, Ubicacion FROM DatosGenerales WHERE ID_Asignado = ?";
                        $stmt_asignado = $conn->prepare($sql_asignado);
                        $stmt_asignado->bind_param("i", $row['ID_Personal']);
                        $stmt_asignado->execute();
                        $result_asignado = $stmt_asignado->get_result();
                        if ($result_asignado->num_rows > 0) {
                            while ($obra = $result_asignado->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $obra["ID_DatosGenerales"] . "</td>";
                                echo "<td>" . htmlspecialchars($obra["Autores"]) . "</td>";
                                echo "<td>" . htmlspecialchars($obra["ObjetoObra"]) . "</td>";
                                echo "<td>" . htmlspecialchars($obra["Ubicacion"]) . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4'>No hay obras asignadas como asignado</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>