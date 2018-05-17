<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="renderer" content="webkit">
	<meta name="crm" content="http://{{env('CRM_DOMAIN','kefu.liweijia.com')}}/"/>
    <title>管理后台</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="/base/css/bootstrap.min.css?v=3.4.0.css" rel="stylesheet">
    <link href="/base/css/font-awesome.min.css?v=4.3.0.css" rel="stylesheet">
    <link href="/base/css/plugins/datapicker/datepicker3.css" rel="stylesheet">
    <link href="/base/css/plugins/datetimepicker/bootstrap-datetimepicker.min.css" rel="stylesheet">
    <link href="/base/css/plugins/bootstrap-validator/bootstrapValidator.css" rel="stylesheet">

    <!-- Data Tables -->
    <link href="/base/css/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet">

    <link href="/base/css/animate.min.css?v={{ config('sys.version') }}" rel="stylesheet">
    <link href="/base/css/style.min.css?v={{ config('sys.version') }}" rel="stylesheet">

    <!-- cropperjs -->
    <link rel="stylesheet" href="/base/plugins/cropperjs/cropper.min.css">

    <link rel="stylesheet" href="/base/css/jquery-ui-1.12.1.min.css">
    <link rel="stylesheet" href="/base/select2/select2.min.css">

   	<!-- 全局js -->
    <script src="/base/js/jquery-2.1.1.min.js?v={{ config('sys.version') }}" ></script>
    <script src="/base/js/bootstrap.min.js?v=3.4.0" ></script>
    <script src="/base/js/layer/layer.js?v={{ config('sys.version') }}"></script>

    <style type="text/css">
        .sg-centered {
            text-align: center;
        }
        .dataTable {
            text-align: center;
        }
        .dataTable thead th {
            text-align: center;
        }
        .default-color {
            color: #999999;
        }
        .primary-color {
            color: #418bca;
        }
        .success-color {
            color: #57b658;
        }
        .info-color {
            color: #59c1de;
        }
        .warning-color {
            color: #f2ad50;
        }
        .danger-color {
            color: #d8534c;
        }
        .sg-divider {
            width: 100%;
            height: 2px;
            background-color: rgba(0, 0, 0, 0.1);
            margin: 25px 0;
        }
        .sg-hide {
            display: none;
        }
        .sg-show {
            display: block;
        }
        .sg-divider-bold {
            width: 100%;
            height: 3px;
            background-color: rgba(0, 0, 0, 0.1);
            margin: 20px 0;
        }
        .sg-divider {
            width: 100%;
            height: 2px;
            background-color: rgba(0, 0, 0, 0.1);
            margin: 20px 0;
        }
    </style>

    @yield('header')
</head>

<body class="gray-bg">
    <div class="wrapper wrapper-content animated fadeInRight">
       @yield('content')
    </div>
    
    <!-- layer Date -->
    <script src="/base/js/laydate/laydate.js?v={{ config('sys.version') }}" type="text/javascript" ></script>
    <script type="text/javascript"> !function(){ laydate.skin('molv'); }(); </script>
    
    <!-- 自定义js -->
    <script src="/base/js/content.min.js?v=1.0.0" ></script>

    <script src="/base/js/jquery-ui-1.12.1.min.js"></script>

    <!-- jQuery Validation plugin javascript-->
    <script src="/base/js/plugins/validate/jquery.validate.min.js?v={{ config('sys.version') }}" ></script>
    <script src="/base/js/plugins/validate/validate_custom.js?v={{ config('sys.version') }}" ></script>
    <script src="/base/js/plugins/validate/messages_zh.min.js?v={{ config('sys.version') }}" ></script>
    <script src="/base/js/jquery.tableSort.js?v={{ config('sys.version') }}" ></script>
    <script src="/base/plugins/cropperjs/cropper.min.js"></script>
    
    <script src="/base/js/plugins/datapicker/bootstrap-datepicker.js" ></script>
    <script src="/base/js/plugins/datetimepicker/bootstrap-datetimepicker.js" ></script>
    <script src="/base/js/plugins/datetimepicker/locales/bootstrap-datetimepicker.zh-CN.js" ></script>
    <script src="/base/js/plugins/bootstrap-validator/bootstrapValidator.js" ></script>
    <script src="/base/js/plugins/chartJs/Chart.bundle.js" ></script>
    <script src="/base/js/plugins/chartJs/utils.js" ></script>
    <script src="/base/select2/select2.full.min.js" ></script>
    <script>
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN' : '{{ csrf_token() }}' }
        });
		$.validator.setDefaults({
			highlight : function(a) {
				$(a).closest(".form-group").removeClass(
						"has-success").addClass("has-error")
			},
			success : function(a) {
				a.closest(".form-group").removeClass("has-error")
						.addClass("has-success")
			},
			errorElement : "span",
			errorPlacement : function(a, b) {
				if (b.is(":radio") || b.is(":checkbox")) {
					a.appendTo(b.parent().parent().parent())
				} else {
					a.appendTo(b.parent())
				}
			},
			errorClass : "help-block m-b-none",
			validClass : "help-block m-b-none"
		});
		$(".dataTable").tableSort(['sorting','sorting_asc','sorting_desc']);
		$().ready(function() {
			$("#form-validation").validate();
			
		});
	</script>
	
    @yield('footer')
</body>

</html>