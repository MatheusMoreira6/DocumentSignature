$(function () {
    const datatable = new DataTable("#table-documents", {
        ajax: `${BASE_URL}/documents/datatable`,
        language: {
            url: `${BASE_URL}/assets/vendor/datatables/pt-BR.json`,
        },
        processing: true,
        serverSide: true,
        responsive: true,
        order: [1, "desc"],
        columns: [
            { data: "file_name" },
            { data: "created_at", className: "text-center" },
            { data: "updated_at", className: "text-center" },
            { data: "status", className: "text-center" },
            {
                data: null,
                className: "text-center",
                render: () => {
                    const btn_view = `<button class="btn btn-outline-secondary btn-sm view-document"><i class="bi bi-eye"></i></button>`;
                    const btn_delete = `<button class="btn btn-dark btn-sm ms-1 delete-document"><i class="bi bi-trash"></i></button>`;

                    return btn_view + btn_delete;
                },
            },
        ],
    });

    window.reloadDocuments = function () {
        datatable.ajax.reload();
    };

    datatable.on("click", ".view-document", function () {
        const data = datatable.row($(this).closest("tr")).data();

        if (!data.id) {
            Toast.warning("Documento inválido!");
            return;
        }

        window.open(`${BASE_URL}/documents/show?id=${data.id}`, "_blank");
    });

    const deleteDocument = (id) => {
        $.ajax({
            url: `${BASE_URL}/documents/destroy`,
            type: "POST",
            data: { id },
            dataType: "JSON",
            success: function (response) {
                Toast.success(response.message);
                datatable.ajax.reload();
            },
            error: function (jqXHR) {
                handleAjaxError(jqXHR, "Falha ao deletar documento!");
            },
        });
    };

    datatable.on("click", ".delete-document", function () {
        const data = datatable.row($(this).closest("tr")).data();

        if (!data.id) {
            Toast.warning("Documento inválido!");
            return;
        }

        Swal.fire({
            title: "Tem certeza que deseja excluir este documento?",
            text: "Esta ação não pode ser desfeita!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: '<i class="bi bi-trash"></i> Sim, excluir',
            cancelButtonText: '<i class="bi bi-x-lg"></i> Não, cancelar',
            customClass: {
                confirmButton: "btn btn-outline-secondary",
                cancelButton: "btn btn-dark",
            },
        }).then((result) => result.isConfirmed && deleteDocument(data.id));
    });
});
