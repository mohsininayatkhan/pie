<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        
        <meta property="og:image" name="image" content="@yield('image')"/>
        <meta property="og:title" name="title" content="@yield('title')"/>
        <meta property="og:url" name="url"  content="@yield('url')"/>
        <meta property="og:description" name="description" content="@yield('description')"/>
        <script src="https://apis.google.com/js/platform.js" async defer></script>
        <script>
            var base_url = '<?php echo URL::to('/'); ?>';
        </script>

        <title>eshtihar - @yield('page_title')</title>

        @include('assets.css')

        @include('assets.js_scripts')

        @yield('additional_scripts')
    </head>

    <body>
        @yield('content')
    </body>
</html>