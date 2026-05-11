$(function () {
    $("#current-password, #new-password").on("input", function () {
        const current_password = $("#current-password").val();
        const new_password = $("#new-password").val();

        const required = current_password.length > 0 || new_password.length > 0;

        $("#current-password, #new-password").prop("required", required);
    });

    const resetPassword = () => {
        $("#current-password, #new-password").val("");
        $("#current-password, #new-password").prop("required", false);
    };

    $("#form-profile").on("submit", function (e) {
        e.preventDefault();

        const $form = $(this);
        const $btn = $(e.originalEvent.submitter);

        if (!$form.get(0).checkValidity()) {
            $form.addClass("was-validated");
            return;
        }

        $form.removeClass("was-validated");

        $.ajax({
            url: `${BASE_URL}/profile`,
            type: "POST",
            data: $form.serialize(),
            dataType: "json",
            beforeSend: () => {
                $btn.loading(true, "Salvando alterações...");
            },
            success: function (data) {
                Toast.success(data.message);
            },
            error: function (jqXHR) {
                handleAjaxError(jqXHR, "Falha ao salvar alterações. Tente novamente mais tarde!");
            },
            complete: function () {
                $btn.loading(false);
                resetPassword();
            },
        });
    });
});
