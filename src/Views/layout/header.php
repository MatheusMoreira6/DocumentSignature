<!DOCTYPE html>

<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Portal de Assinaturas</title>

        <script>
            const BASE_URL = "<?= BASE_URL ?>";
        </script>

        <script src="<?= asset("vendor/jquery/jquery-4.0.0.min.js") ?>"></script>
        <script src="<?= asset("vendor/bootstrap/bootstrap.bundle.min.js") ?>"></script>
        <script src="<?= asset("vendor/datatables/datatables.min.js") ?>"></script>
        <script src="<?= asset("vendor/toastify/toastify.js") ?>"></script>
        <script src="<?= asset("vendor/sweetalert2/sweetalert2.min.js") ?>"></script>
        <script src="<?= asset("app/js/app.js?v=" . time()) ?>"></script>

        <link rel="stylesheet" href="<?= asset("vendor/bootstrap/bootstrap.min.css") ?>">
        <link rel="stylesheet" href="<?= asset("vendor/bootstrap-icons/bootstrap-icons.min.css") ?>">
        <link rel="stylesheet" href="<?= asset("vendor/datatables/datatables.min.css") ?>">
        <link rel="stylesheet" href="<?= asset("vendor/toastify/toastify.css") ?>">
        <link rel="stylesheet" href="<?= asset("vendor/sweetalert2/sweetalert2.min.css") ?>">
        <link rel="stylesheet" href="<?= asset("app/css/main.css?v=" . time()) ?>">
    </head>

    <body>