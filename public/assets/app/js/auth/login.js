$(function () {
    $("#form-login").submit(function (e) {
        e.preventDefault();

        const $form = $(this);
        const $btn = $(e.originalEvent.submitter);

        if (!$form.get(0).checkValidity()) {
            $form.addClass("was-validated");
            return;
        }

        $form.removeClass("was-validated");

        $.ajax({
            url: `${BASE_URL}/auth/login`,
            type: "POST",
            data: $form.serialize(),
            beforeSend: () => {
                $btn.loading(true, "Entrando...");
            },
            error: function (jqXHR) {
                handleAjaxError(jqXHR, "Falha ao realizar o login. Tente novamente mais tarde!");
            },
            complete: function (xhr) {
                if (handleRedirect(xhr)) return;

                $btn.loading(false);
            },
        });
    });
});
