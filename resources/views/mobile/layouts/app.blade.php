<!DOCTYPE HTML>
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="apple-mobile-web-app-capable" content="yes"><meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" />
<title>Simethris Mobile</title>
<link href="https://fonts.googleapis.com/css?family=Poppins:200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i|Roboto:300,300i,400,400i,500,500i,700,700i,900,900i&display=swap" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="/mobile/css/style.css">
<link rel="stylesheet" type="text/css" href="/mobile/css/framework.css">
<link rel="stylesheet" type="text/css" href="/mobile/fonts/css/fontawesome-all.min.css">    
<link rel="apple-touch-icon" sizes="180x180" href="/mobile/app/icons/icon-192x192.png">
</head>
    
<body class="theme-light"  data-highlight="blue2">
    @yield('content')        

    <form id="logoutform" action="{{ route('mobile.logout') }}" method="POST" style="display: none;">
        {{ csrf_field() }}
    </form>
<script type="text/javascript" src="/mobile/scripts/jquery.js"></script>
<script type="text/javascript" src="/mobile/scripts/plugins.js"></script>
<script type="text/javascript" src="/mobile/scripts/custom.js"></script>
    @yield('scripts')
</body>
