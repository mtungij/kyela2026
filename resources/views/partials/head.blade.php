<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

<title>{{ $title ?? config('app.name') }}</title>

<link rel="icon" href="/favicon.ico" sizes="any">
<link rel="icon" href="/favicon.svg" type="image/svg+xml">
<link rel="apple-touch-icon" href="/images/logo-180.png">
<link rel="icon" href="/images/logo-192.png" type="image/png">

<link rel="manifest" href="/manifest.json">
<meta name="theme-color" content="#0e7490">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="default">
<meta name="apple-mobile-web-app-title" content="Kalumbulu">

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

@vite(['resources/css/app.css', 'resources/js/app.js'])
@fluxAppearance
