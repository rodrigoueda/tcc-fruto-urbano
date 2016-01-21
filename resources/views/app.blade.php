<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
   	<meta name="keywords" content="" />
  	<title>Título</title>

  	<link href="{{ asset('/css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/sweetalert.css') }}" rel="stylesheet">
</head>
	<body>
		@yield('content')
	</body>
		@include('footer')
</html>
