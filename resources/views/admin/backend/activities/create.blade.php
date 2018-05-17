@extends('admin.layout')

@section('header')
<style type="text/css">
	.datepicker {z-index: 1151 !important;}
</style>
@endsection

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
		<div class="col-sm-12">
			@if(isset($errors) && !$errors->isEmpty())
	        <div class="alert alert-danger alert-dismissable">
	            <button type="button" class="close" data-dismiss="alert"
	                    aria-hidden="true">
	                &times;
	            </button>
	            @foreach($errors->keys() as $key)
	            	{{ $errors->first($key) }}
	            @endforeach
	        </div>
			@endif

			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5>新增优惠活动</h5>
					<div class="ibox-tools">
						<a class="collapse-link"> <i class="fa fa-chevron-up"></i>
						</a>
					</div>
				</div>
				<div class="ibox-content">
					<div class="row">
						<form class="form-horizontal" method="POST" action="{{ U('Backend/Activity/store') }}" id="activity-form">
							{{ csrf_field() }}

							<div class="form-group">
								<label class="control-label col-sm-2 col-sm-offset-1">活动名称</label>
								<div class="col-sm-6">
									<input type="text" name="name" class="form-control" placeholder="50字以内" required>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-2 col-sm-offset-1">指定范围</label>
								<div class="col-sm-6">
									<select class="form-control" name="type">
										<option value="0" selected>通用范围</option>
										<option value="1">男士餐</option>
										<option value="2">女士餐</option>
										<option value="3">工作餐</option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-2 col-sm-offset-1">活动金额</label>
								<div class="col-sm-6">
									<input type="number" name="money" class="form-control" placeholder="请填写大于0的整数">
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-2 col-sm-offset-1">购买次数满</label>
								<div class="col-sm-6">
									<input type="number" name="times" class="form-control" placeholder="请填写大于0的整数">
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-2 col-sm-offset-1">启用状态</label>
								<div class="col-sm-6">
									<label class="radio-inline">
								        <input type="radio" name="status" value="1" checked>是
								    </label>
								    <label class="radio-inline">
								        <input type="radio" name="status" value="0">否
								    </label>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-2 col-sm-offset-1">规则描述</label>
								<div class="col-sm-6">
									<textarea class="form-control" name="description" rows="3" placeholder="300字以内"></textarea>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 col-sm-offset-1 control-label" for="begin_time">开始时间</label>
								<div class="col-sm-6">
									<div class="input-group date" id="begin_time">
									    <input type="text" name="begin_time" class="form-control" value="{{ Carbon\Carbon::now()->toDateString() }}">
									    <div class="input-group-addon">
									        <span class="glyphicon glyphicon-th"></span>
									    </div>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 col-sm-offset-1 control-label" for="end_time">结束时间</label>
								<div class="col-sm-6">
									<div class="input-group date" id="end_time">
									    <input type="text" name="end_time" class="form-control" value="{{ Carbon\Carbon::now()->toDateString() }}">
									    <div class="input-group-addon">
									        <span class="glyphicon glyphicon-th"></span>
									    </div>
									</div>
								</div>
							</div>
							<div class="form-group" id="operations">
								<div class="col-sm-6 col-sm-offset-3">
									<div class="btn btn-sm btn-primary btn-save">保存</div>
									<div class="btn btn-sm btn-success btn-cancel">取消</div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('footer')
<script type="text/javascript">
$(function() {
	$('#operations').on('click', '.btn-save', function() {
		$('#activity-form').submit();
	}).on('click', '.btn-cancel', function() {
		url = "{{ U('Backend/Activity/index') }}";
		window.location = url;
	});

	$('#begin_time').datepicker({
		autoclose: true
	});
	$('#end_time').datepicker({
		autoclose: true
	});

    $('#activity-form').bootstrapValidator({
        message: '填写的值无效！',
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            name: {
                message: '活动名称无效',
                validators: {
                    notEmpty: {
                        message: '活动名称不能为空'
                    },
                    stringLength: {
                        min: 1,
                        max: 50,
                        message: '活动名称在50字以内'
                    },
                }
            },
            money: {
        		message: '活动金额无效！',
        		validators: {
        			notEmpty: {
                        message: '活动金额不能为空！'
                    },
                    greaterThan: {
                        value: 0,
                        inclusive: true,
                        message: '活动金额必须大于0元'
                    }
        		}
        	},
        	money: {
        		message: '购买次数无效！',
        		validators: {
        			notEmpty: {
                        message: '购买次数不能为空！'
                    },
                    greaterThan: {
                        value: 0,
                        inclusive: true,
                        message: '购买次数必须大于0'
                    }
        		}
        	},
            description: {
                message: '规则简介无效',
                validators: {
                    stringLength: {
                        min: 0,
                        max: 300,
                        message: '规则简介在300字以内'
                    },
                }
            }
        }
    });
});
</script>
@endsection