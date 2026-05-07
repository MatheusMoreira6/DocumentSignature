<div class="vh-100 vw-100 d-flex">
    <div class="border rounded shadow-sm p-3 m-auto" style="width: 20rem;">
        <h5>Login</h5>

        <hr>

        <form id="form-login" class="row g-3 needs-validation" novalidate>
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
                <a href="<?= url("auth/register") ?>" class="text-decoration-none">
                    Não tem uma conta? Registre-se
                </a>
            </div>

            <div class="col-12">
                <div class="d-grid d-sm-block">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-box-arrow-in-right"></i>
                        Entrar
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript" src="<?= asset("app/js/auth/login.js?v=" . time()) ?>"></script>