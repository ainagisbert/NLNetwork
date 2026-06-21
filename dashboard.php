<!-- Perfil de l'usuari de la sessió on pot gestionar les seves publicacions -->
<?php
session_start();

if (!isset($_SESSION['id_usuari'])) {
    $_SESSION['errorNumber'] = 9;
    $_SESSION['errorMsg'] = "Has d'iniciar sessió per accedir a aquesta pàgina.";
    header("Location: error.php");
    exit;
}

require_once('BL/Usuari.php');
require_once('helpers/display.php');

// Info usuari de la sessió i els seus posts
$usuari = new Usuari($_SESSION["id_usuari"]);
$isLogged = isset($_SESSION['id_usuari']);

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
  <title>Perfil</title>
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css  " rel="stylesheet">
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
        <form class="d-flex me-3" action="visor.php" method="GET" role="search" style="max-width: 280px;">
          <input class="form-control form-control-sm" type="search" name="alies" placeholder="Cerca..." aria-label="Cerca continguts">
        </form>
        <!-- Menú de navegació -->
        <ul class="navbar-nav align-items-center gap-2">
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
        </ul>
      </div>
    </div>
  </nav>

  <!-- Offcanvas de mòbil (desplegable lateral des de la dreta) -->
  <div class="offcanvas offcanvas-end bg-dark" tabindex="-1" id="navbarOffcanvas" aria-labelledby="navbarOffcanvasLabel">
    <div class="offcanvas-header">
      <h5 class="offcanvas-title text-white" id="navbarOffcanvasLabel">Menú</h5>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Tanca"></button>
    </div>
    <div class="offcanvas-body d-flex flex-column align-items-end">
      <form class="d-flex w-100 mb-4" role="search" action="visor.php" method="GET">
        <input class="form-control form-control-sm" type="search" name="alies" placeholder="Cerca..." aria-label="Cerca continguts">
      </form>
      <button class="btn btn-primary w-100 mb-2" data-bs-toggle="modal" data-bs-target="#createTextModal">
        <i class="bi bi-bookmark-plus"></i> Crear Text
      </button>
      <a class="btn btn-outline-light w-100 mb-2 text-start" href="dashboard.php">
        <i class="bi bi-person-circle"></i> Perfil
      </a>
      <a class="btn btn-outline-warning w-100 text-start" href="BL/logout.php">
        <i class="bi bi-box-arrow-right"></i> Tanca sessió
      </a>
    </div>
  </div>

  <!-- Contingut principal -->
  <div class="container mt-4">
    <div class="row">
      <!-- Columna del perfil -->
      <div class="col-12 col-lg-3 mb-4">
        <div class="card bg-dark text-white shadow-sm">
          <div class="text-center pt-3">
            <img src="<?= $avatar ?>" class="rounded-circle shadow-1-strong mx-auto big-avatar" alt="avatar">
            <h5 class="mt-2 text-warning fw-bold">@<?= $alies ?></h5>
            <p class="mb-1"><?= $nom ?></p>
            <p class="px-3 mb-0 text-info small"> <?= $descripcio ?> </p>
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
          <button class="btn btn-outline-warning btn-sm mt-3 w-100" data-bs-toggle="modal" data-bs-target="#editProfileModal">Editar perfil</button>
        </div>
      </div>
    </div>
    
    <!-- Publicacions de l'usuari ordenats de més nou a més antic -->
    <div class="col-12 col-lg-9">
      <h4 class="mb-3">Les meves publicacions</h4>
      <?php if (!empty($posts)): ?>
        <?php $categoriaNoms = getCategoriaNoms(); ?>
        <?php foreach ($posts as $post): ?>
          <?php
          // Carreguem els comentaris del post
          $post->loadComments(); 
          $comentaris = $post->getComentaris();
          ?>
          <div class="card shadow-sm bg-dark text-white text-card mb-2 position-relative">
            <div class="card-body">
              <div class="d-flex flex-start align-items-center">
                <img class="rounded-circle shadow-1-strong me-3 avatar" src="<?= $avatar ?>" alt="avatar">
                <div>
                  <div class="d-flex align-items-center">
                    <h6 class="fw-bold text-warning mb-0 me-1">@<?= $alies ?></h6>
                    <span class="text-info small me-1">(Tu)</span>
                    <button type="button" class="btn btn-sm text-info" data-bs-toggle="modal" data-bs-target="#deletePostModal" data-id="<?= $post->getIdPost() ?>">
                      <i class="bi bi-trash text-danger"></i>
                    </button>
                  </div>
                  <p class="text-info small mb-0"><?= formatDateCa($post->getData()) ?></p>
                </div>
              </div>
              <p class="mt-3 mb-3"><?= $post->getContingut() ?></p>
              <?php if (!empty($post->getImatge())): ?>
                <img src="<?= $post->getImatge() ?>" class="img-fluid rounded mb-3 publication-image" alt="Foto publicació">
              <?php endif; ?>
              <div class="d-flex justify-content-start gap-2 mb-1">
                <?php
                $likeState = getLikeButtonState($isLogged, function() use ($post, $usuari) {
                  return $usuari->hasLikedPost($post->getIdPost());
                });
                ?>
                <form method="POST" action="BL/like_post.php" style="display:inline;">
                  <input type="hidden" name="id_publicacio" value="<?= (int)$post->getIdPost() ?>">
                  <button class="btn <?= $likeState['class'] ?> btn-sm" <?= $likeState['disabled'] ?>>
                    <i class="bi bi-hand-thumbs-up"></i> <?= $post->getLikes() ?>
                  </button>
                </form>
                <button class="btn btn-outline-info btn-sm btn-comment">
                  <i class="bi bi-chat"></i> <?= (int)($post->getNumComentaris()) ?>
                </button>
              </div>
              <small class="text-info">Categoria: <?= $categoriaNoms[$post->getCategoria()] ?></small>
              
              <!-- Secció comentaris sense formulari, la intenció és que només els pugui consultar -->
              <div class="card-footer bg-dark my-3 border-0" style="display: none;">
                <!-- Comentaris ordenats de més antic al més nou -->
                <?php if(!empty($comentaris)): ?>
                  <?php foreach ($comentaris as $comentari): ?>
                    <div class="card shadow-lg bg-dark text-white text-card mb-0 mt-3" style="max-width: 100%;">
                      <div class="card-body py-3 px-3">
                        <?php
                        // Info de l'autor del comentari
                        $autorCom = new Usuari($comentari->getUser());
                        $avatarCom = $autorCom->getAvatar();
                        $aliesCom = $autorCom->getAlies();
                        $esMeuCom = ($isLogged && $comentari->getUser() == (int)$_SESSION['id_usuari']);
                        ?>
                        <div class="d-flex align-items-start mb-1">
                          <img class="rounded-circle shadow-1-strong me-2 comment-avatar" src="<?= $avatarCom ?>" alt="avatar">
                          <div>
                            <?php if ($esMeuCom): ?>
                              <div class="d-flex align-items-center">
                                <h6 class="fw-bold text-warning mb-0 me-1">@<?= $aliesCom ?></h6>
                                <span class="text-info small">(Tu)</span>
                                <button type="button" class="btn btn-sm text-info mb-0" data-bs-toggle="modal" data-bs-target="#deleteCommentModal" data-id="<?= $comentari->getIdCom() ?>">
                                  <i class="bi bi-trash text-danger"></i>
                                </button>
                              </div>
                            <?php else: ?>
                              <h6 class="fw-bold text-warning fs-6">@<a href="visor.php?alies=<?= urlencode($aliesCom) ?>" class="link-warning link-offset-2 link-underline-opacity-0 link-underline-opacity-100-hover"><?= $aliesCom ?></a></h6>
                            <?php endif; ?>
                            <p class="text-info small mb-0"><?= formatDateCa($comentari->getData()) ?></p>
                          </div>
                        </div>
                        <p class="mt-3 mb-3"><?= $comentari->getContingut() ?></p>
                        <div class="d-flex justify-content-start gap-2 mt-1">
                          <?php
                          // Comprovem si l'usuari logguejat li ha donat like i li donem forma al botó en conseqüència
                          $likeStateCom = getLikeButtonState($isLogged, function() use ($comentari, $usuari) {
                            return $usuari->hasLikedComentari($comentari->getIdCom());
                          });
                          ?>
                          <form method="POST" action="BL/like_comment.php" style="display:inline;">
                            <input type="hidden" name="id_comentari" value="<?= (int)$comentari->getIdCom() ?>">
                            <button class="btn <?= $likeStateCom['class'] ?> btn-sm p-1 px-2" <?= $likeStateCom['disabled'] ?>> 
                              <i class="bi bi-hand-thumbs-up"></i> <?= $comentari->getLikes() ?>
                            </button>
                          </form>
                        </div>
                      </div>
                    </div>
                  <?php endforeach; ?>
                <?php else: ?>
                  <div class="text-center text-info mt-4"><i class="bi bi-inbox"></i> No hi ha cap comentari.</div>
                <?php endif; ?>
              </div>    
            </div>
          </div>
        <?php endforeach; ?>
          <div class="text-center text-muted mt-4"><i class="bi bi-inbox"></i> No hi ha més publicacions.</div>
      <?php else: ?>
        <div class="text-center text-muted mt-4"><i class="bi bi-inbox"></i> No tens cap publicació.</div>
      <?php endif; ?>
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
              <div class="invalid-feedback">Has de seleccionar una categoria.</div>
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

  <!-- Modal Editar Perfil -->
  <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editProfileModalLabel">Configuració del perfil</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tanca"></button>
        </div>
        <div class="modal-body">
          <form class="needs-validation" novalidate action="BL/update.php" method="POST" enctype="multipart/form-data">
            <div class="text-center mb-3">
              <img id="previewAvatar" src="<?= $avatar ?>" class="rounded-circle mb-2" alt="Foto de perfil" width="80" height="80">
              <br>
              <label for="avatarUpload" class="btn btn-outline-primary btn-sm mt-2">Canvia la foto</label>
              <input type="file" id="avatarUpload" name="avatar" class="d-none" accept="image/*">
            </div>

            <div class="mb-3">
              <label for="profileUsername" class="form-label">Nom d'usuari</label>
              <input type="text" class="form-control border-dark" id="profileUsername" name="alies" value="<?= $alies ?>" maxlength="30" required>
              <div class="invalid-feedback">El nom d'usuari és obligatori.</div>
            </div>

            <div class="mb-3">
              <label for="profileName" class="form-label">Nom públic</label>
              <input type="text" class="form-control border-dark" id="profileName" name="nom" value="<?= $nom ?>" required>
              <div class="invalid-feedback">El nom públic és obligatori.</div>
            </div>

            <div class="mb-3">
              <label for="profileBio" class="form-label">Biografia (opcional)</label>
              <textarea class="form-control char-counter border-dark" id="profileBio" rows="3" maxlength="150" name="descripcio"><?= $descripcio ?></textarea>
              <div class="form-text">Màxim 150 caràcters</div>
            </div>

            <div class="mb-3">
              <label for="profilePassword" class="form-label">Nova contrasenya</label>
              <div class="input-group">
                <input type="password" class="form-control border-dark" id="profilePassword" name="contrasenya" placeholder="Deixa en blanc per no canviar">
                <button class="btn btn-dark" type="button" id="toggleNewPassword">
                  <i class="bi bi-eye"></i>
                </button>
              </div>
            </div>

            <div class="form-check mb-3">
              <button type="button" class="btn btn-danger w-100 mb-3" data-bs-toggle="modal" data-bs-target="#deleteProfileModal">
                <i class="bi bi-trash"></i> Esborra el meu perfil
              </button>
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel·lar</button>
              <button type="submit" class="btn btn-primary" id="saveProfileBtn">Desa els canvis</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Esborrar Perfil -->
  <div class="modal fade" id="deleteProfileModal" tabindex="-1" aria-labelledby="deleteProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteProfileModalLabel">Confirmar esborrat</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tanca"></button>
        </div>
        <div class="modal-body">
          <p class="mb-1">Estàs segur que vols esborrar el teu perfil?</p>
          <small class="text-muted">Aquesta acció és irreversible i eliminarà totes les teves publicacions.</small>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel·lar</button>
          <form action="BL/delete.php" method="POST" style="display:inline;">
            <input type="hidden" name="action" value="deleteUser">
            <button type="submit" class="btn btn-danger">
              <i class="bi bi-trash"></i> Esborra permanentment
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Esborrar Post -->
  <div class="modal fade" id="deletePostModal" tabindex="-1" aria-labelledby="deletePostModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deletePostModalLabel">Esborrar publicació</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tanca"></button>
        </div>
        <div class="modal-body">
          <p class="mb-1">Estàs segura que vols esborrar aquesta publicació?</p>
          <small class="text-muted">Aquesta acció no es pot desfer.</small>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel·lar</button>
          <form action="BL/delete_post.php" method="POST" style="display:inline;">
            <input type="hidden" name="action" value="deletePost">
            <input type="hidden" name="id_publicacio" id="deletePostId">
            <button type="submit" class="btn btn-danger">
              <i class="bi bi-trash"></i> Esborra
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Esborrar Comentari -->
  <div class="modal fade" id="deleteCommentModal" tabindex="-1" aria-labelledby="deleteCommentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteCommentModalLabel">Esborrar comentari</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tanca"></button>
        </div>
        <div class="modal-body">
          <p class="mb-1">Estàs segura que vols esborrar aquest comentari?</p>
          <small class="text-muted">Aquesta acció no es pot desfer.</small>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel·lar</button>
          <form action="BL/delete_comment.php" method="POST" style="display:inline;">
            <input type="hidden" name="action" value="deleteComment">
            <input type="hidden" name="id_comentari" id="deleteCommentId">
            <button type="submit" class="btn btn-danger">
              <i class="bi bi-trash"></i> Esborra
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Alerta de perfil actualitzat -->
  <?php if (isset($_GET['updated']) && $_GET['updated'] == 'true'): ?>
  <div id="profileSavedAlert" class="alert alert-success alert-dismissible fade show fixed-top w-75 mx-auto mt-3" role="alert" style="z-index: 1050;">
    <i class="bi bi-check-circle-fill me-2"></i>El perfil s'ha actualitzat correctament.
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tanca"></button>
  </div>
  <?php endif; ?>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js  "></script>
  <script>
    // Limitar caràcters del textarea principal  
    document.querySelectorAll('.char-counter').forEach(textarea => {
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

      updateCounter();
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

    // Dismiss alerta perfil actualitzat
    const alert = document.getElementById('profileSavedAlert');
    if (alert) {
      setTimeout(() => {
        alert.classList.remove('show');
        setTimeout(() => {
          alert.remove();
        }, 150);
      }, 3000);
    }

    // Passar ID publicació i comentari
    const deletePostModal = document.getElementById('deletePostModal');
    deletePostModal.addEventListener('show.bs.modal', function (event) {
      const button = event.relatedTarget;
      const postId = button.getAttribute('data-id');

      document.getElementById('deletePostId').value = postId;
    });

    const deleteCommentModal = document.getElementById('deleteCommentModal');
    deleteCommentModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const commentId = button.getAttribute('data-id');
        document.getElementById('deleteCommentId').value = commentId;
    });

    // Funció reutilitzable per alternar visibilitat contrasenya
    function setupPasswordToggle(toggleButtonId, inputId) {
      const toggleBtn = document.querySelector(`#${toggleButtonId}`);
      const input = document.querySelector(`#${inputId}`);

      toggleBtn?.addEventListener('click', () => {
        const isPassword = input.getAttribute('type') === 'password';
        input.setAttribute('type', isPassword ? 'text' : 'password');
        toggleBtn.innerHTML = isPassword
          ? '<i class="bi bi-eye-slash"></i>'
          : '<i class="bi bi-eye"></i>';
      });
    }

    // Aplica a tots dos camps
    setupPasswordToggle('toggleNewPassword', 'profilePassword')
  </script>
</body>
</html>