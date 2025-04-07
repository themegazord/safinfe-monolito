<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <title>{{ $title ?? 'Page Title' }}</title>
</head>

<body>
  <livewire:componentes.utils.navbar.top-nav-bar />
  <div class="bg-gray-950 h-[90vh] flex justify-center items-center">
    {{ $slot }}
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  <script src="https://kit.fontawesome.com/187a0c0ba5.js" crossorigin="anonymous"></script>
</body>

</html>
