@extends('admin.layout')

@section('header')
<style type="text/css">
	.user-info-item {
		margin-top: 15px;
		margin-bottom: 15px;
	}
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
					<h5>用户详情</h5>
					<div class="ibox-tools">
						<a class="collapse-link"> <i class="fa fa-chevron-up"></i>
						</a>
					</div>
				</div>
				<div class="ibox-content">
					<div class="row">
						<div class="col-sm-3 pull-right">
							<div class="btn btn-sm btn-success pull-right" id="btn-edit-info" data-id="{{ $user->id }}">编辑用户信息</div>
						</div>
					</div>
					<div class="user-info">
						<div class="row user-info-item">
							<div class="col-sm-2">用户ID</div>
							<div class="col-sm-10">{{ $user->id }}</div>
						</div>
						<div class="row user-info-item">
							<div class="col-sm-2">用户微信</div>
							<div class="col-sm-10">{{ $user->wechat_id }}</div>
						</div>
						<div class="row user-info-item">
							<div class="col-sm-2">微信昵称</div>
							<div class="col-sm-10">{{ $user->wechat_name }}</div>
						</div>
						<div class="row user-info-item">
							<div class="col-sm-2">电话</div>
							<div class="col-sm-10">{{ $user->tel }}</div>
						</div>
						<div class="row user-info-item">
							<div class="col-sm-2">真实姓名</div>
							<div class="col-sm-10">{{ $user->real_name }}</div>
						</div>
						<div class="row user-info-item">
							<div class="col-sm-2">性别</div>
							<div class="col-sm-10">{{ $user->gender == 1 ? '男' : '女' }}</div>
						</div>
						<div class="row user-info-item">
							<div class="col-sm-2">收货地址</div>
							<div class="col-sm-10">{{ $user->address }}</div>
						</div>
						<div class="row user-info-item">
							<div class="col-sm-2">生日</div>
							<div class="col-sm-10">{{ $user->birthday }}</div>
						</div>
						<div class="row user-info-item">
							<div class="col-sm-2">身高</div>
							<div class="col-sm-10">{{ $user->height }}</div>
						</div>
						<div class="row user-info-item">
							<div class="col-sm-2">体重</div>
							<div class="col-sm-10">{{ $user->weight }}</div>
						</div>
						<div class="row user-info-item">
							<div class="col-sm-2">账户余额</div>
							<div class="col-sm-10">{{ $user->remain_money . '元' }}</div>
						</div>
						<div class="row user-info-item">
							<div class="col-sm-2">剩余总次数</div>
							<div class="col-sm-10">{{ $user->man_remain_times + $user->woman_remain_times + $user->work_remain_times . '次' }}</div>
						</div>
						<div class="row user-info-item">
							<div class="col-sm-2">个人积分</div>
							<div class="col-sm-10">{{ $user->score . '分' }}</div>
						</div>
						<div class="row user-info-item">
							<div class="col-sm-2">推荐有礼</div>
							<div class="col-sm-10">{{ $user->recommend == 0 ? '未开通' : $recommend->name }}</div>
						</div>
						@if($user->recommend != 0)
							<div class="row user-info-item">
								<div class="col-sm-2">获得奖金</div>
								<div class="col-sm-10">{{ $user->total_pay . '元' }}</div>
							</div>
						@endif
						<div class="row user-info-item">
							<div class="col-sm-2">注册时间</div>
							<div class="col-sm-10">{{ $user->created_at }}</div>
						</div>
					</div>
					<div class="remain_times">
						<div class="row user-info-item">
							<div class="col-sm-2">
								<h4>购买餐类情况</h4>
							</div>
						</div>
						<table class="table table-striped table-bordered table-hover dataTable" id="food-info-table">
							<thead>
								<tr>
									<th>餐名</th>
									<th>购买次数</th>
									<th>剩余次数</th>
									<th>修改次数</th>
									<th>操作</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>男士餐</td>
									<td>{{ $user->man_times }}</td>
									<td>{{ $user->man_remain_times }}</td>
									<td>{{ $user->man_amend_times }}</td>
									<td>
										<div class="btn-group">
											<div class="btn btn-sm btn-success btn-change-times" data-type="男士餐" data-remain-times="{{ $user->man_remain_times }}">更换次数</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>女士餐</td>
									<td>{{ $user->woman_times }}</td>
									<td>{{ $user->woman_remain_times }}</td>
									<td>{{ $user->woman_amend_times }}</td>
									<td>
										<div class="btn-group">
											<div class="btn btn-sm btn-success btn-change-times" data-type="女士餐" data-remain-times="{{ $user->woman_remain_times }}">更换次数</div>
										</div>
									</td>
								</tr>
								<tr>
									<td>工作餐</td>
									<td>{{ $user->work_times }}</td>
									<td>{{ $user->work_remain_times }}</td>
									<td>{{ $user->work_amend_times }}</td>
									<td>
										<div class="btn-group">
											<div class="btn btn-sm btn-success btn-change-times" data-type="工作餐" data-remain-times="{{ $user->work_remain_times }}">更换次数</div>
										</div>
									</td>
								</tr>
							</tbody>
						</table>
						<div class="row user-info-item">
							<div class="col-sm-2">
								<div class="btn btn-sm btn-primary" id="btn-amend-record">更换记录</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="change-times-modal" tabindex="-1" role="dialog" aria-labelledby="change-times-modal-label" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="change-times-modal-label">
					更换次数
				</h4>
			</div>
			<div class="modal-body">
				<form class="form-horizontal" id="change-times-form" method="POST" action="{{ U('Backend/User/changeTimes') }}">

					<div class="form-group">
						{{ csrf_field() }}
						<input type="text" name="id" value="{{ $user->id }}" style="display: none">
						<input type="text" name="food-type" id="food-type" style="display: none">
					</div>
					<div class="form-group">
						<label class="col-sm-2">餐类</label>
						<div class="col-sm-10 food-type"></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2">剩余次数</label>
						<div class="col-sm-10 food-remain"></div>
					</div>
					<div class="form-group">
						<label class="col-sm-2">修改类型</label>
						<div class="col-sm-10" id="amend-type">
						    <label class="radio-inline">
						        <input type="radio" name="type" value="add" checked>增加
						    </label>
						    <label class="radio-inline">
						        <input type="radio" name="type" value="minus">减少
						    </label>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2">修改次数</label>
						<div class="col-sm-10">
							<input class="form-control" type="number" name="change_times" id="change-times" required>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2">账户余额</label>
						<div class="col-sm-10">
							<span class="danger-color">{{ $user->remain_money . '元' }}</span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2">应扣金额</label>
						<div class="col-sm-10 back-money" id="back-money"></div>
						<input type="text" name="back_money_input" id="back-money-input" style="display: none">
					</div>
					<div class="form-group">
						<label class="col-sm-2">是否扣除</label>
						<div class="col-sm-10" id="is-minus">
						    <label class="radio-inline">
						        <input type="radio" name="is_minus" value="1" checked>是
						    </label>
						    <label class="radio-inline">
						        <input type="radio" name="is_minus" value="0">否
						    </label>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2">修改原因</label>
						<div class="col-sm-10">
							<textarea class="form-control" name="reason" placeholder="不超过200字" rows="3"></textarea>
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-4">
							<div class="btn btn-warning" id="compute-money">计算</div>
							<button type="submit" class="btn btn-success" id="change-times-confirm-btn">
								确认
							</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
@endsection

@section('footer')
<script type="text/javascript">
$(function() {

	function updateBackMoney()
	{
		type = $('#amend-type input:checked').val();
		if(type == 'add') {
			num = parseInt($('#change-times').val());
			man_price = parseFloat("{{ $man_price }}").toFixed(2);
			woman_price = parseFloat("{{ $woman_price }}").toFixed(2);
			work_price = parseFloat("{{ $work_price }}").toFixed(2);
			food_type = $('#food-type').val();
			price = 0;
			if(food_type == '男士餐') {
				price = man_price;
			} else if(food_type == '女士餐') {
				price = woman_price;
			} else if(food_type == '工作餐') {
				price = work_price;
			}
			if(isNaN(num)) {
				num = 0;
			}
			// console.log(num);
			money = num * price;
			html = "<span class='danger-color'>" + money + "元</span>";
			$('#back-money').html(html);
			$('#back-money-input').val(money);
			$('#is-minus input[value="1"]').prop('checked', true);
		} else {
			$('#back-money').empty();
			$('#back-money-input').val('');
			$('#is-minus input[value="0"]').prop('checked', true);
		}
	}

	$('#btn-edit-info').on('click', function() {
		url = "{{ U('Backend/User/edit') }}" + '?id=' + $(this).attr('data-id');
		window.location = url;
	});


	$('#btn-amend-record').on('click', function() {
		url = "{{ U('Backend/Order/amendRecord') }}" + '?id=' + "{{ $user->id }}";
		window.location = url;
	});

	$('#food-info-table').on('click', '.btn-change-times', function() {
		$('#change-times-modal .food-type').text($(this).attr('data-type'));
		$('#food-type').val($(this).attr('data-type'));
		$('#change-times-modal .food-remain').text($(this).attr('data-remain-times'));
		$('#change-times-modal').modal('show');
	});

	$('#change-times').blur(function() {
		updateBackMoney();
	});

	$('#amend-type').on('click', function() {
		updateBackMoney();
	});

	$('#compute-money').on('click', function() {
		updateBackMoney();
	});

    $('#change-times-form').bootstrapValidator({
        message: '填写的值无效！',
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            change_times: {
                message: '填写的值无效！',
                validators: {
                    notEmpty: {
                        message: '更改次数不能为空！',

                    },
                    callback: {
                        message: '减少次数不能大于剩余次数，0为无效值！',
                        callback: function(value, validator) {
                            var remain_times = parseInt($('#change-times').val());

                            value = parseInt(value);
                            if(value == 0) {
                            	return false;
                            } else if(value < 0) {
                            	if(Math.abs(value) > remain_times) {
                            		return false;
                            	}
                            }

                            return true;
                        }
                    }
                }
            }
        }
    });
});
</script>
@endsection