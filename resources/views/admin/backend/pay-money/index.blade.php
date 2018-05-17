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
					<h5>充值记录</h5>
					<div class="ibox-tools">
						<a class="collapse-link"> <i class="fa fa-chevron-up"></i>
						</a>
					</div>
				</div>
				<div class="ibox-content">
					<div class="row">
						<form class="form-horizontal" method="POST" action="{{ U('Backend/PayMoney/index', ['search' => true]) }}">
							{{ csrf_field() }}

							<div class="form-group">
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
									<button class="btn btn-sm btn-primary" type="submit">筛选</button>
								</div>
							</div>
						</form>
					</div>
					<table class="table table-striped table-bordered table-hover dataTable" id="pay-money-table">
						<thead>
                            <tr>
                                <th>时间</th>
                                <th>用户</th>
                                <th>金额</th>
                                <th>状态</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                        	@if(count($pay_moneys) == 0)
                        		<tr>
                                    <td colspan="5" class="sg-centered">暂无充值记录！</td>
                                </tr>
                        	@else
								@foreach($pay_moneys as $pay_money)
                                    <tr>
                                        <td>{{ $pay_money->created_at }}</td>
                                        <td>{{ $pay_money->username }}</td>
                                        <td>{{ $pay_money->money . '元' }}</td>
                                        @if($pay_money->status == 0)
                                        	<td>待确认</td>
                                        @elseif($pay_money->status == 1)
                                        	<td class="success-color">已付款</td>
                                        @elseif($pay_money->status == 2)
                                        	<td class="danger-color">未付款</td>
                                        @endif
                                        <td>
                                        	@if($pay_money->status == 0)
                                        		<div class="btn-group">
                                        			<div class="btn btn-sm btn-primary pay-complete" data-id="{{ $pay_money->id }}" data-money="{{ $pay_money->money }}" data-user="{{ $pay_money->username }}">已付款</div>
                                        			<div class="btn btn-sm btn-danger not-pay" data-id="{{ $pay_money->id }}" data-money="{{ $pay_money->money }}" data-user="{{ $pay_money->username }}">未付款</div>
                                        		</div>
                                        	@endif
                                        </td>
                                    </tr>
                                @endforeach
                        	@endif
                        </tbody>
					</table>
					<div class="row">
                        <div class="col-sm-6">
                            <div class="dataTables_info" id="DataTables_Table_0_info"
                                role="alert" aria-live="polite" aria-relevant="all">每页{{ $pay_moneys->count() }}条，共{{ $pay_moneys->lastPage() }}页，总{{ $pay_moneys->total() }}条。</div>
                        </div>
                        <div class="col-sm-6">
                            <div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_0_paginate">
                                {!! $pay_moneys->setPath('')->appends(Request::all())->render() !!}
                            </div>
                        </div>
                    </div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="pay-money-modal" tabindex="-1" role="dialog" aria-labelledby="pay-money-modal-label" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="pay-money-modal-label">
					付款确认
				</h4>
			</div>
			<div class="modal-body">

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default pull-left" data-dismiss="modal">
					取消
				</button>
				<button type="button" class="btn btn-primary" id="pay-money-confirm-btn">
					确认
				</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="pay-not-modal" tabindex="-1" role="dialog" aria-labelledby="pay-not-modal-label" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="pay-not-modal-label">
					未付款确认
				</h4>
			</div>
			<div class="modal-body">

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default pull-left" data-dismiss="modal">
					取消
				</button>
				<button type="button" class="btn btn-danger" id="pay-not-confirm-btn">
					确认
				</button>
			</div>
		</div>
	</div>
</div>
<form method="POST" style="display: none;" id="change-status-form">
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

	$('#pay-money-table').on('click', '.pay-complete', function() {
		url = "{{ U('Backend/PayMoney/changeStatus') }}" + '?status=1&id=' + $(this).attr('data-id');
		$('#change-status-form').attr('action', url);
		text = '确认为&nbsp;&nbsp;<strong>' + $(this).attr('data-user') + '</strong>&nbsp;&nbsp;充值&nbsp;&nbsp;<strong>' + $(this).attr('data-money') + '</strong>&nbsp;&nbsp;元吗？';
		$('#pay-money-modal .modal-body').html(text);
		$('#pay-money-modal').modal('show');
	}).on('click', '.not-pay', function() {
		url = "{{ U('Backend/PayMoney/changeStatus') }}" + '?status=2&id=' + $(this).attr('data-id');
		$('#change-status-form').attr('action', url);
		text = '确定&nbsp;&nbsp;<strong>' + $(this).attr('data-user') + '</strong>&nbsp;&nbsp;充值&nbsp;&nbsp;<strong>' + $(this).attr('data-money') + '</strong>&nbsp;&nbsp;元<strong>&nbsp;&nbsp;无效</strong>&nbsp;&nbsp;吗？';
		$('#pay-not-modal .modal-body').html(text);
		$('#pay-not-modal').modal('show');
	});

	$('#pay-money-confirm-btn').on('click', function() {
		$('#change-status-form').submit();
	});

	$('#pay-not-confirm-btn').on('click', function() {
		$('#change-status-form').submit();
	});
});
</script>
@endsection