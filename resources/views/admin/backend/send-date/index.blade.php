@extends('admin.layout')

@section('header')
<style type="text/css">
	.datepicker {
		z-index:9999 !important
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
					<h5>配送日期管理</h5>
					<div class="ibox-tools">
						<a class="collapse-link"> <i class="fa fa-chevron-up"></i>
						</a>
					</div>
				</div>
				<div class="ibox-content">
					<div class="row">
    					<div class="col-sm-4 pull-right">
    						<div class="btn btn-sm btn-primary pull-right" id="add-date">添加</div>
    					</div>
					</div>
					<table class="table table-striped table-bordered table-hover dataTable" id="send-time-table">
						<thead>
							<tr>
								<th>日期</th>
								<th>是否可配送</th>
								<th>操作</th>
							</tr>
						</thead>
						<tbody>
							@if(count($dates) == 0)
								<tr>
									<td colspan="3" class="sg-centered">暂无配送日期！</td>
								</tr>
							@else
								@foreach($dates as $date)
									<tr>
										<td>{{ $date->date }}</td>
										@if($date->status == 0)
											<td class="danger-color">否</td>
										@else
											<td class="success-color">是</td>
										@endif
										<td>
											<div class="btn-group">
												<div class="btn btn-sm btn-success btn-edit" data-id="{{ $date->id }}" data-date="{{ $date->date }}" data-status="{{ $date->status }}">编辑</div>
												<div class="btn btn-sm btn-danger btn-delete" data-id="{{ $date->id }}">删除</div>
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
                                role="alert" aria-live="polite" aria-relevant="all">每页{{ $dates->count() }}条，共{{ $dates->lastPage() }}页，总{{ $dates->total() }}条。</div>
                        </div>
                        <div class="col-sm-6">
                            <div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_0_paginate">
                                {!! $dates->setPath('')->appends(Request::all())->render() !!}
                            </div>
                        </div>
                    </div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="create-modal" tabindex="-1" role="dialog" aria-labelledby="create-modal-label" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="create-modal-label">
					添加配送日期项
				</h4>
			</div>
			<div class="modal-body">
				<form class="form-horizontal" method="POST" action="{{ U('Backend/SendDate/store') }}" id="create-form">
					
					{{ csrf_field() }}
					<div class="form-group">
						<label class="col-sm-3 control-label" for="send_date">选择时间</label>
						<div class="col-sm-9">
							<div class="input-group date" id="create-date-picker">
							    <input type="text" name="date" class="form-control" value="{{ Carbon\Carbon::now()->toDateString() }}" required>
							    <div class="input-group-addon">
							        <span class="glyphicon glyphicon-th"></span>
							    </div>
							</div>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-3 control-label" for="status">是否配送</label>
						<div class="col-sm-9">
						    <label class="radio-inline">
						        <input type="radio" name="status" value="1">是
						    </label>
						    <label class="radio-inline">
						        <input type="radio" name="status" value="0" checked>否
						    </label>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default pull-left" data-dismiss="modal">
					取消
				</button>
				<button type="button" class="btn btn-success" id="create-confirm-btn">
					确认
				</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="edit-modal" tabindex="-1" role="dialog" aria-labelledby="edit-modal-label" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="edit-modal-label">
					编辑配送日期项
				</h4>
			</div>
			<div class="modal-body">
				<form class="form-horizontal" method="POST" action="{{ U('Backend/SendDate/update') }}" id="edit-form">
					
					{{ csrf_field() }}

					<input type="text" name="id" style="display: none" id="edit-date-id">

					<div class="form-group">
						<label class="col-sm-3 control-label" for="send_date">选择时间</label>
						<div class="col-sm-9">
							<div class="input-group date" id="edit-date-picker">
							    <input type="text" name="date" class="form-control" value="{{ Carbon\Carbon::now()->toDateString() }}" required id="edit-date-date">
							    <div class="input-group-addon">
							        <span class="glyphicon glyphicon-th"></span>
							    </div>
							</div>
						</div>
					</div>

					<div class="form-group">
						<label class="col-sm-3 control-label" for="status">是否配送</label>
						<div id="edit-date-status" class="col-sm-9">
						    <label class="radio-inline">
						        <input type="radio" name="status" value="1">是
						    </label>
						    <label class="radio-inline">
						        <input type="radio" name="status" value="0" checked>否
						    </label>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default pull-left" data-dismiss="modal">
					取消
				</button>
				<button type="button" class="btn btn-success" id="edit-confirm-btn">
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
				<h4 class="modal-title" id="delete-modal-label">
					确定要删除该条记录吗？
				</h4>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default pull-left" data-dismiss="modal">
					取消
				</button>
				<button type="button" class="btn btn-danger" id="delete-confirm-btn">
					删除
				</button>
			</div>
		</div>
	</div>
</div>

<form method="POST" style="display: none;" id="delete-form">
    {{ csrf_field() }}
</form>
@endsection

@section('footer')
<script type="text/javascript">
$(function() {

	$('#create-date-picker').datepicker({
		autoclose: true
	});

	$('#edit-date-picker').datepicker({
		autoclose: true
	});

	$('#add-date').on('click', function() {
		$('#create-modal').modal('show');
	});

	$('#create-confirm-btn').on('click', function() {
		$('#create-form').submit();
	});

	$('#edit-confirm-btn').on('click', function() {
		$('#edit-form').submit();
	});

	$('#send-time-table').on('click', '.btn-edit', function() {
		$('#edit-date-id').val($(this).attr('data-id'));
		$('#edit-date-date').val($(this).attr('data-date'));
		$('#edit-date-status input[value=' + $(this).attr('data-status') + ']').prop('checked', true);

		$('#edit-modal').modal('show');
	}).on('click', '.btn-delete', function() {
		url = "{{ U('Backend/SendDate/destroy') }}" + "?id=" + $(this).attr('data-id');
		$('#delete-form').attr('action', url);
		$('#delete-modal').modal('show');
	});

	$('#delete-confirm-btn').on('click', function() {
		$('#delete-form').submit();
	});
});
</script>
@endsection