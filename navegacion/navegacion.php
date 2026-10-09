<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="#">sae_EGF</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto">
        
        <!-- 1. REGISTRO (PRIMERO) -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle text-white" href="#" id="registroDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Registro
          </a>
          <ul class="dropdown-menu" aria-labelledby="registroDropdown">
            <li><a class="dropdown-item" href="../Registro/Alumno.php">Alumnos</a></li>
            <li><a class="dropdown-item" href="../Registro/Grupos.php">Grupos</a></li>
            <li><a class="dropdown-item" href="../Registro/Materia.php">Materias</a></li>
            <li><a class="dropdown-item" href="../Registro/Profesores.php">Profesores</a></li>
          </ul>
        </li>

        <!-- 2. CATÁLOGOS / CRUDE (SEGUNDO) -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle text-white" href="#" id="catalogosDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Catálogos
          </a>
          <ul class="dropdown-menu" aria-labelledby="catalogosDropdown">
            <li><a class="dropdown-item" href="../Crude/crudealumnos.php">Alumnos</a></li>
            <li><a class="dropdown-item" href="../Crude/crudegrupos.php">Grupos</a></li>
            <li><a class="dropdown-item" href="../Crude/crudemateria.php">Materias</a></li>
            <li><a class="dropdown-item" href="../Crude/crudeprofesores.php">Profesores</a></li>
          </ul>
        </li>

      </ul>
    </div>
  </div>
</nav>