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
					<h5>编辑用户信息</h5>
					<div class="ibox-tools">
						<a class="collapse-link"> <i class="fa fa-chevron-up"></i>
						</a>
					</div>
				</div>
				<div class="ibox-content">
					<form class="form-horizontal" id="user-form" action="{{ U('Backend/User/update', ['id' => $user->id]) }}" method="POST">
						{{ csrf_field() }}

						<div class="form-group">
							<label class="col-sm-2 col-sm-offset-1 control-label">微信</label>
							<div class="col-sm-6">
								<div class="form-control">
									{{ $user->wechat_name }}
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 col-sm-offset-1 control-label">姓名</label>
							<div class="col-sm-6">
								<input type="text" name="real_name" class="form-control" value="{{ $user->real_name }}">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 col-sm-offset-1 control-label">性别</label>
							<div class="col-sm-6">
								<label class="radio-inline">
									@if($user->gender == 1)
										<input type="radio" value="1" name="gender" checked>男
									@else
										<input type="radio" value="1" name="gender">男
									@endif
								</label>
								<label class="radio-inline">
									@if($user->gender == 2)
										<input type="radio" value="2" name="gender" checked>女
									@else
										<input type="radio" value="2" name="gender">女
									@endif
								</label>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 col-sm-offset-1 control-label">生日</label>
							<div class="col-sm-6">
								<div class="input-group date" id="birthday-input">
								    <input type="text" class="form-control" name="birthday" value="{{ $user->birthday }}">
								    <div class="input-group-addon">
								        <span class="glyphicon glyphicon-th"></span>
								    </div>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 col-sm-offset-1 control-label">体重</label>
							<div class="col-sm-6">
								<input type="text" name="weight" class="form-control" value="{{ $user->weight }}">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 col-sm-offset-1 control-label">身高</label>
							<div class="col-sm-6">
								<input type="text" name="height" class="form-control" value="{{ $user->height }}">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 col-sm-offset-1 control-label">手机号</label>
							<div class="col-sm-6">
								<input type="text" name="tel" class="form-control" value="{{ $user->tel }}">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 col-sm-offset-1 control-label">收货地址</label>
							<div class="col-sm-6">
								<select name="address" class="form-control" id="address-select">
									@foreach($addresses as $address)
										<option value="{{ $address->address }}" {{ $address->address == $user->address ? 'selected' : '' }}>{{ $address->address }}</option>
									@endforeach
									<option value="-1">新增地址</option>
								</select>
							</div>
						</div>
						<div class="add-new-address {{ $addresses->count() == 0 ? '' : 'sg-hide' }}">
							<div class="form-group">
								<label class="col-sm-2 col-sm-offset-1 control-label">新增地址</label>
				                <div class="col-xs-2 col-sm-2">
				                    <select class="form-control" id="cmbProvince" name="place_province_id" style="padding-right: 0px"></select>
				                </div>
				                <div class="col-xs-2 col-sm-2">
				                    <select class="form-control" id="cmbCity" name="place_city_id" style="padding-right: 0px"></select>
				                </div>
				                <div class="col-xs-2 col-sm-2">
				                    <select class="form-control" id="cmbArea" name="" style="padding-right: 0px"></select>
				                </div>
				                <script type="text/javascript" src="/base/js/areadata.min.js"></script>
				                <script type="text/javascript">
				                    areadata({_cmbProvince:'cmbProvince',//省
				                        _cmbCity:'cmbCity',//市
				                        _cmbArea:'cmbArea',//县
				                        _infoname:'place_area_id',
				                        _default:"{{ $data['place_area_id'] or '510104' }}"//默认县
				                    });
				                </script>
							</div>
							<div class="form-group">
								<div class="col-xs-6 col-sm-6 col-sm-offset-3">
									<input type="text" class="form-control input-sm" placeholder="详细地址" name="detail_address" id="detail-address">
									<input type="text" class="form-control input-sm sg-hide" placeholder="详细地址" name="full_address" id="full-address">
								</div>
							</div>
						</div>
						<div class="form-group" id="btn-operations">
							<div class="col-sm-4 col-sm-offset-4">
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
	$('#birthday-input').datepicker({
		autoclose: true
	});

	$('#address-select').change(function() {
		select = $(this).val();
		if(select == -1) {
			$('.add-new-address').removeClass('sg-hide').addClass('sg-show');
		} else {
			$('.add-new-address').removeClass('sg-show').addClass('sg-hide');
		}
		console.log($('#full_address').val());
	});

	$('#btn-operations').on('click', '.btn-reset', function() {
		window.location = "{{ U('Backend/User/edit') . '?id=' . $user->id }}";
	}).on('click', '.btn-save', function() {
		if($('#address-select').val() == '-1') {
			province = $('#cmbProvince option:selected').text();
			city = $('#cmbCity option:selected').text();
			area = $('#cmbArea option:selected').text();
			full_address = province + ' ' + city + ' ' + area + ' ' + $('#detail-address').val();
			$('#full-address').val(full_address);
		}
		$('#user-form').submit();
	});

    $('#user-form').bootstrapValidator({
        message: '填写的值无效！',
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            real_name: {
                message: '填写的姓名无效！',
                validators: {
                    notEmpty: {
                        message: '填写的姓名不能为空！'
                    },
                    stringLength: {
                        min: 1,
                        max: 50,
                        message: '填写的姓名不能超过50个字符！'
                    },
                }
            },
            weight: {
            	message: '填写的体重无效！',
            	validators: {
            		stringLength: {
            			min: 0,
            			max: 30,
            			message: '体重应在30字以内！',
            		}
            	}
            },
            weight: {
            	message: '填写的身高无效！',
            	validators: {
            		stringLength: {
            			min: 0,
            			max: 30,
            			message: '身高应在30字以内！',
            		}
            	}
            },
            tel: {
            	message: '填写的手机号无效！',
            	validators: {
            		notEmpty: {
                        message: '手机号不能为空！'
                    },
            		stringLength: {
            			min: 0,
            			max: 30,
            			message: '手机号应在30字以内！',
            		}
            	}
            },
            address: {
            	message: '填写的地址无效！',
            	validators: {
            		notEmpty: {
                        message: '地址不能为空！'
                    },
            		stringLength: {
            			min: 0,
            			max: 100,
            			message: '地址应在100字以内！',
            		}
            	}
            }
        }
    });
});
</script>
@endsection