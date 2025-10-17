<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta name="description" content="{{ env('APP_DESCRIPTION', 'Default description') }}">
<meta name="keywords" content="{{ env('APP_KEYWORDS', 'default, keywords') }}">
<meta property="og:locale" content="{{ env('OG_LOCALE', 'en_MY') }}">
<meta property="og:title" content="{{ env('OG_TITLE', config('app.name')) }}">
<meta property="og:url" content="{{ env('APP_URL') }}">
<meta property="og:site_name" content="{{ env('OG_SITE_NAME', config('app.name')) }}">
<link rel="canonical" href="{{ env('CANONICAL_URL', env('APP_URL')) }}" />
