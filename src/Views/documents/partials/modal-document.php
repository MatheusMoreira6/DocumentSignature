<div class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" id="modal-document" tabindex="-1" aria-labelledby="modal-document-label" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="modal-document-label">Enviar Documento</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="container-fluid">
                    <form id="form-document-upload" class="needs-validation" novalidate>
                        <div class="row g-2">
                            <div class="col-12">
                                <label for="file" class="form-label fw-semibold">Arquivo</label>
                                <input type="file" class="form-control" id="file" name="file" required>
                            </div>

                            <div class="col-12 pt-4 pb-2">
                                <span class="fw-semibold fs-6">Signatários</span>

                                <button type="button" class="btn btn-outline-secondary btn-sm float-end" id="btn-add-signer">
                                    <i class="bi bi-plus-lg"></i>
                                    Adicionar Signatário
                                </button>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body bg-light">
                                <div class="row g-2">
                                    <div class="col-12">
                                        <span class="fw-semibold fs-6">Signatário</span>

                                        <button type="button" class="btn btn-outline-secondary btn-sm float-end" disabled>
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </div>

                                    <div class="col-12 col-lg-6">
                                        <label for="signers_0_name" class="form-label fw-semibold">Nome Completo</label>
                                        <input type="text" class="form-control" name="signers[0][name]" id="signers_0_name" placeholder="Nome Completo" required>
                                        <div class="invalid-feedback">Informe o nome do signatário.</div>
                                    </div>

                                    <div class="col-12 col-lg-6">
                                        <label for="signers_0_cpf" class="form-label fw-semibold">CPF</label>
                                        <input type="text" class="form-control" name="signers[0][cpf]" id="signers_0_cpf" placeholder="CPF" required>
                                        <div class="invalid-feedback">Informe o CPF do signatário.</div>
                                    </div>

                                    <div class="col-12 col-lg-6">
                                        <label for="signers_0_email" class="form-label fw-semibold">E-mail</label>
                                        <input type="email" class="form-control" name="signers[0][email]" id="signers_0_email" placeholder="E-mail" required>
                                        <div class="invalid-feedback">Informe o e-mail do signatário.</div>
                                    </div>

                                    <div class="col-12 col-lg-6">
                                        <label for="signers_0_type" class="form-label fw-semibold">Tipo de Assinatura</label>

                                        <select class="form-select" name="signers[0][type]" id="signers_0_type" required>
                                            <option value="" disabled selected>Selecione</option>

                                            <?php foreach ($signature_types as $signature_type) { ?>
                                                <option value="<?= $signature_type['id'] ?>"><?= $signature_type['description'] ?></option>
                                            <?php } ?>
                                        </select>

                                        <div class="invalid-feedback">Selecione um tipo de assinatura.</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="modal-footer">
                <button type="reset" class="btn btn-outline-secondary" form="form-document-upload" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg"></i>
                    Cancelar
                </button>

                <button type="submit" class="btn btn-dark" form="form-document-upload">
                    <i class="bi bi-send"></i>
                    Enviar
                </button>
            </div>
        </div>
    </div>
</div>

<script src="<?= asset('app/js/documents/modal-document.js?v=' . time()) ?>"></script>