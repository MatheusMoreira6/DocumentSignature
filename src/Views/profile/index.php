<div class="vw-100 d-flex pt-5 pb-4">
    <div class="m-auto" style="width: 45rem;">
        <form class="container-fluid needs-validation" id="form-profile" novalidate>
            <div class="row">
                <div class="col-12">
                    <h4>Editar perfil</h4>
                    <small class="text-secondary">Atualize seus dados e configure o token de integração com o Portal de Assinaturas.</small>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-body">
                    <div class="row g-2">
                        <div class="col-12">
                            <h6>Dados de cadastro</h6>
                        </div>

                        <div class="col-12 col-sm-6 mt-3">
                            <label for="name" class="form-label fw-semibold">Nome</label>
                            <input type="text" class="form-control" id="name" placeholder="Nome" value="<?= $user['name'] ?? '' ?>" disabled>
                        </div>

                        <div class="col-12 col-sm-6 mt-sm-3">
                            <label for="cpf" class="form-label fw-semibold">CPF</label>
                            <input type="text" class="form-control" id="cpf" placeholder="CPF" value="<?= $user['cpf'] ?? '' ?>" disabled>
                        </div>

                        <div class="col-12">
                            <label for="email" class="form-label fw-semibold">E-mail</label>
                            <input type="text" class="form-control" id="email" placeholder="E-mail" value="<?= $user['email'] ?? '' ?>" disabled>
                        </div>

                        <div class="col-12 col-sm-6">
                            <label for="current-password" class="form-label fw-semibold">Senha atual</label>
                            <input type="password" class="form-control" id="current-password" name="current_password" placeholder="Senha atual" autocomplete="off">
                            <div class="invalid-feedback">Digite sua senha atual</div>
                        </div>

                        <div class="col-12 col-sm-6">
                            <label for="new-password" class="form-label fw-semibold">Nova senha</label>
                            <input type="password" class="form-control" id="new-password" name="new_password" placeholder="Nova senha" autocomplete="off">
                            <div class="invalid-feedback">Digite sua nova senha</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-body">
                    <div class="row g-2">
                        <div class="col-12">
                            <h6>Token de integração</h6>
                            <small class="text-secondary">Token gerado em Configurações de Empresas no Portal de Assinaturas. Sem ele, não é possível enviar documentos.</small>
                        </div>

                        <div class="col-12 mt-3">
                            <label for="token" class="form-label fw-semibold">Token</label>
                            <input type="text" id="token" class="form-control" name="token" placeholder="Token" value="<?= $user['token'] ?? '' ?>" autocomplete="off">
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-12 text-end">
                    <div class="d-grid gap-2 d-sm-block">
                        <a href="<?= url('/') ?>" class="btn btn-outline-secondary">
                            <i class="bi bi-x-lg"></i>
                            Cancelar
                        </a>

                        <button type="submit" class="btn btn-dark">
                            <i class="bi bi-floppy"></i>
                            Salvar alterações
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="<?= asset('app/js/profile/profile.js?v=' . time()) ?>"></script>