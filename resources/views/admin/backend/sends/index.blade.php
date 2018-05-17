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
					<h5>配送管理</h5>
					<div class="ibox-tools">
						<a class="collapse-link"> <i class="fa fa-chevron-up"></i>
						</a>
					</div>
				</div>
				<div class="ibox-content">
					<div class="row">
						<form class="form-horizontal" method="POST" action="{{ U('Backend/Send/index', ['search' => true]) }}">
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
									<div class="btn btn-sm btn-info pull-right" id="update-send" style="margin-right: 20px;">更新</div>
								</div>
							</div>
						</form>
					</div>
                    <table class="table table-striped table-bordered table-hover dataTable" id="order-send-table">
                        <thead>
                            <tr>
                            	<th>订单号</th>
                                <th>配送产品</th>
                                <th>收货地址</th>
                                <th>配送状态</th>
                                <th>收货人</th>
                                <th>收货人手机</th>
                                <th>配送时间</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($order_sends) == 0)
                                <tr>
                                    <td colspan="8" class="sg-centered">暂无配送记录！</td>
                                </tr>
                            @else
                                @foreach($order_sends as $send)
                                    <tr>
                                    	<td>{{ sprintf("%013d", $send->order_id) }}</td>
                                        <td>
                                        	@if($send->type == 1)
                                        		{{ '男士餐：' . $send->num . '份' }}
                                        	@endif
                                        	@if($send->type == 2)
                                        		{{ '女士餐：' . $send->num . '份' }}
                                        	@endif
                                        	@if($send->type == 3)
                                        		{{ '工作餐：' . $send->num . '份' }}
                                        	@endif
                                        </td>
                                        <td>{{ $send->address }}</td>
                                        @if($send->status == 0)
                                        	<td>未配送</td>
                                        @elseif($send->status == 1)
                                        	<td style="color: #f2ad50">配送中</td>
                                        @elseif($send->status == 2)
                                        	<td style="color: #57b658">已完成</td>
                                        @elseif($send->status == 3)
                                        	<td style="color: #d8534c">已取消</td>
                                        @elseif($send->status == 4)
                                        	<td style="color: #59c1de">已退款</td>
                                        @endif
                                        <td>{{ $send->name }}</td>
                                        <td>{{ $send->tel }}</td>
                                        <td>{{ $send->time }}</td>
                                        <td>
                                        	<div class="btn-group">
												<div class="btn btn-sm btn-info send-info" data-id="{{ $send->order_id }}">详情</div>
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
                                role="alert" aria-live="polite" aria-relevant="all">每页{{ $order_sends->count() }}条，共{{ $order_sends->lastPage() }}页，总{{ $order_sends->total() }}条。</div>
                        </div>
                        <div class="col-sm-6">
                            <div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_0_paginate">
                                {!! $order_sends->setPath('')->appends(Request::all())->render() !!}
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
				<button type="button" class="btn btn-success" id="change-status-confirm-btn">
					确认
				</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="delete-modal" tabindex="-1" role="dialog" aria-labelledby="delete-modal-label" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="delete-modal-label">确定要删除？</h4>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default pull-left" data-dismiss="modal">
					取消
				</button>
				<button type="button" class="btn btn-success" id="delete-confirm-btn">
					确认
				</button>
			</div>
		</div>
	</div>
</div>
<form id="change-status-form" style="display: none" method="POST">
	{{ csrf_field() }}
</form>

<form id="delete-form" style="display: none" method="POST">
	{{ csrf_field() }}
</form>

<form id="update-send-form" style="display: none" method="POST" action="{{ U('Backend/Send/updateSend') }}">
	{{ csrf_field() }}
</form>
@endsection

@section('footer')
<script type="text/javascript">
$(function() {
	$('#update-send').on('click', function() {
		$('#update-send-form').submit();
	})

	$('#begin_time').datepicker({
		autoclose: true
	});
	$('#end_time').datepicker({
		autoclose: true
	});

	$('#order-send-table').on('click', '.send-info', function() {
		url = "{{ U('Backend/Order/sendShow') }}" + '?order_id=' + $(this).attr('data-id');
		window.location = url;
	});
	// .on('.not-send', function() {
	// 	url = "{{ U('Backend/Send/changeStatus') }}" + '?status=0&id=' + $(this).attr('data-id');
	// 	$('#change-status-form').attr('action', url).submit();
	// }).on('.sending', function() {
	// 	url = "{{ U('Backend/Send/changeStatus') }}" + '?status=1&id=' + $(this).attr('data-id');
	// 	$('#change-status-form').attr('action', url).submit();
	// }).on('.send-complete', function() {
	// 	url = "{{ U('Backend/Send/changeStatus') }}" + '?status=2&id=' + $(this).attr('data-id');
	// 	$('#change-status-form').attr('action', url).submit();
	// }).on('.send-cancel', function() {
	// 	url = "{{ U('Backend/Send/changeStatus') }}" + '?status=3&id=' + $(this).attr('data-id');
	// 	$('#change-status-form').attr('action', url).submit();
	// }).on('.send-back-money', function() {

	// 	$('#change-status-modal').modal('show');
	// 	url = "{{ U('Backend/Send/changeStatus') }}" + '?status=3&id=' + $(this).attr('data-id');
	// 	$('#change-status-form').attr('action', url);
	// })

	$('#change-status-confirm-btn').on('click', function() {
		$('#change-status-form').submit();
	});

	$('#export-record').on('click', function() {
		type = $('#sort_type').val();
		begin_time = $('#begin_time').val();
		end_time = $('#end_time').val();
		keyword = $('#keyword').val();
		url = "{{ U('Backend/Send/export') }}" + '?type=' + type + '&begin_time=' + begin_time + '&end_time=' + end_time + '&keyword=' + keyword;
		window.location = url;
	});
});
</script>
@endsection