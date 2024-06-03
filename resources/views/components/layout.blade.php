<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"><head>
	<meta charset="utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<title>{{ $title ?? __( 'Админ турбазы' ) }}</title>
	<link rel="stylesheet" href="{{ asset( 'css/bootstrap.css' ) }}"/>
	{{ $styles ?? '' }}
	<script src="{{ asset( 'js/bootstrap.js' ) }}"></script>
	{{ $scripts ?? '' }}
</head><body>
{{ $slot }}
</body></html>