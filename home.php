<?php
session_start();
?>

<!-- Publicacions de la xarxa separades en categories -->
<!DOCTYPE html>
<html lang="ca">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>NLNetwork</title>
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <!-- CSS personalitzat -->
  <link rel="stylesheet" href="./scss/reducido.css">
</head>
<body class="bg-white">

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container">
      <a class="navbar-brand" href="home.php">
        <img src="images/logo.png" alt="Logo NLN" height="30">
      </a>

      <!-- Botó per a mòbil: obre offcanvas des de la dreta -->
      <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#navbarOffcanvas" aria-controls="navbarOffcanvas" aria-label="Mostra el menú">
        <span class="navbar-toggler-icon"></span>
      </button>

      <!-- Menú només visible per a escriptori -->
      <div class="collapse navbar-collapse justify-content-end d-none d-lg-flex">
        <!-- Barra de cerca -->
        <form class="d-flex me-3" role="search" action="visor.php" method="GET" style="max-width: 280px;">
          <input class="form-control form-control-sm" type="search" name="alies" placeholder="Cerca..." aria-label="Cerca continguts">
        </form>
        <!-- Menú de navegació -->
        <ul class="navbar-nav align-items-center gap-2">
          <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
            <li class="nav-item">
              <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createTextModal">
                <i class="bi bi-bookmark-plus"></i> Crear Text
              </button>
            </li>
            <li class="nav-item">
              <a class="nav-link text-white" href="dashboard.php"><i class="bi bi-person-circle"></i> Perfil</a>
            </li>
            <li class="nav-item">
              <a class="btn btn-outline-warning" href="BL/logout.php"><i class="bi bi-box-arrow-right"></i> Tanca sessió</a>
            </li>
          <?php else: ?>
            <li class="nav-item">
              <a class="btn btn-outline-warning" href="index.html"><i class="bi bi-door-open"></i> Inicia sessió</a>
            </li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Offcanvas (desplegable lateral des de la dreta) -->
  <div class="offcanvas offcanvas-end bg-dark" tabindex="-1" id="navbarOffcanvas" aria-labelledby="navbarOffcanvasLabel">
    <div class="offcanvas-header">
      <h5 class="offcanvas-title text-white" id="navbarOffcanvasLabel">Menú</h5>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Tanca"></button>
    </div>
    <div class="offcanvas-body d-flex flex-column align-items-end">
      <!-- Barra de cerca (mòbil) -->
      <form class="d-flex w-100 mb-4" role="search" action="visor.php" method="GET">
        <input class="form-control form-control-sm" type="search" name="alies" placeholder="Cerca..." aria-label="Cerca continguts">
      </form>
      <button class="btn btn-primary w-100 mb-2" data-bs-toggle="modal" data-bs-target="#createTextModal">
        <i class="bi bi-bookmark-plus"></i> Crear Text
      </button>
      <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
        <a class="btn btn-outline-light w-100 mb-2 text-start" href="dashboard.php">
          <i class="bi bi-person-circle"></i> Perfil
        </a>
      <?php endif; ?>
      <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
        <a class="btn btn-outline-warning w-100 text-start" href="BL/logout.php">
          <i class="bi bi-box-arrow-right"></i> Tanca sessió
        </a>
      <?php else: ?>
        <a class="btn btn-outline-warning w-100 mb-2 text-start" href="index.html">
          <i class="bi bi-door-open"></i> Inicia sessió
        </a>
      <?php endif; ?>
    </div>
  </div>

  <!-- Contingut principal -->
  <div class="container mt-4">
    <div class="row">
      <!-- Sidebar lateral -->
      <div class="col-12 col-lg-3 mb-2">
        <div class="nav flex-row flex-lg-column nav-pills gap-2 sidebar-nav" id="v-pills-tab" role="tablist" aria-orientation="vertical">
          <button class="nav-link active flex-fill text-center text-lg-start" id="v-pills-haikus-tab" data-bs-toggle="pill" data-bs-target="#v-pills-haikus" type="button" role="tab" aria-controls="v-pills-haikus" aria-selected="true">Haikus</button>
          <button class="nav-link flex-fill text-center text-lg-start" id="v-pills-cites-tab" data-bs-toggle="pill" data-bs-target="#v-pills-cites" type="button" role="tab" aria-controls="v-pills-cites" aria-selected="false">Cites</button>
          <button class="nav-link flex-fill text-center text-lg-start" id="v-pills-pensaments-tab" data-bs-toggle="pill" data-bs-target="#v-pills-pensaments" type="button" role="tab" aria-controls="v-pills-pensaments" aria-selected="false">Pensaments</button>
          <button class="nav-link flex-fill text-center text-lg-start" id="v-pills-altres-tab" data-bs-toggle="pill" data-bs-target="#v-pills-altres" type="button" role="tab" aria-controls="v-pills-altres" aria-selected="false">Altres</button>
        </div>
      </div>

      <!-- Posts -->
      <div class="col-12 col-lg-9">
        <div class="tab-content" id="v-pills-tabContent">

          <!-- Haikus -->
          <div class="tab-pane fade show active" id="v-pills-haikus" role="tabpanel" aria-labelledby="v-pills-haikus-tab" tabindex="0">
            <div class="card shadow-sm bg-dark text-white text-card mb-2">
              <div class="card-body">
                <div class="d-flex flex-start align-items-center">
                  <img class="rounded-circle shadow-1-strong me-3 avatar" src="images/profile_placeholder.jpg" alt="avatar">
                  <div>
                    <div class="d-flex align-items-center">
                      <h6 class="fw-bold text-warning mb-1 me-1">@nomUsuari</h6>
                      <span class="text-info small">(Tu)</span>
                    </div>
                    <p class="text-info small mb-0">17 oct, 2025</p>
                  </div>
                </div>
                <p class="mt-3 mb-3">Aquest és un exemple de text curt, tipus haiku. I una imatge que acompanya la publicació. </p>
                <img src="images/placeholder.jpg" class="img-fluid rounded mb-3 publication-image" alt="Foto publicació">
                <div class="d-flex justify-content-start gap-2 mb-1">
                  <button class="btn btn-outline-warning btn-sm btn-like"><i class="bi bi-hand-thumbs-up"></i> 12</button>
                  <button class="btn btn-outline-info btn-sm btn-comment"><i class="bi bi-chat"></i> 1</button>
                </div>
                <small class="text-info">Categoria: Haikus</small>

                <!-- Secció comentaris (inicialment oculta) -->
                <div class="card-footer bg-dark my-3 border-0" style="display: none;">
                  <form class="needs-validation comment-form" novalidate>
                    <div class="d-flex flex-start w-100">
                      <img class="rounded-circle shadow-1-strong me-3 form-avatar" src="images/profile_placeholder.jpg" alt="avatar">
                      <div class="form-outline w-100">
                        <textarea class="form-control char-counter" rows="3" maxlength="200" required></textarea>
                        <div class="invalid-feedback">
                          El comentari no pot estar buit.
                        </div>
                        <div class="form-text text-end text-info mt-1">200 caràcters restants</div>
                      </div>
                    </div>
                    <div class="float-end mt-1 pt-1">
                      <button type="submit" class="btn btn-warning btn-sm">Comenta</button>
                    </div>
                    <div class="clearfix"></div>
                  </form>
                  <!-- Exemple comentari -->
                  <div class="card shadow-lg bg-dark text-white text-card mb-0 mt-3" style="max-width: 100%;">
                      <div class="card-body py-3 px-3">
                        <div class="d-flex align-items-start mb-1">
                          <img class="rounded-circle shadow-1-strong me-2 comment-avatar" src="images/profile_placeholder.jpg" alt="avatar">
                        <div>
                          <h6 class="fw-bold text-warning mb fs-6">@<a href="visor.php?alies=usuariPublic" class="link-warning link-offset-2 link-underline-opacity-0 link-underline-opacity-100-hover">usuariPublic</a></h6>
                          <p class="text-info small mb-0">20 oct, 2025</p>
                        </div>
                      </div>
                      <p class="mt-3 mb-3">Aquest és un exemple de comentari breu. No es poden tornar a comentar, només fer like.</p>
                      <div class="d-flex justify-content-start gap-2 mt-1">
                        <button class="btn btn-outline-warning btn-sm p-1 px-2"><i class="bi bi-hand-thumbs-up"></i> 32</button>
                      </div>
                      </div>
                    </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Cites -->
          <div class="tab-pane fade" id="v-pills-cites" role="tabpanel" aria-labelledby="v-pills-cites-tab" tabindex="0">
            <!-- Exemple cita -->
             <div class="card shadow-sm bg-dark text-white text-card mb-2">
              <div class="card-body">
                <div class="d-flex flex-start align-items-center">
                  <img class="rounded-circle shadow-1-strong me-3 avatar" src="images/profile_placeholder.jpg" alt="avatar">
                  <div>
                    <h6 class="fw-bold text-warning mb fs-6">@<a href="visor.php?alies=usuariPublic" class="link-warning link-offset-2 link-underline-opacity-0 link-underline-opacity-100-hover">usuariPublic</a></h6>
                    <p class="text-info small mb-0">5 oct, 2025</p>
                  </div>
                </div>
                <p class="mt-3 mb-4">"Aquest és un exemple de text curt, tipus cita." — Autor</p>
                <div class="d-flex justify-content-start gap-2 mb-1">
                  <button class="btn btn-outline-warning btn-sm btn-like"><i class="bi bi-hand-thumbs-up"></i> 31</button>
                  <button class="btn btn-outline-info btn-sm btn-comment"><i class="bi bi-chat"></i> 0</button>
                </div>
                <small class="text-info">Categoria: Cites</small>

                <!-- Secció comentaris (inicialment oculta) -->
                <div class="card-footer bg-dark my-3 border-0" style="display: none;">
                  <form class="needs-validation comment-form" novalidate>
                    <div class="d-flex flex-start w-100">
                      <img class="rounded-circle shadow-1-strong me-3 form-avatar" src="images/profile_placeholder.jpg" alt="avatar">
                      <div class="form-outline w-100">
                        <textarea class="form-control char-counter" rows="3" maxlength="200" required></textarea>
                        <div class="invalid-feedback">
                          El comentari no pot estar buit.
                        </div>
                        <div class="form-text text-end text-info mt-1">200 caràcters restants</div>
                      </div>
                    </div>
                    <div class="float-end mt-1 pt-1">
                      <button type="submit" class="btn btn-warning btn-sm">Comenta</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
          <!-- Pensaments -->
          <div class="tab-pane fade" id="v-pills-pensaments" role="tabpanel" aria-labelledby="v-pills-pensaments-tab" tabindex="0">
            <!-- Exemple pensament -->
            <div class="card shadow-sm bg-dark text-white text-card mb-2">
              <div class="card-body">
                <div class="d-flex flex-start align-items-center">
                  <img class="rounded-circle shadow-1-strong me-3 avatar" src="images/profile_placeholder.jpg" alt="avatar">
                  <div>
                    <h6 class="fw-bold text-warning mb fs-6">@<a href="visor.php?alies=usuariPublic" class="link-warning link-offset-2 link-underline-opacity-0 link-underline-opacity-100-hover">usuariPublic</a></h6>
                    <p class="text-info small mb-0">12 oct, 2025</p>
                  </div>
                </div>
                <p class="mt-3 mb-4">Aquest és un exemple de text curt, tipus pensament.</p>
                <div class="d-flex justify-content-start gap-2 mb-1">
                  <button class="btn btn-outline-warning btn-sm btn-like"><i class="bi bi-hand-thumbs-up"></i> 18</button>
                  <button class="btn btn-outline-info btn-sm btn-comment"><i class="bi bi-chat"></i> 0</button>
                </div>
                <small class="text-info">Categoria: Pensaments</small>

                <!-- Secció comentaris (inicialment oculta) -->
                <div class="card-footer bg-dark my-3 border-0" style="display: none;">
                  <form class="needs-validation comment-form" novalidate>
                    <div class="d-flex flex-start w-100">
                      <img class="rounded-circle shadow-1-strong me-3 form-avatar" src="images/profile_placeholder.jpg" alt="avatar">
                      <div class="form-outline w-100">
                        <textarea class="form-control char-counter" rows="3" maxlength="200" required></textarea>
                        <div class="invalid-feedback">
                          El comentari no pot estar buit.
                        </div>
                        <div class="form-text text-end text-info mt-1">200 caràcters restants</div>
                      </div>
                    </div>
                    <div class="float-end mt-1 pt-1">
                      <button type="submit" class="btn btn-warning btn-sm">Comenta</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
          <!-- Altres -->
          <div class="tab-pane fade" id="v-pills-altres" role="tabpanel" aria-labelledby="v-pills-altres-tab" tabindex="0">
            <!-- Exemple d'altres -->
            <div class="card shadow-sm bg-dark text-white text-card mb-2">
              <div class="card-body">
                <div class="d-flex flex-start align-items-center">
                  <img class="rounded-circle shadow-1-strong me-3 avatar" src="images/profile_placeholder.jpg" alt="avatar">
                  <div>
                    <h6 class="fw-bold text-warning mb fs-6">@<a href="visor.php?alies=usuariPublic" class="link-warning link-offset-2 link-underline-opacity-0 link-underline-opacity-100-hover">usuariPublic</a></h6>
                    <p class="text-info small mb-0">22 oct, 2025</p>
                  </div>
                </div>
                <p class="mt-3 mb-4">Aquest és un exemple de text curt, tipus altres.</p>
                <div class="d-flex justify-content-start gap-2 mb-1">
                  <button class="btn btn-outline-warning btn-sm btn-like"><i class="bi bi-hand-thumbs-up"></i> 37</button>
                  <button class="btn btn-outline-info btn-sm btn-comment"><i class="bi bi-chat"></i> 0</button>
                </div>
                <small class="text-info">Categoria: Altres</small>

                <!-- Secció comentaris (inicialment oculta) -->
                <div class="card-footer bg-dark my-3 border-0" style="display: none;">
                  <form class="needs-validation comment-form" novalidate>
                    <div class="d-flex flex-start w-100">
                      <img class="rounded-circle shadow-1-strong me-3 form-avatar" src="images/profile_placeholder.jpg" alt="avatar">
                      <div class="form-outline w-100">
                        <textarea class="form-control char-counter" rows="3" maxlength="200" required></textarea>
                        <div class="invalid-feedback">
                          El comentari no pot estar buit.
                        </div>
                        <div class="form-text text-end text-info mt-1">200 caràcters restants</div>
                      </div>
                    </div>
                    <div class="float-end mt-1 pt-1">
                      <button type="submit" class="btn btn-warning btn-sm">Comenta</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Crear Text -->
  <div class="modal fade" id="createTextModal" tabindex="-1" aria-labelledby="createTextModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form class="needs-validation" novalidate enctype="multipart/form-data">
          <div class="modal-header">
            <h5 class="modal-title" id="createTextModalLabel">Escriu una publicació</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label for="textContent" class="form-label">Text</label>
              <textarea class="form-control char-counter border-dark" id="textContent" rows="3" maxlength="200" required></textarea>
              <div class="invalid-feedback">
                El text de la publicació és obligatori.
              </div>
              <div id="charCount" class="form-text text-end">200 caràcters restants</div>
            </div>
            <div class="mb-3">
              <label for="textCategory" class="form-label">Categoria</label>
              <select class="form-select border-dark" id="textCategory" required>
                <option value="">Selecciona una categoria</option>
                <option>Haikus</option>
                <option>Cites</option>
                <option>Pensaments</option>
                <option>Altres</option>
              </select>
              <div class="invalid-feedback">
                Has de seleccionar una categoria.
              </div>
            </div>
            <div class="mb-3">
              <label for="textImage" class="form-label">Afegir imatge (opcional)</label>
              <input class="form-control border-dark" type="file" id="textImage" accept="image/*">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Cancel·lar</button>
            <button type="submit" class="btn btn-primary">Publicar</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Limitar caràcters del textarea principal  
    document.querySelectorAll('.char-counter').forEach(textarea => {
      // Troba l'element de recompte associat (el següent element .form-text o similar)
      const counter = textarea.nextElementSibling?.classList.contains('form-text') 
        ? textarea.nextElementSibling 
        : textarea.parentElement.querySelector('.form-text');

      const max = parseInt(textarea.getAttribute('maxlength')) || 200;

      const updateCounter = () => {
        const remaining = max - textarea.value.length;
        if (counter) {
          counter.textContent = `${remaining} caràcters restants`;
        }
      };

    // Inicialitza el recompte
    updateCounter();

    // Escolta els canvis
    textarea.addEventListener('input', updateCounter);
  });

    // Mostrar/ocultar comentaris
    document.querySelectorAll('.btn-comment').forEach(button => {
      button.addEventListener('click', function () {
        const card = this.closest('.card');
        const footer = card.querySelector('.card-footer');
        footer.style.display = footer.style.display === 'none' || footer.style.display === '' ? 'block' : 'none';
      });
    });

    // Validació Bootstrap
    (() => {
      'use strict';
      const forms = document.querySelectorAll('.needs-validation');
      Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
          if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
          }
          form.classList.add('was-validated');
        }, false);
      });
    })();
  </script>
</body>
</html>