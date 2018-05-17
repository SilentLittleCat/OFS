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
					<h5>订单管理</h5>
					<div class="ibox-tools">
						<a class="collapse-link"> <i class="fa fa-chevron-up"></i>
						</a>
					</div>
				</div>
				<div class="ibox-content">
					<div class="row">
						<form class="form-horizontal" method="POST" action="{{ U('Backend/Order/index', ['search' => true]) }}">
							{{ csrf_field() }}

							<div class="form-group">
								<div class="col-sm-2">
									<select class="form-control" name="type" style="margin-left: 15px;" id="sort_type">
										@if(isset($info['type']))
											<option value="0" {{ $info['type'] == 0 ? 'selected' : '' }}>所有</option>
											<option value="1" {{ $info['type'] == 1 ? 'selected' : '' }}>男士餐</option>
											<option value="2" {{ $info['type'] == 2 ? 'selected' : '' }}>女士餐</option>
											<option value="3" {{ $info['type'] == 3 ? 'selected' : '' }}>工作餐</option>
										@else
											<option value="0" selected>所有</option>
											<option value="1">男士餐</option>
											<option value="2">女士餐</option>
											<option value="3">工作餐</option>
										@endif
									</select>
								</div>
								<div class="col-sm-4">
									<div class="input-group">
										@if(isset($info['begin_time']))
											<input type="text" name="begin_time" class="form-control" placeholder="起始时间" id="begin_time" value="{{ $info['begin_time'] }}">
										@else
											<input type="text" name="begin_time" class="form-control" placeholder="起始时间" id="begin_time">
										@endif
										<span class="input-group-addon">到</span>
										@if(isset($info['end_time']))
											<input type="text" name="end_time" class="form-control" placeholder="终止时间" id="end_time" value="{{ $info['end_time'] }}">
										@else
											<input type="text" name="end_time" class="form-control" placeholder="终止时间" id="end_time">
										@endif
									</div>
								</div>
								<div class="col-sm-3">
									@if(isset($info['keyword']))
										<input type="text" name="keyword" class="form-control" placeholder="订单号/姓名/电话" id="keyword" value="{{ $info['keyword'] }}">
									@else
										<input type="text" name="keyword" class="form-control" placeholder="订单号/姓名/电话" id="keyword">
									@endif
								</div>
								<div class="col-sm-3">
									<button class="btn btn-sm btn-primary" type="submit">筛选</button>
									<div class="btn btn-sm btn-primary pull-right" id="export-record" style="margin-right: 20px;">导出</div>
								</div>
							</div>
						</form>
					</div>
                    <table class="table table-striped table-bordered table-hover dataTable" id="order-table">
                        <thead>
                            <tr>
                            	<th class="sorting" data-sort="id">订单号</th>
                                <th>预定产品</th>
                                <th>订单金额</th>
                                <th>收货地址</th>
                                <th>订单状态</th>
                                <th>下单人</th>
                                <th>下单人手机</th>
                                <th class="sorting" data-sort="time">下单时间</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($orders) == 0)
                                <tr>
                                    <td colspan="9" class="sg-centered">暂无订单记录！</td>
                                </tr>
                            @else
                                @foreach($orders as $order)
                                    <tr>
                                    	<td>{{ sprintf("%013d", $order->id) }}</td>
                                        <td>
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
                                        <td>{{ $order->address }}</td>
                                        @if($order->pay_status == 0)
											<td class="danger-color">未支付</td>
                                        @elseif($order->status == 0)
                                        	<td class="warning-color">进行中</td>
                                        @else
                                        	<td class="success-color">已完成</td>
                                        @endif
                                        <td>{{ $order->real_name }}</td>
                                        <td>{{ $order->tel }}</td>
                                        <td>{{ $order->created_at }}</td>
                                        <td>
                                        	<div class="btn-group">
                                        		<div class="btn btn-sm btn-success order-detail" data-id="{{ $order->id }}">订单详情</div>
												<div class="btn btn-sm btn-info send-detail {{ $order->method == 1 ? 'disabled' : '' }}" data-id="{{ $order->id }}">配送详情</div>
												<div class="btn btn-sm btn-warning confirm-pay {{ $order->pay_status == 1 ? 'disabled' : '' }}" data-id="{{ $order->id }}" data-money="{{ $order->money }}">确认支付</div>
												<div class="btn btn-sm btn-primary set-complete {{ $order->status == 1 ? 'disabled' : '' }}" data-id="{{ $order->id }}">设置完成</div>
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
								 role="alert" aria-live="polite" aria-relevant="all">每页{{ $orders->count() }}条，共{{ $orders->lastPage() }}页，总{{ $orders->total() }}条。</div>
						</div>
						<div class="col-sm-6">
							<div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_0_paginate">
								{{ $orders->links() }}
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="change-status-modal" tabindex="-1" role="dialog" aria-labelledby="change-status-modal-label" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="change-status-modal-label">确定要设为完成？</h4>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default pull-left" data-dismiss="modal">
					取消
				</button>
				<button type="button" class="btn btn-primary" id="change-status-confirm-btn">
					确认
				</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="pay-modal" tabindex="-1" role="dialog" aria-labelledby="pay-modal-label" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="pay-modal-label">确定该用户已支付？</h4>
			</div>
			<div class="modal-body"></div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default pull-left" data-dismiss="modal">
					取消
				</button>
				<button type="button" class="btn btn-primary" id="pay-confirm-btn">
					确认
				</button>
			</div>
		</div>
	</div>
</div>
<form id="confirm-pay-form" style="display: none" method="POST">
	{{ csrf_field() }}
</form>
<form id="change-status-form" style="display: none" method="POST">
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

	$('#order-table').on('click', '.order-detail', function() {
		url = "{{ U('Backend/Order/show') }}" + '?order_id=' + $(this).attr('data-id');
		window.location = url;
	}).on('click', '.send-detail', function() {
		url = "{{ U('Backend/Order/sendShow') }}" + '?order_id=' + $(this).attr('data-id');
		window.location = url;
	}).on('click', '.confirm-pay', function() {
		url = "{{ U('Backend/Order/changePayStatus') }}" + '?status=1&id=' + $(this).attr('data-id');
		$('#confirm-pay-form').attr('action', url);
		info = '该操作将扣除用户余额' + $(this).attr('data-money') + '元，如需生成配送记录，将会生成相应配送记录';
		$('#pay-modal .modal-body').text(info);
		$('#pay-modal').modal('show');
	}).on('click', '.set-complete', function() {
		$('#change-status-modal').attr('data-id', $(this).attr('data-id'));
		$('#change-status-modal').modal('show');
	});

	$('#pay-confirm-btn').on('click', function() {
		$('#confirm-pay-form').submit();
	});

	$('#change-status-confirm-btn').on('click', function() {
		url = "{{ U('Backend/Order/changeStatus') }}" + '?status=1&id=' + $('#change-status-modal').attr('data-id');
		$('#change-status-form').attr('action', url).submit();
	});

	$('#export-record').on('click', function() {
		type = $('#sort_type').val();
		begin_time = $('#begin_time').val();
		end_time = $('#end_time').val();
		keyword = $('#keyword').val();
		url = "{{ U('Backend/Order/export') }}" + '?type=' + type + '&begin_time=' + begin_time + '&end_time=' + end_time + '&keyword=' + keyword;
		window.location = url;
	});
});
</script>
@endsection