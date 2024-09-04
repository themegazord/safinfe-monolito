<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <title>{{ $title ?? 'Page Title' }}</title>

  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

    * {
      font-family: 'Poppins', sans-serif;
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    ol,
    ul {
      padding-left: 0;
    }

    :root {
      --body-color: #E3E9F7;
      --sidebar-color: #FFF;
      --primary-color: #000511;
      --primary-color-hover: rgb(94, 80, 252);
      --primary-color-light: #F6F5FF;
      --toggle-color: #DDD;
      --text-color: #707070;

      --tran-02: all 0.2s ease;
      --tran-03: all 0.3s ease;
      --tran-04: all 0.4s ease;
      --tran-05: all 0.5s ease;
    }
  </style>
  <link rel="stylesheet" href="{{asset('css/style.css')}}">
</head>

<body>
  <livewire:componentes.utils.navbar.main-nav-bar />
  <livewire:componentes.utils.notificacao.flash />
  {{ $slot }}
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
  <script src="https://kit.fontawesome.com/187a0c0ba5.js" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.11/jquery.mask.min.js"></script>
  <script>
    $(document).ready(function() {
      $('.date').mask('00/00/0000');
      $('.time').mask('00:00:00');
      $('.date_time').mask('00/00/0000 00:00:00');
      $('.cep').mask('00000-000');
      $('.phone').mask('0000-0000');
      $('.cellphone_with_ddd').mask('(00) 00000-0000');
      $('.cnpj').mask('00.000.000/0000-00', {
        reverse: true
      });
      $('.telefone_com_ddd').mask('(00) 0000-0000');
      $('.phone_us').mask('(000) 000-0000');
      $('.mixed').mask('AAA 000-S0S');
      $('.cpf').mask('000.000.000-00', {
        reverse: true
      });
      $('.money').mask('000.000.000.000.000,00', {
        reverse: true
      });
      $('.money2').mask("#.##0,00", {
        reverse: true
      });
      $('.ip_address').mask('0ZZ.0ZZ.0ZZ.0ZZ', {
        translation: {
          'Z': {
            pattern: /[0-9]/,
            optional: true
          }
        }
      });
      $('.ip_address').mask('099.099.099.099');
      $('.percent').mask('##0,00%', {
        reverse: true
      });
      $('.clear-if-not-match').mask("00/00/0000", {
        clearIfNotMatch: true
      });
      $('.placeholder').mask("00/00/0000", {
        placeholder: "__/__/____"
      });
      $('.fallback').mask("00r00r0000", {
        translation: {
          'r': {
            pattern: /[\/]/,
            fallback: '/'
          },
          placeholder: "__/__/____"
        }
      });
      $('.selectonfocus').mask("00/00/0000", {
        selectOnFocus: true
      });
    });
  </script>
</body>

</html>
