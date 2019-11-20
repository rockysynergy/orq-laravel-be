<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{asset('vendor/OrqStarterlayui/css/layui.css')}}">
    <link rel="stylesheet" href="{{asset('vendor/OrqStartercss/admin/style.css')}}">
    @yield('head')
</head>
<body>

    @yield('body')


    <script src="{{asset('vendor/OrqStarter/js/jquery-3.4.1.min.js')}}"></script>
    <script src="{{asset('vendor/OrqStarter/layui/layui.js')}}"></script>
    <script>
        layui.use('element', function(){
          var element = layui.element;
          element.on('nav(test)', function(elem) {
          });

        });
    </script>
    @stack('body-scripts')
</body>
</html>
