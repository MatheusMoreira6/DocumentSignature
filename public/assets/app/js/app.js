(function () {
    window.Toast = {
        success: (message, options = {}) => {
            return Toastify({
                text: message,
                duration: options.duration || 3000,
                gravity: "top",
                position: "right",
                close: true,
                stopOnFocus: true,
                style: {
                    background: "#4caf50",
                },
                ...options,
            }).showToast();
        },

        error: (message, options = {}) => {
            return Toastify({
                text: message,
                duration: options.duration || 4000,
                gravity: "top",
                position: "right",
                close: true,
                stopOnFocus: true,
                style: {
                    background: "#e57373",
                },
                ...options,
            }).showToast();
        },

        info: (message, options = {}) => {
            return Toastify({
                text: message,
                duration: options.duration || 3000,
                gravity: "top",
                position: "right",
                close: true,
                stopOnFocus: true,
                style: {
                    background: "#64b5f6",
                },
                ...options,
            }).showToast();
        },

        warning: (message, options = {}) => {
            return Toastify({
                text: message,
                duration: options.duration || 3500,
                gravity: "top",
                position: "right",
                close: true,
                stopOnFocus: true,
                style: {
                    background: "#ffb74d",
                },
                ...options,
            }).showToast();
        },
    };

    let is_redirecting = false;

    window.handleRedirect = function (xhr) {
        if (is_redirecting) return true;

        const redirect = xhr.getResponseHeader("X-Redirect");

        if (!redirect) return false;

        is_redirecting = true;
        window.location.href = redirect;

        return true;
    };

    window.handleAjaxError = function (jqXHR, fallback = "Ocorreu um erro inesperado.") {
        const errors = jqXHR?.responseJSON?.errors;

        if (!errors) {
            Toast.error(fallback);
            return;
        }

        if (typeof errors === "string") {
            Toast.error(errors);
            return;
        }

        if (Array.isArray(errors)) {
            errors.forEach((error) => Toast.error(error));
            return;
        }

        if (typeof errors === "object") {
            Object.values(errors).forEach((error) => Toast.error(error));
            return;
        }

        Toast.error(fallback);
    };
})();

(function ($) {
    $.fn.loading = function (state, text = "Carregando...") {
        return this.each(function () {
            const $btn = $(this);

            if (state) {
                if ($btn.data("original-html") === undefined) {
                    $btn.data("original-html", $btn.html());
                }

                if (!$btn.prop("disabled")) {
                    $btn.prop("disabled", true).html(
                        `<span class="btn-loading"><span class="spinner"></span>${text}</span>`,
                    );
                }
            } else {
                $btn.prop("disabled", false).html($btn.data("original-html")).removeData("original-html");
            }
        });
    };
})(jQuery);

(function ($) {
    $(function () {
        $(".needs-validation").on("submit", function (e) {
            if (!$(this).get(0).checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
            }

            $(this).addClass("was-validated");
        });

        $(".needs-validation").on("reset", function () {
            $(this).removeClass("was-validated");
        });
    });
})(jQuery);
