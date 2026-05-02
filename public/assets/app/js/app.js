"use strict";

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
