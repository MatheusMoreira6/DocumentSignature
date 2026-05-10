<header>
    <nav class="navbar navbar-expand-md border-bottom">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="bi bi-pencil-square"></i>
                Portal de Assinaturas
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar_alt" aria-controls="navbar_alt" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbar_alt">
                <div class="navbar-nav ms-auto">
                    <a class="nav-link <?= current_route('/') ? 'active' : '' ?>" href="<?= url('/') ?>">
                        <i class="bi bi-file-earmark-text"></i>
                        Documentos
                    </a>

                    <a class="nav-link <?= current_route('/profile') ? 'active' : '' ?>" href="<?= url('/profile') ?>">
                        <i class="bi bi-person"></i>
                        Perfil
                    </a>

                    <button class="nav-link" id="logout">
                        <i class="bi bi-box-arrow-right"></i>
                        Sair
                    </button>
                </div>
            </div>
        </div>
    </nav>
</header>

<script src="<?= asset('app/js/auth/logout.js?v=' . time()) ?>"></script>