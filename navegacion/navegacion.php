<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
    <div class="container-fluid">
        <a class="navbar-brand" href="/Escuela_EGF/index.php">Escuela EGF</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                
                <!-- 1. REGISTRO -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" id="registroDropdown" role="button" data-bs-toggle="dropdown">
                        Registro
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="registroDropdown">
                        <li><a class="dropdown-item" href="/Escuela_EGF/Registro/Alumno.php">Alumnos</a></li>
                        <li><a class="dropdown-item" href="/Escuela_EGF/Registro/Grupos.php">Grupos</a></li>
                        <li><a class="dropdown-item" href="/Escuela_EGF/Registro/Materia.php">Materias</a></li>
                        <li><a class="dropdown-item" href="/Escuela_EGF/Registro/Profesores.php">Profesores</a></li>
                    </ul>
                </li>

                <!-- 2. CATÁLOGOS / CRUDE -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" id="catalogosDropdown" role="button" data-bs-toggle="dropdown">
                        Catálogos
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="catalogosDropdown">
                        <li><a class="dropdown-item" href="/Escuela_EGF/Crude/crudealumnos.php">Alumnos</a></li>
                        <li><a class="dropdown-item" href="/Escuela_EGF/Crude/crudegrupos.php">Grupos</a></li>
                        <li><a class="dropdown-item" href="/Escuela_EGF/Crude/crudemateria.php">Materias</a></li>
                        <li><a class="dropdown-item" href="/Escuela_EGF/Crude/crudeprofesores.php">Profesores</a></li>
                    </ul>
                </li>

                <!-- 3. REPORTES -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" id="reportesDropdown" role="button" data-bs-toggle="dropdown">
                        Reportes
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="reportesDropdown">
                        <li><a class="dropdown-item" href="/Escuela_EGF/Reportes/Reporte_insgrup.php">Inscripción a Grupos</a></li>
                        <li><a class="dropdown-item" href="/Escuela_EGF/Reportes/Reporte_asignat.php">Asignación de Materias</a></li>
                        <li><a class="dropdown-item" href="/Escuela_EGF/Reportes/Reporte_capcal.php">Captura de Calificaciones</a></li>
                    </ul>
                </li>

            </ul>
        </div>
    </div>
</nav>