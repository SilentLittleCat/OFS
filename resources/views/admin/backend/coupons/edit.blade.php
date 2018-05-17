@extends('admin.layout')

@section('header')
<style type="text/css">
	
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
					<h5>优惠券编辑&nbsp;&nbsp;&nbsp;<span>优惠券ID：{{ $coupon->id }}</span></h5>
					<div class="ibox-tools">
						<a class="collapse-link"> <i class="fa fa-chevron-up"></i>
						</a>
					</div>
				</div>
				<div class="ibox-content">
					<form class="form-horizontal" role="form" id="coupon-form" action="{{ U('Backend/Coupon/update', ['id' => $coupon->id]) }}" method="POST">
						{{ csrf_field() }}

						<div class="form-group">
							<label class="col-sm-2 col-sm-offset-2 control-label" for="name">优惠券名称</label>
							<div class="col-sm-6">
								<input type="text" name="name" class="form-control" value="{{ $coupon->name }}" required>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 col-sm-offset-2 control-label" for="type">指定范围</label>
							<div class="col-sm-6">
								<select class="form-control" name="type">
									<option value="1" {{ $coupon->type == 1 ? 'selected' : '' }}>通用范围</option>
									<option value="2" {{ $coupon->type == 2 ? 'selected' : '' }}>男士餐</option>
									<option value="3" {{ $coupon->type == 3 ? 'selected' : '' }}>女士餐</option>
									<option value="4" {{ $coupon->type == 4 ? 'selected' : '' }}>工作餐</option>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 col-sm-offset-2 control-label" for="condition">订单金额满</label>
							<div class="col-sm-6">
								<input type="number" name="condition" class="form-control" value="{{ $coupon->condition }}" required>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 col-sm-offset-2 control-label" for="money">优惠金额</label>
							<div class="col-sm-6">
								<input type="number" name="money" class="form-control" value="{{ $coupon->money }}" required>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 col-sm-offset-2 control-label" for="status">启用状态</label>
							<div class="col-sm-6">
								<label class="radio-inline">
									<input type="radio" name="status" value="1" {{ $coupon->status == 1 ? 'checked' : '' }}>是
								</label>
								<label class="radio-inline">
									<input type="radio" name="status" value="0" {{ $coupon->status == 0 ? 'checked' : '' }}>否
								</label>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 col-sm-offset-2 control-label" for="total">发放总量</label>
							<div class="col-sm-6">
								<input type="number" name="total" class="form-control" value="{{ $coupon->total }}" required>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 col-sm-offset-2 control-label" for="description">规则描述</label>
							<div class="col-sm-6">
								<textarea class="form-control" rows="5" name="description" required>{{ $coupon->description }}</textarea>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 col-sm-offset-2 control-label" for="begin_time">开始时间</label>
							<div class="col-sm-6">
								<div class="input-group date" id="begin_time">
								    <input type="text" name="begin_time" class="form-control" value="{{ $coupon->begin_time }}">
								    <div class="input-group-addon">
								        <span class="glyphicon glyphicon-th"></span>
								    </div>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 col-sm-offset-2 control-label" for="end_time">结束时间</label>
							<div class="col-sm-6">
								<div class="input-group date" id="end_time">
								    <input type="text" name="end_time" class="form-control" value="{{ $coupon->end_time }}">
								    <div class="input-group-addon">
								        <span class="glyphicon glyphicon-th"></span>
								    </div>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-6 col-sm-offset-6">
								<div class="btn btn-default btn-reset">重置</div>
								<div class="btn btn-primary btn-save">保存</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('footer')
<script type="text/javascript">
$(function() {
	$('#begin_time').datepicker({
		autoclose: true
	});
	$('#end_time').datepicker({
		autoclose: true
	});

	$('#coupon-form').on('click', '.btn-reset', function() {
		window.location = "{{ U('Backend/Coupon/edit') . '?id=' . $coupon->id }}";
	}).on('click', '.btn-save', function() {
		$('#coupon-form').submit();
	});

	$('#coupon-form').bootstrapValidator({
        message: 'This value is not valid',
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
        	name: {
        		message: '优惠券名称无效！',
        		validators: {
        			notEmpty: {
                        message: '优惠券名称不能为空！'
                    },
        			stringLength: {
        				min: 1,
                        max: 100,
                        message: '优惠券名称最长不超过100个字符！'
        			}
        		}
        	},
        	condition: {
        		message: '订单金额满无效！',
        		validators: {
        			notEmpty: {
                        message: '订单金额满不能为空！'
                    },
                    greaterThan: {
                        value: 0,
                        inclusive: true,
                        message: '订单金额必须大于0元！'
                    }
        		}
        	},
        	money: {
        		message: '优惠金额无效！',
        		validators: {
        			notEmpty: {
                        message: '优惠金额不能为空！'
                    },
                    greaterThan: {
                        value: 0,
                        inclusive: true,
                        message: '优惠金额必须大于0元'
                    }
        		}
        	},
        	total: {
        		message: '发放总量无效！',
        		validators: {
        			notEmpty: {
                        message: '发放总量不能为空！'
                    },
                    greaterThan: {
                        value: 0,
                        inclusive: true,
                        message: '发放总量必须大于0！'
                    }
        		}
        	},
            description: {
                message: '规则描述无效！',
                validators: {
                    notEmpty: {
                        message: '规则描述不能为空！'
                    },
                    stringLength: {
                        min: 1,
                        max: 300,
                        message: '规则描述不能超过400个字符！'
                    }
                }
            },
        }
	});
});
</script>
@endsection