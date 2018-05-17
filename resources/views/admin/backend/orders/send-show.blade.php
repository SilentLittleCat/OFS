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
					<h5>订单详情</h5>
					<div class="ibox-tools">
						<a class="collapse-link"> <i class="fa fa-chevron-up"></i>
						</a>
					</div>
				</div>
				<div class="ibox-content">
					<div class="row">
						<div class="col-sm-12">
							<div class="btn btn-sm btn-info pull-right" id="send-change-info">订单更改记录</div>
						</div>
					</div>
                    <table class="table table-striped table-bordered table-hover dataTable" id="order-info-table">
                        <thead>
                            <tr>
                            	<th>订单号</th>
                                <th>预定产品</th>
                                <th>订单金额</th>
                                <th>订单状态</th>
                                <th>下单人</th>
                                <th>下单人手机</th>
                                <th>下单时间</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                            	<td>{{ sprintf("%013d", $order->id) }}</td>
                                <td id="food-info-detail">
                                	@if($order->type == 1)
                                   		{{ '男士餐：' . $order->num . '份' }}
                                   	@endif
                                   	@if($order->type == 2)
                                   		{{ '女士餐：' . $order->num . '份' }}
                                   	@endif
                                   	@if($order->type == 3)
                                   		{{ '工作餐：' . $order->num . '份' }}
                                   	@endif
                                </td>
                                <td>{{ $order->money }}</td>
                                @if($order->pay_status == 0)
									<td class="danger-color">未支付</td>
                                @elseif($order->status == 0)
                                    <td class="warning-color">进行中</td>
                                @else
                                    <td class="success-color">已完成</td>
                                @endif
                                <td>{{ $order->wechat_name }}</td>
                                <td>{{ $order->tel }}</td>
                                <td>{{ $order->created_at }}</td>
                                <td>
                                    <div class="btn-group">
                                    	<div class="btn btn-sm btn-danger" id="back-all-money" data-id="{{ $order->id }}" data-type="{{ $order->type }}" data-num="{{ $order->num }}" data-money="{{ $order->money }}" data-back-money="{{ $order->back_money }}">全部退款</div>
									</div>
                            	</td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="sg-divider"></div>
                    <table class="table table-striped table-bordered table-hover dataTable" id="order-send-table">
                        <thead>
                            <tr>
                            	<th>配送编号</th>
                                <th>商品</th>
                                <th>配送时间</th>
                                <th>配送地址</th>
                                <th>状态</th>
                                <th>备注</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($records) == 0)
                                <tr>
                                    <td colspan="7" class="sg-centered">暂无配送记录！</td>
                                </tr>
                            @else
                                @foreach($records as $record)
                                    <tr>
                                    	<td>{{ $record->id }}</td>
                                    	@if($record->type == 1)
                                    		<td>{{ '男士餐' . $record->num . '份' }}</td>
                                    	@elseif($record->type == 2)
                                    		<td>{{ '女士餐' . $record->num . '份' }}</td>
                                    	@else
                                    		<td>{{ '工作餐' . $record->num . '份' }}</td>
                                    	@endif
                                        <td>{{ $record->time }}</td>
                                        <td>{{ $record->address }}</td>
                                        @if($record->status == 0)
                                        	<td>未配送</td>
                                        @elseif($record->status == 1)
                                        	<td style="color: #f2ad50">配送中</td>
                                        @elseif($record->status == 2)
                                        	<td style="color: #57b658">已完成</td>
                                        @elseif($record->status == 3)
                                        	<td style="color: #d8534c">已取消</td>
                                        @else
                                        	<td style="color: #59c1de">已退款</td>
                                        @endif
                                        <td>{{ $record->remarks }}</td>
                                        <td>
                                        	<div class="btn-group">
                                        		@if($record->status == 0)
	                                        		<!-- <div class="btn btn-sm btn-default change-food" data-type="{{ $record->type }}" data-id="{{ $record->id }}" data-num="{{ $record->num }}">更换配餐</div> -->
	                                        		<div class="btn btn-sm btn-primary change-address" data-id="{{ $record->id }}" data-address="{{ $record->address }}" data-type="{{ $record->type }}" data-num="{{ $record->num }}">更换地址</div>
	                                        		<div class="btn btn-sm btn-info delay-time" data-id="{{ $record->id }}" data-time="{{ $record->time }}" data-type="{{ $record->type }}" data-num="{{ $record->num }}">延时配送</div>
	                                        		<div class="btn btn-sm btn-success cancel-send" data-id="{{ $record->id }}" data-type="{{ $record->type }}" data-num="{{ $record->num }}">取消配送</div>
	                                        		<!-- <div class="btn btn-sm btn-warning sending" data-id="{{ $record->id }}">配送中</div>
	                                        		<div class="btn btn-sm btn-primary send-complete" data-id="{{ $record->id }}" data-num="{{ $record->num }}">配送完成</div> -->
	                                        		<div class="btn btn-sm btn-danger back-money" data-id="{{ $record->id }}" data-type="{{ $record->time }}" data-num="{{ $record->num }}" data-back-money="{{ $record->back_money }}">退款</div>
	                                        	@else
													<!-- <div class="btn btn-sm btn-default change-food" data-type="{{ $record->type }}" data-id="{{ $record->id }}" data-num="{{ $record->num }}">更换配餐</div> -->
	                                        		<div class="btn btn-sm btn-primary change-address disabled" data-id="{{ $record->id }}" data-address="{{ $record->address }}" data-type="{{ $record->type }}" data-num="{{ $record->num }}">更换地址</div>
	                                        		<div class="btn btn-sm btn-info delay-time disabled" data-id="{{ $record->id }}" data-time="{{ $record->time }}" data-type="{{ $record->type }}" data-num="{{ $record->num }}">延时配送</div>
	                                        		<div class="btn btn-sm btn-success cancel-send disabled" data-id="{{ $record->id }}" data-type="{{ $record->type }}" data-num="{{ $record->num }}">取消配送</div>
	                                        		<!-- <div class="btn btn-sm btn-warning sending" data-id="{{ $record->id }}" data-num="{{ $record->num }}">配送中</div>
	                                        		<div class="btn btn-sm btn-primary send-complete" data-id="{{ $record->id }}" data-num="{{ $record->num }}">配送完成</div> -->
	                                        		<div class="btn btn-sm btn-danger back-money disabled" data-id="{{ $record->id }}" data-type="{{ $record->time }}" data-num="{{ $record->num }}" data-back-money="{{ $record->back_money }}">退款</div
	                                        	@endif
                                        	</div>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="dataTables_info" id="DataTables_Table_0_info"
                                role="alert" aria-live="polite" aria-relevant="all">每页{{ $records->count() }}条，共{{ $records->lastPage() }}页，总{{ $records->total() }}条。</div>
                        </div>
                        <div class="col-sm-6">
                            <div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_0_paginate">
                                {!! $records->setPath('')->appends(Request::all())->render() !!}
                            </div>
                        </div>
                    </div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="change-food-modal" tabindex="-1" role="dialog" aria-labelledby="change-food-modal-label" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="change-food-modal-label">
					更换配餐
				</h4>
			</div>
			<div class="modal-body">
				<form class="form-horizontal" method="POST" id="changed-food-form">
					{{ csrf_field() }}

					<div class="form-group">
						<label class="control-label col-sm-3">已定配餐</label>
						<div class="col-sm-6">
							<div class="form-control food-info"></div>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-3">更改配餐</label>
						<div class="col-sm-3">
							<select class="form-control" id="changed-food" name="food_type">
								<option selected value="1">男士餐</option>
								<option value="2">女士餐</option>
								<option value="3">工作餐</option>
							</select>
						</div>
						<div class="col-sm-3">
							<input type="number" class="form-control" name="food_num" placeholder="份数" id="changed-food-num">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-3">应补</label>
						<div class="col-sm-6">
							<input class="form-control back-money" id="need-pay" name="need_pay" readonly type="text">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-3">规则</label>
						<div class="back-money col-sm-6">
							只能换更贵的<br>
							男士餐单价：{{ $man_food_price . '元' }}<br>
							女士餐单价：{{ $woman_food_price . '元' }}<br>
							工作餐单价：{{ $work_food_price . '元' }}
						</div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-3">
							<div class="btn btn-sm btn-primary btn-compute">计算差价</div>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default pull-left" data-dismiss="modal">
					取消
				</button>
				<button type="button" class="btn btn-success" id="change-food-confirm-btn">
					确认
				</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="change-address-modal" tabindex="-1" role="dialog" aria-labelledby="change-address-modal-label" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="change-address-modal-label">
					更换地址
				</h4>
			</div>
			<div class="modal-body">
				<form class="form-horizontal" method="POST" id="change-address-form">
					{{ csrf_field() }}

					<div class="form-group">
						<label class="control-label col-sm-3">已定配餐</label>
						<div class="col-sm-6">
							<div class="form-control food-info"></div>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-3">原配送地址</label>
						<div class="col-sm-6">
							<div class="form-control food-address"></div>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-3">更改配送地址</label>
						<div class="col-sm-6">
							<select class="form-control" name="select_address">
								@foreach($addresses as $address)
									@if($loop->index == 0)
										<option value="{{ $address->id }}" selected>{{ $address->address }}</option>
									@else
										<option value="{{ $address->id }}">{{ $address->address }}</option>
									@endif
								@endforeach					
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-3">是否新增地址</label>
						<div class="col-sm-6">
						    <label class="radio-inline">
						        <input type="radio" value="1" name="use_new_address" id="use-new-address">是
						    </label>
						    <label class="radio-inline">
						        <input type="radio" value="0" name="use_new_address" checked id="not-use-new-address">否
						    </label>
						</div>
					</div>
					<div class="new-address-group sg-hide">
						<div class="form-group">
							<label class="col-xs-3 col-sm-3 control-label">区县</label>
			                <div class="col-xs-9 col-sm-9">
			                    <select class="form-control" id="cmbArea" name="">
			                    	@foreach($ranges as $range)
			                    	<option value="{{ $range->county }}">{{ $range->county }}</option>
			                    	@endforeach
			                    </select>
			                </div>
						</div>
						<div class="form-group">
							<div class="col-xs-9 col-sm-9 col-sm-offset-3">
								<input type="text" class="form-control" placeholder="详细地址" id="detail-address">
								<input type="text" class="sg-hide" name="detail_address" id="hide-address">
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default pull-left" data-dismiss="modal">
					取消
				</button>
				<button type="button" class="btn btn-success" id="change-address-confirm-btn">
					确认
				</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="delay-time-modal" tabindex="-1" role="dialog" aria-labelledby="delay-time-modal-label" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="delay-time-modal-label">
					延时送餐
				</h4>
			</div>
			<div class="modal-body">
				<form class="form-horizontal" method="POST" id="delay-time-form">
					{{ csrf_field() }} 
					<div class="form-group">
						<label class="control-label col-sm-3">已定配餐</label>
						<div class="col-sm-6">
							<div class="form-control food-info"></div>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-3">原配送时间</label>
						<div class="col-sm-6">
							<div class="form-control send-time"></div>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-3">更改时间为</label>
						<div class="col-sm-6">
							<input type="text" name="changed_time" id="delay-time-input" class="form-control">
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default pull-left" data-dismiss="modal">
					取消
				</button>
				<button type="button" class="btn btn-success" id="delay-time-confirm-btn">
					确认
				</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="cancel-send-modal" tabindex="-1" role="dialog" aria-labelledby="cancel-send-modal-label" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="cancel-send-modal-label">
					取消配送
				</h4>
			</div>
			<div class="modal-body">
				<form class="form-horizontal" method="POST" id="cancel-send-form">
					{{ csrf_field() }} 
					<div class="form-group">
						<label class="control-label col-sm-3">已定配餐</label>
						<div class="col-sm-6">
							<div class="form-control food-info"></div>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-3">取消原因</label>
						<div class="col-sm-6">
							<textarea class="form-control" name="reason" rows="3"></textarea>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default pull-left" data-dismiss="modal">
					取消
				</button>
				<button type="button" class="btn btn-success" id="cancel-send-confirm-btn">
					确认
				</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="back-money-modal" tabindex="-1" role="dialog" aria-labelledby="back-money-modal-label" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="back-money-modal-label">
					退款
				</h4>
			</div>
			<div class="modal-body">
				<form class="form-horizontal" method="POST" id="back-money-form">
					{{ csrf_field() }} 
					<div class="form-group">
						<label class="control-label col-sm-3">已定配餐</label>
						<div class="col-sm-6">
							<div class="form-control food-info"></div>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-3">退款金额</label>
						<div class="col-sm-6">
							<input type="number" name="back_money" class="form-control back-money-input">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-3">退款原因</label>
						<div class="col-sm-6">
							<textarea class="form-control" name="reason" rows="3"></textarea>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default pull-left" data-dismiss="modal">
					取消
				</button>
				<button type="button" class="btn btn-success" id="back-money-confirm-btn">
					确认
				</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="back-all-money-modal" tabindex="-1" role="dialog" aria-labelledby="back-all-money-modal-label" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="back-all-money-modal-label">
					全部退款
				</h4>
			</div>
			<div class="modal-body">
				<form class="form-horizontal" method="POST" id="back-all-money-form" action="{{ U('Backend/Order/backAllMoney', ['order_id' => $order->id]) }}">
					{{ csrf_field() }} 
					<div class="form-group">
						<label class="control-label col-sm-3">已定配餐</label>
						<div class="col-sm-6">
							<div class="form-control food-info food-name"></div>
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-3">退款金额</label>
						<div class="col-sm-6">
							<input type="number" name="back_money" class="form-control back-money-input">
						</div>
					</div>
					<div class="form-group">
						<label class="control-label col-sm-3">退款原因</label>
						<div class="col-sm-6">
							<textarea class="form-control" name="reason" rows="3"></textarea>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default pull-left" data-dismiss="modal">
					取消
				</button>
				<button type="button" class="btn btn-danger" id="back-all-money-confirm-btn">
					全部退款
				</button>
			</div>
		</div>
	</div>
</div>
<form id="change-status-form" class="sg-hide" method="POST">
	{{ csrf_field() }}
</form>
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

	$('#delay-time-input').datetimepicker({
		format: "yyyy-mm-dd",
		autoclose: true,
		todayBtn: true,
		language: 'zh-CN',
		pickerPosition: "bottom-left",
		minView: 2,
	});

	$('#back-all-money-confirm-btn').on('click', function() {
		$('#back-all-money-form').submit();
	});

	$('#send-change-info').on('click', function() {
		url = "{{ U('Backend/Order/sendChangeInfo', ['id' => $order->id]) }}";
		window.location = url; 
	});

	$('#change-food-modal').on('click', '.btn-compute', function() {
		type = $('#change-food-modal').attr('data-type');
		num = parseInt($('#change-food-modal').attr('data-num'));
		man_food_price = parseInt("{{ $man_food_price }}");
		woman_food_price = parseInt("{{ $woman_food_price }}");
		work_food_price = parseInt("{{ $work_food_price }}");
		price = type == 1 ? man_food_price : (type == 2 ? woman_food_price : work_food_price);
		changed_food_type = $('#changed-food').val();
		changed_price = changed_food_type == 1 ? man_food_price : (changed_food_type == 2 ? woman_food_price : work_food_price);
		changed_food_num = parseInt($('#changed-food-num').val());
		need_pay = (changed_food_num * changed_price - num * price);
		$('#need-pay').val(need_pay);
		// console.log(type, num, changed_food_type, changed_food_num);
		// console.log(changed_food_num, changed_price, num, price, need_pay);
	});

	$('#order-send-table').on('click', '.change-food', function() {
		$('#change-food-modal').attr('data-type', $(this).attr('data-type'));
		$('#change-food-modal').attr('data-num', $(this).attr('data-num'));
		$('#change-food-modal').attr('data-id', $(this).attr('data-id'));
		type = parseInt($(this).attr('data-type'));
		food_name = (type == 1 ? '男士餐' : (type == 2 ? '女士餐' : '工作餐'));
		num = $(this).attr('data-num');
		food_name = food_name + num + '份';
		$('#change-food-modal .food-info').text(food_name);
		$('#change-food-modal').modal('show');
	}).on('click', '.change-address', function() {
		type = parseInt($(this).attr('data-type'));
		food_name = (type == 1 ? '男士餐' : (type == 2 ? '女士餐' : '工作餐'));
		num = $(this).attr('data-num');
		food_name = food_name + num + '份';
		address = $(this).attr('data-address');
		$('#change-address-modal .food-info').text(food_name);
		$('#change-address-modal .food-address').text(address);
		$('#change-address-modal').attr('data-id', $(this).attr('data-id')).modal('show');
	}).on('click', '.delay-time', function() {
		type = parseInt($(this).attr('data-type'));
		food_name = (type == 1 ? '男士餐' : (type == 2 ? '女士餐' : '工作餐'));
		num = $(this).attr('data-num');
		food_name = food_name + num + '份';
		time = $(this).attr('data-time');
		$('#delay-time-modal').attr('data-id', $(this).attr('data-id'));
		$('#delay-time-modal .food-info').text(food_name);
		$('#delay-time-modal .send-time').text(time);
		$('#delay-time-modal').modal('show');
	}).on('click', '.cancel-send', function() {
		type = parseInt($(this).attr('data-type'));
		food_name = (type == 1 ? '男士餐' : (type == 2 ? '女士餐' : '工作餐'));
		num = $(this).attr('data-num');
		food_name = food_name + num + '份';
		$('#cancel-send-modal').attr('data-id', $(this).attr('data-id'));
		$('#cancel-send-modal .food-info').text(food_name);
		$('#cancel-send-modal').modal('show');
	}).on('click', '.back-money', function() {
		type = parseInt($(this).attr('data-type'));
		food_name = (type == 1 ? '男士餐' : (type == 2 ? '女士餐' : '工作餐'));
		num = $(this).attr('data-num');
		food_name = food_name + num + '份';
		$('#back-money-modal').attr('data-id', $(this).attr('data-id'));
		$('#back-money-modal .food-info').text(food_name);
		$('#back-money-modal .back-money-input').val($(this).attr('data-back-money'));
		$('#back-money-modal').modal('show');
	}).on('click', '.sending', function() {
		url = "{{ U('Backend/Order/changeSendStatus') }}" + '?id=' + $(this).attr('data-id') + '&status=1';
		$('#change-status-form').attr('action', url).submit();
	}).on('click', '.send-complete', function() {
		url = "{{ U('Backend/Order/changeSendStatus') }}" + '?id=' + $(this).attr('data-id') + '&status=2';
		$('#change-status-form').attr('action', url).submit();
	});

	$('#back-all-money').on('click', function() {
		$('#back-all-money-modal').attr('data-id', $(this).attr('data-id'));
		$('#back-all-money-modal .food-name').text($('#food-info-detail').text());
		$('#back-all-money-modal .back-money-input').val($(this).attr('data-back-money'));
		$('#back-all-money-modal').modal('show');
	});

	$('#use-new-address').on('click', function() {
		$('#change-address-modal .new-address-group').removeClass('sg-hide').addClass('sg-show');
	});

	$('#not-use-new-address').on('click', function() {
		$('#change-address-modal .new-address-group').removeClass('sg-show').addClass('sg-hide');
	});

	$('#change-address-confirm-btn').on('click', function() {
		url = "{{ U('Backend/Order/changeSendAddress') }}" + '?record_id=' + $('#change-address-modal').attr('data-id');
		// province = $('#cmbProvince option:selected').text();
		// city = $('#cmbCity option:selected').text();
		area = $('#cmbArea option:selected').text();
		detail = $('#detail-address').val().trim();
		address = area + ' ' + detail;
		$('#hide-address').val(address);
		$('#change-address-form').attr('action', url).submit();
	});

	$('#delay-time-confirm-btn').on('click', function() {
		url = "{{ U('Backend/Order/delaySendTime') }}" + '?record_id=' + $('#delay-time-modal').attr('data-id');
		$('#delay-time-form').attr('action', url).submit();
	});

	$('#cancel-send-confirm-btn').on('click', function() {
		url = "{{ U('Backend/Order/cancelSend') }}" + '?record_id=' + $('#cancel-send-modal').attr('data-id');
		$('#cancel-send-form').attr('action', url).submit();
	});

	$('#back-money-confirm-btn').on('click', function() {
		url = "{{ U('Backend/Order/backMoney') }}" + '?record_id=' + $('#back-money-modal').attr('data-id');
		$('#back-money-form').attr('action', url).submit();
	});

	$('#change-food-confirm-btn').on('click', function() {
		url = "{{ U('Backend/Order/changeFood') }}" + '?record_id=' + $('#change-food-modal').attr('data-id');
		$('#changed-food-form').attr('action', url).submit();
	});
});
</script>
@endsection