<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
    <div class="container-fluid">
        <a class="navbar-brand" href="/Escuela_EGF/index.php">Escuela EGF</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                
                <!-- 1. PROCESOS (Aquí van los formularios de captura y asignación) -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" id="procesosDropdown" role="button" data-bs-toggle="dropdown">
                        Procesos
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="procesosDropdown">
                        <li><a class="dropdown-item" href="../Proceso/Proceso_insgrup.php">Inscripción a Grupos</a></li>
                        <li><a class="dropdown-item" href="../Proceso/Proceso_asignat.php">Asignación de Materias</a></li>
                        <li><a class="dropdown-item" href="../Proceso/Proceso_capcal.php">Captura de Calificaciones</a></li>
                    </ul>
                </li>

                <!-- 2. CATÁLOGOS -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" id="catalogosDropdown" role="button" data-bs-toggle="dropdown">
                        Catálogos
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="catalogosDropdown">
                       <li><a class="dropdown-item" href="../Crude/crudealumnos.php">Alumnos</a></li>
                       <li><a class="dropdown-item" href="../Crude/crudegrupos.php">Grupos</a></li>
                       <li><a class="dropdown-item" href="../Crude/crudemateria.php">Materias</a></li>
                       <li><a class="dropdown-item" href="../Crude/crudeprofesores.php">Profesores</a></li>
                    </ul>
                </li>

                <!-- 3. REPORTES (PURO REPORTE EN TABLA, SIN FORMULARIOS) -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Reportes
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="../Reportes/Reporte_insgrup.php">Lista de alumnos por grupo</a></li>
                        <li><a class="dropdown-item" href="../Reportes/Reporte_asignat.php">Lista de materias por alumno</a></li>
                        <li><a class="dropdown-item" href="../Reportes/Reporte_capcal.php">Calificaciones por materia y grupo</a></li>
                        <li><a class="dropdown-item" href="../Reportes/Reporte_alumnos.php">Boleta de calificaciones</a></li>
                    </ul>
                </li>

            </ul>
        </div>
    </div>
</nav>