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
					<h5>退款管理</h5>
					<div class="ibox-tools">
						<a class="collapse-link"> <i class="fa fa-chevron-up"></i>
						</a>
					</div>
				</div>
				<div class="ibox-content">
					<div class="row">
						<form class="form-horizontal" method="POST" action="{{ U('Backend/BackMoney/index', ['search' => true]) }}">
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
									<button type="button" class="btn btn-sm btn-primary">搜索</button>
								</div>
							</div>
						</form>
					</div>
                    <table class="table table-striped table-bordered table-hover dataTable" id="order-table">
                        <thead>
                            <tr>
                            	<th class="sorting" data-sort="id">订单号</th>
                                <th>配送产品</th>
                                <th>退款金额</th>
                                <th>收货地址</th>
                                <th>下单人</th>
                                <th>下单人手机</th>
                                <th class="sorting" data-sort="time">下单时间</th>
                                <th>状态</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($orders) == 0)
                                <tr>
                                    <td colspan="9" class="sg-centered">暂无售后订单记录！</td>
                                </tr>
                            @else
                                @foreach($orders as $order)
                                    <tr>
                                    	<td>{{ sprintf("%013d", $order->order_id) }}</td>
                                        <td>
                                        	@if($order->type == 1)
                                        		{{ '男士餐：' }}
                                        	@endif
                                        	@if($order->type == 2)
                                        		{{ '女士餐：' }}
                                        	@endif
                                        	@if($order->type == 3)
                                        		{{ '工作餐：' }}
                                        	@endif
                                        </td>
                                        <td>{{ $order->price }}</td>
                                        <td>{{ $order->address }}</td>
                                        <td>{{ $order->name }}</td>
                                        <td>{{ $order->tel }}</td>
                                        <td>{{ $order->time }}</td>
                                        @if($order->status == 0)
                                        	<td class="warning-color">待审核</td>
                                        @elseif($order->status == 1)
                                        	<td class="success-color">审核通过</td>
                                        @else
                                        	<td class="danger-color">审核不过</td>
                                        @endif
                                        <td>
                                        	<div class="btn-group">
                                        		<div class="btn btn-sm btn-info order-detail" data-id="{{ $order->id }}" data-reason="{{ $order->reason }}" data-img="{{ $order->img == null ? '' : url($order->img) }}">订单详情</div>
												<div class="btn btn-sm btn-primary change-money" data-id="{{ $order->id }}">更改金额</div>
												<div class="btn btn-sm btn-success order-pass" data-id="{{ $order->id }}">审核通过</div>
												<div class="btn btn-sm btn-danger order-not-pass" data-id="{{ $order->id }}">审核不过</div>
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
<div class="modal fade" id="detail-modal" tabindex="-1" role="dialog" aria-labelledby="detail-modal-label" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="detail-modal-label">售后订单详情</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-12" align="center">
						<img src="" id="detail-modal-img" width="400">
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12" id="detail-reason">
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default pull-left" data-dismiss="modal">
					取消
				</button>
				<button type="button" class="btn btn-primary">
					确认
				</button>
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
<div class="modal fade" id="change-money-modal" tabindex="-1" role="dialog" aria-labelledby="change-money-modal-label" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">更改售后订单的退款金额</h4>
			</div>
			<div class="modal-body">
				<form class="form-horizontal" role="form" id="change-money-form" method="POST" action="{{ U('Backend/BackMoney/changeMoney') }}">
					{{ csrf_field() }}
					<input type="hidden" name="id" id="change-money-id-input">
					<div class="form-group">
						<label for="firstname" class="col-sm-2 control-label">金额</label>
						<div class="col-sm-10">
      						<input type="text" class="form-control" placeholder="请输入金额" required name="money">
    					</div>
					</div>
					<div class="form-group">
						<div class="col-sm-offset-2 col-sm-10">
      						<button type="submit" class="btn btn-primary">确认</button>
    					</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
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
		$('#detail-modal-img').attr('src', $(this).attr('data-img'));
		$('#detail-reason').text($(this).attr('data-reason'));
		$('#detail-modal').modal('show');
	}).on('click', '.order-pass', function() {
		url = "{{ U('Backend/BackMoney/changeStatus') }}" + '?id=' + $(this).attr('data-id') + '&status=1';
		$('#change-status-form').attr('action', url);
		$('#change-status-modal-label').text('确定要设置审核通过吗？');
		$('#change-status-modal').modal('show');
	}).on('click', '.order-not-pass', function() {
		url = "{{ U('Backend/BackMoney/changeStatus') }}" + '?id=' + $(this).attr('data-id') + '&status=2';
		$('#change-status-form').attr('action', url);
		$('#change-status-modal-label').text('确定要设置审核不过吗？');
		$('#change-status-modal').modal('show');
	}).on('click', '.change-money', function() {
		$('#change-money-id-input').val($(this).attr('data-id'));
		url = "{{ U('Backend/BackMoney/changeMoney') }}";
		$('#change-money-form').attr('action', url);
		$('#change-money-modal').modal('show');
	});

	$('#change-status-confirm-btn').on('click', function() {
		$('#change-status-form').submit();
	});
});
</script>
@endsection