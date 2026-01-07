<!-- Visualitzador de publicacions d'un usuari -->
<?php
session_start();

require_once('BL/Usuari.php');
require_once('helpers/display.php');

if (!isset($_GET['alies']) || empty(trim($_GET['alies']))) {
    $_SESSION['errorNumber'] = 10;
    $_SESSION['errorMsg'] = "No s'ha especificat cap àlies.";
    header("Location: error.php");
    exit;
}

$aliesBuscat = trim($_GET['alies']);
$usuari = new Usuari($aliesBuscat);

if (!$usuari->getId()) {
    $_SESSION['errorNumber'] = 11;
    $_SESSION['errorMsg'] = "No s'ha trobat cap usuari amb l'àlies: @" . htmlspecialchars($aliesBuscat, ENT_QUOTES, 'UTF-8');
    header("Location: error.php");
    exit;
}

$usuari->loadPosts();
$posts = $usuari->getPosts();

$avatar = $usuari->getAvatar();
$alies = $usuari->getAlies();
$nom = $usuari->getNom();
$descripcio = $usuari->getDescripcio();
$likes = $usuari->getLikes();
$publicacions = count($posts);
?>

<!DOCTYPE html>
<html lang="ca">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Usuari públic</title>
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
              <button class="btn btn-primary disabled" data-bs-toggle="modal" data-bs-target="#createTextModal">
                <i class="bi bi-bookmark-plus"></i> Crear Text
              </button>
            </li>
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
      <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
        <button class="btn btn-primary w-100 mb-2" data-bs-toggle="modal" data-bs-target="#createTextModal">
          <i class="bi bi-bookmark-plus"></i> Crear Text
        </button>
        <a class="btn btn-outline-light w-100 mb-2 text-start" href="dashboard.php">
          <i class="bi bi-person-circle"></i> Perfil
        </a>
        <a class="btn btn-outline-warning w-100 text-start" href="BL/logout.php">
          <i class="bi bi-box-arrow-right"></i> Tanca sessió
        </a>
      <?php else: ?>
        <button class="btn btn-primary w-100 mb-2 disabled" data-bs-toggle="modal" data-bs-target="#createTextModal">
          <i class="bi bi-bookmark-plus"></i> Crear Text
        </button>
        <a class="btn btn-outline-warning w-100 mb-2 text-start" href="index.html">
          <i class="bi bi-door-open"></i> Inicia sessió
        </a>
      <?php endif; ?>
    </div>
  </div>

  <!-- Contingut principal -->
  <div class="container mt-4">
    <div class="row">
      <!-- Columna del perfil públic -->
      <div class="col-12 col-lg-3 mb-4">
        <div class="card bg-dark text-white shadow-sm">
          <div class="text-center pt-3">
            <img src="<?= $avatar ?>" class="rounded-circle mx-auto big-avatar" alt="avatar">
            <h5 class="mt-2 text-warning fw-bold">@<?= $alies ?></h5>
            <p class="mb-1"><?= $nom ?></p>
            <p class="px-3 mb-0 text-info small">
              <?= $descripcio ?>
            </p>
          </div>
          <div class="card-body pt-0">
            <div class="d-flex justify-content-around mt-3 border-top pt-3">
              <div class="text-center">
                <div class="fw-bold"><?= $publicacions ?></div>
                <small>Publicacions</small>
              </div>
              <div class="text-center">
                <div class="fw-bold"><?= $likes ?></div>
                <small>Likes rebuts</small>
              </div>
            </div>
            <button class="btn btn-warning btn-sm mt-3 w-100" disabled>
              <i class="bi bi-eye"></i> Perfil públic
            </button>
          </div>
        </div>
      </div>
      <!-- Publicacions de l'usuari -->
      <div class="col-12 col-lg-9">
        <h4 class="mb-3">Publicacions de @<?= $alies ?></h4>

        <?php if (!empty($posts)): ?>
          <?php $categoriaNoms = getCategoriaNoms(); ?>
          <?php foreach ($posts as $post): ?>
            <div class="card shadow-sm bg-dark text-white text-card mb-2">
              <div class="card-body">
                <div class="d-flex flex-start align-items-center">
                  <img class="rounded-circle shadow-1-strong me-3 avatar" src="<?= $avatar ?>" alt="avatar">
                  <div>
                    <h6 class="fw-bold text-warning mb-1">@<?= $alies ?></h6>
                    <p class="text-info small mb-0">
                      <?= formatDateCa($post->getData()) ?>
                    </p>
                  </div>
                </div>
                <p class="mt-3 mb-3"><?= $post->getContingut() ?></p>
                <?php if (!empty($post->getImatge())): ?>
                  <img src="<?= $post->getImatge() ?>" class="img-fluid rounded mb-3 publication-image" alt="Foto publicació">
                <?php endif; ?>
                <div class="d-flex justify-content-start gap-2 mb-1">
                  <button class="btn btn-outline-warning btn-sm btn-like" disabled>
                    <i class="bi bi-hand-thumbs-up"></i> <?= $post->getLikes() ?>
                  </button>
                  <button class="btn btn-info btn-sm btn-comment" disabled>
                    <i class="bi bi-chat"></i> <?= $post->getNumComentaris() ?>
                  </button>
                </div>
                <small class="text-info">
                  Categoria: <?= $categoriaNoms[$post->getCategoria()] ?>
                </small>
              </div>
            </div>
          <?php endforeach; ?>
          <div class="text-center text-muted mt-4">
            <i class="bi bi-inbox"></i> No hi ha més publicacions.
          </div>
        <?php else: ?>
          <div class="text-center text-muted mt-4">
            <i class="bi bi-inbox"></i> Aquest usuari no té cap publicació.
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>

    <!-- Modal Crear Text -->
  <div class="modal fade" id="createTextModal" tabindex="-1" aria-labelledby="createTextModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form class="needs-validation" novalidate enctype="multipart/form-data" method="POST" action="BL/publish.php">
          <div class="modal-header">
            <h5 class="modal-title" id="createTextModalLabel">Escriu una publicació</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <div class="mb-3">
              <label for="textContent" class="form-label">Text</label>
              <textarea class="form-control char-counter border-dark" id="textContent" rows="3" maxlength="200" name="textContent" required></textarea>
              <div class="invalid-feedback">
                El text de la publicació és obligatori.
              </div>
              <div id="charCount" class="form-text text-end">200 caràcters restants</div>
            </div>
            <div class="mb-3">
              <label for="textCategory" class="form-label">Categoria</label>
              <select class="form-select border-dark" id="textCategory" name="textCategory" required>
                <option value="">Selecciona una categoria</option>
                <option value="1">Haikus</option>
                <option value="2">Cites</option>
                <option value="3">Pensaments</option>
                <option value="4">Altres</option>
              </select>
              <div class="invalid-feedback">
                Has de seleccionar una categoria.
              </div>
            </div>
            <div class="mb-3">
              <label for="textImage" class="form-label">Afegir imatge (opcional)</label>
              <input class="form-control border-dark" type="file" id="textImage" accept="image/*" name="textImage">
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
    // Comptador de caràcters
    const textarea = document.getElementById('textContent');
    const charCount = document.getElementById('charCount');
    const maxChars = 200;
    if (textarea && charCount) {
      textarea.addEventListener('input', () => {
        const remaining = maxChars - textarea.value.length;
        charCount.textContent = `${remaining} caràcters restants`;
      });
    }

    // Toggle comentaris
    document.querySelectorAll('.btn-comment').forEach(button => {
      button.addEventListener('click', function() {
        const card = this.closest('.card');
        const footer = card.querySelector('.card-footer');
        footer.style.display = footer.style.display === 'none' || footer.style.display === '' ? 'block' : 'none';
      });
    });
  </script>
</body>
</html>