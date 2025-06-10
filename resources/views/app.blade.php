<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

         <!-- Bootstrap css -->
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="assets/css/fontawesome.css">
    <!-- Login css -->
    <link rel="stylesheet" type="text/css" href="assets/css/login.css">


    {{-- all dashboard css --}}
     <!-- Bootstrap -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
    <!-- file upload -->
    <link rel="stylesheet" href="assets/css/file-upload.css" />
    <!-- file upload -->
    <link rel="stylesheet" href="assets/css/plyr.css" />
    <!-- DataTables -->
    <link
      rel="stylesheet"
      href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.min.css"
    />
   
    <!-- jquery Ui -->
    <link rel="stylesheet" href="assets/css/jquery-ui.css" />
    <!-- editor quill Ui -->
    <link rel="stylesheet" href="assets/css/editor-quill.css" />
    <!-- apex charts Css -->
    <link rel="stylesheet" href="assets/css/apexcharts.css" />
    <!-- jvector map Css -->
    <link rel="stylesheet" href="assets/css/jquery-jvectormap-2.0.5.css" />
    <!-- Main css -->
    <link rel="stylesheet" href="assets/css/main.css" />

        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        @inertia



        {{-- javascript dashboard --}}
        <!-- Jquery js -->
    <script src="assets/js/jquery-3.7.1.min.js"></script>
    <!-- Bootstrap Bundle Js -->
    <script src="assets/js/boostrap.bundle.min.js"></script>
    <!-- Phosphor Js -->
    <script src="assets/js/phosphor-icon.js"></script>
    <!-- file upload -->
    <script src="assets/js/file-upload.js"></script>
    <!-- file upload -->
    <script src="assets/js/plyr.js"></script>
    <!-- dataTables -->
    <script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>
   
    <!-- jQuery UI -->
    <script src="assets/js/jquery-ui.js"></script>
    <!-- jQuery UI -->
    <script src="assets/js/editor-quill.js"></script>
    <!-- apex charts -->
    <script src="assets/js/apexcharts.min.js"></script>
   
    <!-- jvectormap Js -->
    <script src="assets/js/jquery-jvectormap-2.0.5.min.js"></script>
    <!-- jvectormap world Js -->
    <script src="assets/js/jquery-jvectormap-world-mill-en.js"></script>

    <!-- main js -->
    <script src="assets/js/main.js"></script>
    </body>
</html>
