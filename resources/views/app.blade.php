<!--

  ______     ___      .______   .___________.    ___       __  .__   __. 
 /      |   /   \     |   _  \  |           |   /   \     |  | |  \ |  | 
|  ,----'  /  ^  \    |  |_)  | `---|  |----`  /  ^  \    |  | |   \|  | 
|  |      /  /_\  \   |   ___/      |  |      /  /_\  \   |  | |  . `  | 
|  `----./  _____  \  |  |          |  |     /  _____  \  |  | |  |\   | 
 \______/__/     \__\ | _|          |__|    /__/     \__\ |__| |__| \__| 
                                                                         
     _______.  ______   ______   .______        ______  __    __         
    /       | /      | /  __  \  |   _  \      /      ||  |  |  |        
   |   (----`|  ,----'|  |  |  | |  |_)  |    |  ,----'|  |__|  |        
    \   \    |  |     |  |  |  | |      /     |  |     |   __   |        
.----)   |   |  `----.|  `--'  | |  |\  \----.|  `----.|  |  |  |        
|_______/     \______| \______/  | _| `._____| \______||__|  |__|        
                                                                         

Designed & developed by captainscor.ch (https://captainscor.ch)

-->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, viewport-fit=cover, initial-scale=1, minimal-ui, maximum-scale=1, user-scalable=no">

        <title inertia>Daniel Schmier</title>

        <meta content="Daniel Schmier" name="author" />
        <meta name="description" content="Personal website and portfolio of Daniel Schmier" />

        <meta name="theme-color" content="#f2f0ef" media="(prefers-color-scheme: light)" id="theme-color-light">
        <meta name="theme-color" content="#040e0c" media="(prefers-color-scheme: dark)" id="theme-color-dark">
        
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link rel="preconnect" href="https://stats.captainscor.ch" crossorigin>
        
        <link rel="manifest" href="/img/favicons/site.webmanifest" />
        <link rel="icon" type="image/png" href="/img/favicons/favicon-96x96.png" sizes="96x96" />
        <link rel="icon" type="image/svg+xml" href="/img/favicons/favicon.svg" />
        <link rel="shortcut icon" href="/img/favicons/favicon.ico" />
        <link rel="apple-touch-icon" sizes="180x180" href="/img/favicons/apple-touch-icon.png" />
        <meta name="apple-mobile-web-app-title" content="Daniel Schmier – Design Engineer" />

        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">

        <!-- Open Graph / Facebook (Meta) -->
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ Request::url() }}">
        <meta inertia property="og:title" content="Daniel Schmier – Design Engineer">
        <meta property="og:description" content="Personal website and portfolio of Daniel Schmier">
        <meta property="og:image" content="{{ asset('img/captainscorch_meta.jpg') }}">

        <!-- Twitter (X) -->
        <meta property="twitter:card" content="summary_large_image">
        <meta property="twitter:url" content="{{ Request::url() }}">
        <meta inertia property="twitter:title" content="Daniel Schmier – Design Engineer">
        <meta property="twitter:description" content="Personal website and portfolio of Daniel Schmier">
        <meta property="twitter:image" content="{{ asset('img/captainscorch_meta.jpg') }}">

        <link href="https://fonts.bunny.net/css?family=work-sans:300,400,500,700" rel="stylesheet" />

        @routes
        @vite(['resources/js/app.ts', "resources/js/pages/{$page['component']}.vue"])
        @inertiaHead
    </head>
    <body>
        <noscript>
            <div class="text-center p-4">
                JavaScript is required to use this app. Please enable JavaScript in your browser.
            </div>
        </noscript>
        @inertia
    </body>
</html>
