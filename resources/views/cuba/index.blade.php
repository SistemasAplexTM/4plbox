<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-6jHF7Z3XI3fF4XZixAuSu0gGKrXwoX/w3uFPxC56OtjChio7wtTGJWRW53Nhx6Ev" crossorigin="anonymous">
    <title>Self Service</title>
  </head>
  <body>
    <div id="cuba">
      <div style="text-align: center">
        <img src="{{ asset('storage/' . $agency->logo) }}" alt="" height="60" style="margin: 0 auto">
      </div>
      <cuba-component></cuba-component>
    </div>
    <script src="{{ asset('js/appCuba.js') }}"></script>
    <script type="text/javascript">
    var objVue = new Vue({
        el: '#cuba',
        data:{

        },
        methods:{

        }
    });

    </script>
  </body>
</html>
