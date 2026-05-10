<main class="container pt-5 pb-4">
    <div class="row g-2">
        <div class="col-12 col-md-6">
            <h4>Documentos</h4>
            <small class="text-secondary">Envie documentos e acompanhe o status das assinaturas.</small>
        </div>

        <div class="col-12 col-md-6 text-end">
            <div class="d-grid gap-2 d-md-block">
                <button class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-clockwise"></i>
                    Atualizar Status
                </button>

                <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#modal-document">
                    <i class="bi bi-upload"></i>
                    Enviar Documento
                </button>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-body">
            <table id="table-documents" class="table">
                <thead>
                    <tr>
                        <th style="width: 40%;">Documento</th>
                        <th style="width: 20%;">Enviado Em</th>
                        <th style="width: 20%;">Atualizado Em</th>
                        <th style="width: 10%;">Status</th>
                        <th style="width: 10%;">Ações</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</main>

<?php include __DIR__ . '/partials/modal-document.php'; ?>

<script src="<?= asset('app/js/documents/datatable.js?v=' . time()) ?>"></script>