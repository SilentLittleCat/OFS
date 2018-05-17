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
					<h5>创建商品</h5>
					<div class="ibox-tools">
						<a class="collapse-link"> <i class="fa fa-chevron-up"></i>
						</a>
					</div>
				</div>
				<div class="ibox-content">
					<div class="row">
						<form class="form-horizontal" method="POST" action="{{ U('Backend/Score/store') }}" id="good-form">
							{{ csrf_field() }}

							<div class="form-group">
								<label class="control-label col-sm-2 col-sm-offset-1">选择类别</label>
								<div class="col-sm-6">
								    <label class="radio-inline">
								        <input type="radio" name="type" value="0" checked id="item-radio">实物
								    </label>
								    <label class="radio-inline">
								        <input type="radio" name="type" value="1" id="coupon-radio">优惠券
								    </label>
								</div>
							</div>
							<div class="form-group sg-hide" id="coupon-group">
								<label class="control-label col-sm-2 col-sm-offset-1">优惠券</label>
								<div class="col-sm-6">
								    <select class="form-control" name="coupon_id">
								    	@if($coupons->count() == 0)
								    		<option value="empty">暂无可使用的优惠券</option>
								    	@else
								    		@foreach($coupons as $coupon)
								    			<option value="{{ $coupon->id }}">{{ $coupon->name }}</option>
								    		@endforeach
								    	@endif
								    </select>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-2 col-sm-offset-1">商品名</label>
								<div class="col-sm-6">
									<input type="text" name="name" class="form-control" required>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-2 col-sm-offset-1">图片</label>
								<div class="col-sm-6">
									{!!  widget('Tools.ImgUpload')->single2('/upload/user','poster',"poster", isset($data['poster'])? $data['poster'] : "", ['class' => '积分商城']) !!}
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-2 col-sm-offset-1">兑换积分</label>
								<div class="col-sm-6">
									<input type="number" name="score" class="form-control" placeholder="请填写整数">
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-2 col-sm-offset-1">商品简介</label>
								<div class="col-sm-6">
									<textarea class="form-control" name="info" rows="3"></textarea>
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
		$('#good-form').submit();
	}).on('click', '.btn-cancel', function() {
		url = "{{ U('Backend/Good/index') }}";
		window.location = url;
	});

	$('#item-radio').on('click', function() {
		$('#coupon-group').addClass('sg-hide');
	});
	$('#coupon-radio').on('click', function() {
		$('#coupon-group').removeClass('sg-hide');
	});

    $('#good-form').bootstrapValidator({
        message: '填写的值无效！',
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            name: {
                message: '商品名无效',
                validators: {
                    notEmpty: {
                        message: '商品名不能为空'
                    },
                    stringLength: {
                        min: 1,
                        max: 50,
                        message: '商品名在50字以内'
                    },
                }
            },
            info: {
                message: '商品简介无效',
                validators: {
                    stringLength: {
                        min: 0,
                        max: 300,
                        message: '商品简介在300字以内'
                    },
                }
            }
        }
    });
});
</script>
@endsection