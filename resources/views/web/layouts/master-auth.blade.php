<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>{{ isset($title) ? $title : '点餐系统' }}</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="/base/css/bootstrap.min.css?v=3.4.0.css" rel="stylesheet">
    <link href="/base/css/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="/base/css/plugins/datapicker/datepicker3.min.css" rel="stylesheet">
    <link href="/base/css/plugins/datetimepicker/bootstrap-datetimepicker.min.css" rel="stylesheet">
    <link href="/base/css/plugins/jquery-weui/jquery-weui.min.css" rel="stylesheet">
    <link href="/base/css/plugins/jquery-weui/weui.min.css" rel="stylesheet">
    <link href="/base/css/plugins/bootstrap-validator/bootstrapValidator.css" rel="stylesheet">

    <!-- 全局js -->
    <script src="/base/js/jquery-2.1.1.min.js?v={{ config('sys.version') }}" ></script>
    <script src="/base/js/bootstrap.min.js?v=3.4.0" ></script>

    <style type="text/css">
        body, html {
          height: 100%;
          -webkit-tap-highlight-color: transparent;
          background-color: #EEE;
        }
        .ofs-title {
          text-align: center;
          font-size: 34px;
          color: #3cc51f;
          font-weight: 400;
          margin: 0 15%;
        }       

        .ofs-sub-title {
          text-align: center;
          color: #888;
          font-size: 14px;
        }       

        .ofs-header {
          padding: 35px 0;
        }       

        .ofs-content-padded {
          padding: 15px;
        }       

        .ofs-second-title {
          text-align: center;
          font-size: 24px;
          color: #3cc51f;
          font-weight: 400;
          margin: 0 15%;
        }       

        footer {
          text-align: center;
          font-size: 14px;
          padding: 20px;
        }       

        footer a {
          color: #999;
          text-decoration: none;
        }

    </style>
    @yield('header')
</head>

<body>

    <div class="app-content">
        @yield('content')
    </div>

    <script src="/base/js/plugins/datapicker/bootstrap-datepicker.js" ></script>
    <script src="/base/js/plugins/datetimepicker/bootstrap-datetimepicker.min.js" ></script>
    <script src="/base/js/plugins/datetimepicker/locales/bootstrap-datetimepicker.zh-CN.js" ></script>
    <script src="/base/js/plugins/jquery-weui/jquery-weui.min.js" ></script>
    <script src="/base/js/plugins/bootstrap-validator/bootstrapValidator.js" ></script>
    <script src="/js/echarts.min.js"></script>

    @yield('footer')
</body>

</html>