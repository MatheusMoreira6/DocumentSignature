$(function () {
    $("#cpf").mask("999.999.999-99");

    $("#form-register").submit(function (e) {
        e.preventDefault();

        const $form = $(this);
        const $btn = $(e.originalEvent.submitter);

        if (!$form.get(0).checkValidity()) {
            $form.addClass("was-validated");
            return;
        }

        $form.removeClass("was-validated");

        $.ajax({
            url: `${BASE_URL}/auth/register`,
            type: "POST",
            data: $form.serialize(),
            beforeSend: () => {
                $btn.loading(true, "Registrando...");
            },
            error: function (jqXHR) {
                handleAjaxError(jqXHR, "Falha ao realizar o registro. Tente novamente mais tarde!");
            },
            complete: function (xhr) {
                if (handleRedirect(xhr)) return;

                $btn.loading(false);
            },
        });
    });
});
