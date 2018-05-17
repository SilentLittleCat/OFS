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
					<h5>提现列表</h5>
					<div class="ibox-tools">
						<a class="collapse-link"> <i class="fa fa-chevron-up"></i>
						</a>
					</div>
				</div>
				<div class="ibox-content">
					<div class="row">
						<form class="form-horizontal" role="form" method="POST" action="{{ U('Backend/Money/index') }}">
							{{ csrf_field() }}

							<div class="form-group">
								<label class="control-label col-sm-2">流水号</label>
								<div class="col-sm-3">
									<input type="text" name="num" class="form-control" value="{{ $request->num }}">
								</div>
								<label class="control-label col-sm-2">提现状态</label>
								<div class="col-sm-3">
									<select class="form-control" name="status">
										<option value="-1" {{ $request->status == -1 ? 'selected' : '' }}>全部</option>
										<option value="0" {{ $request->status == 0 ? 'selected' : '' }}>待审核</option>
										<option value="1" {{ $request->status == 1 ? 'selected' : '' }}>审核通过</option>
										<option value="2" {{ $request->status == 2 ? 'selected' : '' }}>已打款</option>
										<option value="3" {{ $request->status == 3 ? 'selected' : '' }}>审核不过</option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-2">申请人</label>
								<div class="col-sm-3">
									<input type="text" name="username" class="form-control" value="{{ $request->username }}">
								</div>
								<label class="control-label col-sm-2">申请人手机</label>
								<div class="col-sm-3">
									<input type="text" name="tel" class="form-control" value="{{ $request->tel }}">
								</div>
								<div class="col-sm-2">
									<button type="submit" class="btn btn-sm btn-primary" id="filter-btn" >筛选</button>
								</div>
							</div>
						</form>
					</div>
					<div class="sg-divider-bold"></div>
                    <table class="table table-striped table-bordered table-hover dataTable" id="moneys-table">
                        <thead>
                            <tr>
                                <th>流水号</th>
                                <th>提现金额</th>
                                <th>提现状态</th>
                                <th>提现方式</th>
                                <th>申请人</th>
                                <th>手机</th>
                                <th>申请时间</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($moneys) == 0)
                                <tr>
                                    <td colspan="8" class="sg-centered">暂无提现请求！</td>
                                </tr>
                            @else
                                @foreach($moneys as $money)
                                    <tr>
                                        <td>{{ sprintf('%013s', $money->id) }}</td>
                                        <td>{{ $money->money }}</td>
                                        @if($money->status == 0)
                                        	<td class="warning-color">待审核</td>
                                        @elseif($money->status == 1)
                                        	<td class="success-color">审核通过</td>
                                        @elseif($money->status == 2)
                                        	<td class="info-color">已打款</td>
                                        @else
                                        	<td class="danger-color">审核不过</td>
                                        @endif
                                        <td>微信二维码</td>
                                        <td>{{ $money->username }}</td>
                                        <td>{{ $money->tel }}</td>
                                        <td>{{ $money->created_at }}</td>
                                        <td>
                                    		<div class="btn-group">
                                    			<div class="btn btn-sm btn-primary btn-pass" data-status="2" data-id="{{ $money->id }}" data-money="{{ $money->money }}">审核通过</div>
                                    			<div class="btn btn-sm btn-danger btn-not-pass" data-status="3" data-id="{{ $money->id }}">审核不过</div>
                                    			<div class="btn btn-sm btn-info btn-show-code" data-id="{{ $money->id }}" data-img="{{ $money->img == null ? '' : url($money->img) }}">二维码</div>
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
                                role="alert" aria-live="polite" aria-relevant="all">每页{{ $moneys->count() }}条，共{{ $moneys->lastPage() }}页，总{{ $moneys->total() }}条。</div>
                        </div>
                        <div class="col-sm-6">
                            <div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_0_paginate">
								{{  $moneys->links() }}
                            </div>
                        </div>
                    </div>
				</div>
			</div>
		</div>
	</div>
</div>
<form class="sg-hide" method="POST" id="change-status-form">
	{{ csrf_field() }}
</form>
<div class="modal fade" id="pass-modal" tabindex="-1" role="dialog" aria-labelledby="pass-modal-label" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="pass-modal-label">
					审核通过确认
				</h4>
			</div>
			<div class="modal-body"></div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default pull-left" data-dismiss="modal">
					取消
				</button>
				<button type="button" class="btn btn-primary" id="pass-confirm-btn">
					确认
				</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="not-pass-modal" tabindex="-1" role="dialog" aria-labelledby="not-pass-modal-label" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="not-pass-modal-label">
					确定此次提现不通过？
				</h4>
			</div>
			<div class="modal-body">

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default pull-left" data-dismiss="modal">
					取消
				</button>
				<button type="button" class="btn btn-danger" id="not-pass-confirm-btn">
					确认
				</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="show-code-modal" tabindex="-1" role="dialog" aria-labelledby="show-code-modal-label" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="show-code-modal-label">
					扫描该用户的二维码进行提现操作
				</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-12" align="center">
						<img src="" id="show-code-img" width="400">
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default pull-left" data-dismiss="modal">
					取消
				</button>
				<button type="button" class="btn btn-primary" id="show-code-confirm-btn">
					确认
				</button>
			</div>
		</div>
	</div>
</div>
@endsection

@section('footer')
<script type="text/javascript">
$(function() {

	$('#moneys-table').on('click', '.btn-pass', function() {
		url = "{{ U('Backend/Money/changeStatus') }}" + '?id=' + $(this).attr('data-id') + '&status=' + $(this).attr('data-status');
		$('#change-status-form').attr('action', url);
		html = "该操作将会在用户余额上扣除&nbsp;&nbsp;&nbsp;<strong>" + $(this).attr('data-money') + "元</strong>&nbsp;&nbsp;&nbsp;！";
		$('#pass-modal .modal-body').html(html);
		$('#pass-modal').modal('show');
	}).on('click', '.btn-not-pass', function() {
		url = "{{ U('Backend/Money/changeStatus') }}" + '?id=' + $(this).attr('data-id') + '&status=' + $(this).attr('data-status');
		$('#change-status-form').attr('action', url);
		html = "提现不通过！";
		$('#not-pass-modal .modal-body').html(html);
		$('#not-pass-modal').modal('show');
	}).on('click', '.btn-show-code', function() {
		$('#show-code-img').attr('src', $(this).attr('data-img'));
		$('#show-code-modal').modal('show');
	});

	$('#pass-confirm-btn').on('click', function() {
		$('#change-status-form').submit();
	});

	$('#not-pass-confirm-btn').on('click', function() {
		$('#change-status-form').submit();
	});
});
</script>
@endsection