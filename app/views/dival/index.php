<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800">Maestro de Clientes</h1>
    <a href="<?php echo BASE_URL; ?>/cliente/crear" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Nuevo Cliente
    </a>
</div>

<!-- Alertas -->
<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>
        <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i>
        <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- Card Principal -->
<div class="card shadow mb-4">
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Listado de Clientes</h6>
        <div class="btn-group">
            <button class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-download me-1"></i>Exportar
            </button>
            <button class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-print me-1"></i>Imprimir
            </button>
        </div>
    </div>
    <div class="card-body">
        <?php if (empty($clientes)): ?>
            <div class="text-center py-5">
                <i class="fas fa-users fa-4x text-muted mb-3"></i>
                <h4 class="text-muted">No hay clientes registrados</h4>
                <p class="text-muted mb-4">Comienza agregando tu primer cliente al sistema.</p>
                <a href="<?php echo BASE_URL; ?>/cliente/crear" class="btn btn-primary btn-lg">
                    <i class="fas fa-plus me-2"></i>Agregar Primer Cliente
                </a>
            </div>
        <?php else: ?>
            <!-- Filtros -->
            <div class="row mb-3">
                <div class="col-md-4">
                    <input type="text" class="form-control" placeholder="Buscar cliente..." id="searchInput">
                </div>
                <div class="col-md-3">
                    <select class="form-select" id="filterRisk">
                        <option value="">Todos los niveles de riesgo</option>
                        <option value="1">Nivel 1 - Bajo</option>
                        <option value="2">Nivel 2 - Medio</option>
                        <option value="3">Nivel 3 - Alto</option>
                        <option value="4">Nivel 4 - Muy Alto</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select" id="filterStatus">
                        <option value="">Todos los estados</option>
                        <option value="active">Activos</option>
                        <option value="inactive">Inactivos</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-outline-secondary w-100" id="clearFilters">
                        <i class="fas fa-times me-1"></i>Limpiar
                    </button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped" id="tablaClientes" width="100%" cellspacing="0">
                    <thead class="table-dark">
                        <tr>
                            <th width="80">ID</th>
                            <th>Documento</th>
                            <th>Dirección</th>
                            <th width="120">Cupo</th>
                            <th width="120">Saldo</th>
                            <th width="100">Nivel Riesgo</th>
                            <th width="120">Fecha Registro</th>
                            <th width="150" class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($clientes as $cliente): ?>
                        <tr>
                            <td class="fw-bold">#<?php echo $cliente['id_dvcliente']; ?></td>
                            <td>
                                <div class="fw-semibold"><?php echo $cliente['TerDocId']; ?></div>
                                <small class="text-muted"><?php echo $cliente['tipo_documento'] ?? 'N/A'; ?></small>
                            </td>
                            <td>
                                <?php if ($cliente['direccion_residencia']): ?>
                                    <?php echo substr($cliente['direccion_residencia'], 0, 50); ?>
                                    <?php if (strlen($cliente['direccion_residencia']) > 50): ?>...<?php endif; ?>
                                <?php else: ?>
                                    <span class="text-muted">N/A</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end fw-bold text-success">
                                $<?php echo number_format($cliente['valor_cupo'], 2); ?>
                            </td>
                            <td class="text-end fw-bold <?php echo $cliente['valor_saldo'] > 0 ? 'text-warning' : 'text-success'; ?>">
                                $<?php echo number_format($cliente['valor_saldo'], 2); ?>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-<?php echo $this->getRiskBadgeClass($cliente['niel_riezgo']); ?>">
                                    Nivel <?php echo $cliente['niel_riezgo']; ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <small><?php echo date('d/m/Y', strtotime($cliente['creado_el'])); ?></small>
                                <br>
                                <small class="text-muted"><?php echo date('H:i', strtotime($cliente['creado_el'])); ?></small>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm">
                                    <a href="<?php echo BASE_URL; ?>/cliente/editar/<?php echo $cliente['id_dvcliente']; ?>" 
                                       class="btn btn-outline-primary" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="#" class="btn btn-outline-info" title="Ver Detalles">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="#" class="btn btn-outline-warning" title="Historial">
                                        <i class="fas fa-history"></i>
                                    </a>
                                    <button class="btn btn-outline-danger" title="Desactivar" 
                                            onclick="confirmarDesactivacion(<?php echo $cliente['id_dvcliente']; ?>)">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr class="table-light">
                            <td colspan="3" class="text-end fw-bold">Totales:</td>
                            <td class="text-end fw-bold text-success">
                                $<?php echo number_format(array_sum(array_column($clientes, 'valor_cupo')), 2); ?>
                            </td>
                            <td class="text-end fw-bold text-warning">
                                $<?php echo number_format(array_sum(array_column($clientes, 'valor_saldo')), 2); ?>
                            </td>
                            <td colspan="3"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Estadísticas -->
            <div class="row mt-4">
                <div class="col-md-3">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Total Clientes
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        <?php echo count($clientes); ?>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-users fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        Cupo Total
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        $<?php echo number_format(array_sum(array_column($clientes, 'valor_cupo')), 2); ?>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Búsqueda en tiempo real
    const searchInput = document.getElementById('searchInput');
    const table = document.getElementById('tablaClientes');
    const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
    
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            const filter = this.value.toLowerCase();
            for (let row of rows) {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(filter) ? '' : 'none';
            }
        });
    }

    // Limpiar filtros
    const clearFilters = document.getElementById('clearFilters');
    if (clearFilters) {
        clearFilters.addEventListener('click', function() {
            searchInput.value = '';
            document.getElementById('filterRisk').value = '';
            document.getElementById('filterStatus').value = '';
            for (let row of rows) {
                row.style.display = '';
            }
        });
    }
});

function confirmarDesactivacion(id) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "El cliente será desactivado del sistema",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, desactivar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Aquí iría la llamada AJAX para desactivar
            Swal.fire(
                'Desactivado!',
                'El cliente ha sido desactivado.',
                'success'
            );
        }
    });
}
</script>