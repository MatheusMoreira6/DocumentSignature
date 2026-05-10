$(function () {
    $("#form-document-upload #signers_0_cpf").mask("999.999.999-99");

    /**
     * Estrutura do card do signatário
     */
    const signature_types = $("#form-document-upload #signers_0_type option").clone();

    const createCard = () => {
        return $("<div>").addClass("card mt-2");
    };

    const createBody = () => {
        return $("<div>").addClass("card-body bg-light");
    };

    const createHeader = () => {
        const $span = $("<span>").addClass("fw-semibold fs-6").text("Signatário");

        const $trash = $("<button>")
            .addClass("btn btn-outline-secondary btn-sm float-end remove-signer")
            .attr("type", "button")
            .html('<i class="bi bi-trash-fill"></i>');

        return $("<div>").addClass("col-12").append($span).append($trash);
    };

    const createRow = () => {
        return $("<div>").addClass("row g-2");
    };

    const createColumn = () => {
        return $("<div>").addClass("col-12 col-lg-6");
    };

    const createLabel = (text, id) => {
        return $("<label>").addClass("form-label fw-semibold").attr("for", id).text(text);
    };

    const createInput = (type = "text", name, id, placeholder, required = false) => {
        return $("<input>")
            .addClass("form-control")
            .attr("type", type)
            .attr("name", name)
            .attr("id", id)
            .attr("placeholder", placeholder)
            .attr("required", required);
    };

    const createSelectTypeSignature = (name, id, required = false) => {
        return $("<select>")
            .addClass("form-select")
            .attr("name", name)
            .attr("id", id)
            .attr("required", required)
            .append(signature_types.clone());
    };

    const createInvalidFeedback = (message) => {
        return $("<div>").addClass("invalid-feedback").text(message);
    };

    let index = 1;

    const createSignerRow = () => {
        const $row = createRow();
        const $header = createHeader();

        $row.append($header);

        let $col = createColumn();

        $col.append(createLabel("Nome Completo", `signers_${index}_name`));
        $col.append(createInput("text", `signers[${index}][name]`, `signers_${index}_name`, "Nome Completo", true));
        $col.append(createInvalidFeedback("Informe o nome do signatário."));

        $row.append($col);

        $col = createColumn();

        const $cpf_input = createInput("text", `signers[${index}][cpf]`, `signers_${index}_cpf`, "CPF", true);
        $cpf_input.mask("999.999.999-99");

        $col.append(createLabel("CPF", `signers_${index}_cpf`));
        $col.append($cpf_input);
        $col.append(createInvalidFeedback("Informe o CPF do signatário."));

        $row.append($col);

        $col = createColumn();

        $col.append(createLabel("E-mail", `signers_${index}_email`));
        $col.append(createInput("email", `signers[${index}][email]`, `signers_${index}_email`, "E-mail", true));
        $col.append(createInvalidFeedback("Informe o e-mail do signatário."));

        $row.append($col);

        $col = createColumn();

        $col.append(createLabel("Tipo de Assinatura", `signers_${index}_type`));
        $col.append(createSelectTypeSignature(`signers[${index}][type]`, `signers_${index}_type`, true));
        $col.append(createInvalidFeedback("Selecione um tipo de assinatura."));

        $row.append($col);

        const $card = createCard();
        const $body = createBody();

        $body.append($row);
        $card.append($body);

        index++;

        return $card;
    };

    $("#btn-add-signer").click(() => {
        $("#form-document-upload").append(createSignerRow());
    });

    $("#form-document-upload").on("click", ".remove-signer", function () {
        $(this).closest("div.card").remove();
    });

    $("#modal-document").on("hidden.bs.modal", function () {
        $("#form-document-upload").trigger("reset");
    });

    /**
     * Envia o documento para assinatura
     */
    $("#form-document-upload").submit(function (e) {
        e.preventDefault();

        const $form = $(this);
        const $btn = $(e.originalEvent.submitter);

        if (!$form.get(0).checkValidity()) {
            $form.addClass("was-validated");
            return;
        }

        $form.removeClass("was-validated");

        $.ajax({
            url: `${BASE_URL}/documents/store`,
            type: "POST",
            data: new FormData($form.get(0)),
            processData: false,
            contentType: false,
            beforeSend: () => {
                $btn.loading(true, "Enviando...");
            },
            success: function (data) {
                Toast.success(data.message);

                reloadDocuments();

                $("#modal-document").modal("hide");
            },
            error: function (jqXHR) {
                handleAjaxError(jqXHR, "Falha ao enviar o documento. Tente novamente!");
            },
            complete: function () {
                $btn.loading(false);
            },
        });
    });
});
