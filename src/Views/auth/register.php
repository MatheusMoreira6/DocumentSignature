<div class="vh-100 vw-100 d-flex">
    <div class="border rounded shadow-sm p-3 m-auto" style="width: 20rem;">
        <h5>Cadastro</h5>

        <hr>

        <form id="form-register" class="row g-3 needs-validation" novalidate>
            <div class="col-12">
                <label for="name" class="form-label fw-semibold">Nome</label>
                <input type="text" name="name" id="name" class="form-control" required>
                <div class="invalid-feedback">Digite seu nome</div>
            </div>

            <div class="col-12">
                <label for="email" class="form-label fw-semibold">E-mail</label>
                <input type="email" name="email" id="email" class="form-control" required>
                <div class="invalid-feedback">Digite seu e-mail</div>
            </div>

            <div class="col-12">
                <label for="password" class="form-label fw-semibold">Senha</label>
                <input type="password" name="password" id="password" class="form-control" required>
                <div class="invalid-feedback">Digite sua senha</div>
            </div>

            <div class="col-12">
                <label for="token" class="form-label fw-semibold">Token da Empresa</label>
                <input type="text" name="token" id="token" class="form-control">
            </div>

            <div class="col-12">
                <a href="<?= url("auth/login") ?>" class="text-decoration-none">
                    Já tem uma conta? Faça login
                </a>
            </div>

            <div class="col-12">
                <div class="d-grid d-sm-block">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-person-plus"></i>
                        Registrar
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript" src="<?= asset("app/js/auth/register.js?v=" . time()) ?>"></script>