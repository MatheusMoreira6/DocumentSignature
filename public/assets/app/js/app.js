$(function () {
    "use strict";

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
