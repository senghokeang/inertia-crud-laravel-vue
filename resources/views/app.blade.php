<!-- Inertia CRUD - laravelcenter.com -->
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ env('APP_NAME') }}</title>
    <style>
        .required label:after {
            content: " *";
            color: red;
            font-weight: bold;
        }
    </style>
    @vite('resources/js/app.js')
    @inertiaHead
</head>

<body style="display: none;">
    <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-success">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="{{ url('/') }}">Laravel Center</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse"
                aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>
    <main style="margin-top: 100px;">
        @inertia
    </main>
</body>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        document.body.style.display = "block";
    });
</script>

</html>
