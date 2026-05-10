$(function () {
    const logout = function () {
        $.ajax({
            url: `${BASE_URL}/auth/logout`,
            type: "POST",
            error: function (jqXHR) {
                handleAjaxError(jqXHR, "Falha ao realizar o logout!");
            },
            complete: function (xhr) {
                handleRedirect(xhr);
            },
        });
    };

    $("#logout").click(function () {
        Swal.fire({
            title: "Deseja realmente sair do sistema?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: '<i class="bi bi-box-arrow-right"></i> Sim',
            cancelButtonText: '<i class="bi bi-x-lg"></i> Não',
            customClass: {
                confirmButton: "btn btn-outline-secondary",
                cancelButton: "btn btn-dark",
            },
        }).then((result) => result.isConfirmed && logout());
    });
});
