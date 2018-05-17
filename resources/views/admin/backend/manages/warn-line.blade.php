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
					<h5>库存警戒线设置</h5>
					<div class="ibox-tools">
						<a class="collapse-link"> <i class="fa fa-chevron-up"></i>
						</a>
					</div>
				</div>
				<div class="ibox-content">
					<div class="row">
						<div class="col-sm-4 col-sm-offset-8">
							<div class="btn-group pull-right">
								<div class="btn btn-sm btn-primary pull-right" id="add-class">新增</div>
							</div>
						</div>
					</div>
					<table class="table table-striped table-bordered table-hover dataTable" id="warn-lines-table">
						<thead>
							<tr>
								<th>名称</th>
								<th>警戒线</th>
								<th>提示信息</th>
								<th>状态</th>
								<th>操作</th>
							</tr>
						</thead>
						<tbody>
							@if(count($warn_lines) == 0)
								<tr>
									<td colspan="5" class="sg-centered">暂无库存警戒线！</td>
								</tr>
							@else
								@foreach($warn_lines as $warn_line)
									<tr>
										<td>{{ $warn_line->name }}</td>
										<td>{{ '<=' . $warn_line->line }}</td>
										<td>{{ $warn_line->info }}</td>
										@if($warn_line->status == 0)
											<td class="danger-color">禁用</td>
										@else
											<td class="success-color">启用</td>
										@endif
										<td>
											<div class="btn-group">
												<div class="btn btn-sm btn-primary btn-use {{ $warn_line->status == 0 ? '' : 'disabled' }}" data-id="{{ $warn_line->id }}">启用</div>
												<div class="btn btn-sm btn-default btn-forbit {{ $warn_line->status == 1 ? '' : 'disabled' }}" data-id="{{ $warn_line->id }}">禁用</div>
												<div class="btn btn-sm btn-success btn-edit"  data-id="{{ $warn_line->id }}" data-name="{{ $warn_line->name }}" data-status="{{ $warn_line->status }}" data-info="{{ $warn_line->info }}" data-line="{{ $warn_line->line }}">编辑</div>
												<div class="btn btn-sm btn-danger btn-delete" data-id="{{ $warn_line->id }}" data-name="{{ $warn_line->name }}">删除</div>
											</div>
										</td>
									</tr>
								@endforeach
							@endif
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="add-modal" tabindex="-1" role="dialog" aria-labelledby="add-modal-label" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="add-modal-label">
					添加库存警戒线
				</h4>
			</div>
			<div class="modal-body">
				<form class="form-horizontal" id="add-form" method="POST" action="{{ U('Backend/Manage/storeWarnLine') }}">
					{{ csrf_field() }}

					<div class="form-group">
						<label class="col-sm-4 control-label">名称</label>
						<div class="col-sm-8">
							<select class="form-control" name="name">
								@foreach($warn_items as $warn_item)
									<option value="{{ $warn_item->name }}">{{ $warn_item->name }}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label">警戒线</label>
						<div class="col-sm-8">
							<input type="number" name="line" class="form-control" placeholder="当库存小于等于该值时提示">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label">提示信息</label>
						<div class="col-sm-8">
							<input type="text" name="info" class="form-control">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label">启用状态</label>
						<div class="col-sm-8">
							<label class="radio-inline">
								<input type="radio" name="status" value="1" checked>启用
							</label>
							<label class="radio-inline">
								<input type="radio" name="status" value="0">禁用
							</label>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default pull-left" data-dismiss="modal">
					取消
				</button>
				<button type="button" class="btn btn-success" id="add-confirm-btn">
					添加
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
					编辑警戒线
				</h4>
			</div>
			<div class="modal-body">
				<form class="form-horizontal" id="edit-form" method="POST" action="{{ U('Backend/Manage/updateWarnLine') }}">
					{{ csrf_field() }}

					<input type="text" name="id" style="display: none" id="warn-line-id">
					<div class="form-group">
						<label class="col-sm-4 control-label">名称</label>
						<div class="col-sm-8">
							<select class="form-control" name="name" id="warn-line-name">
								@foreach($warn_items as $warn_item)
									<option value="{{ $warn_item->name }}">{{ $warn_item->name }}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label">警戒线</label>
						<div class="col-sm-8">
							<input type="number" name="line" class="form-control" placeholder="当库存小于等于该值时提示" id="warn-line-line">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label">提示信息</label>
						<div class="col-sm-8">
							<input type="text" name="info" class="form-control" id="warn-line-info">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label">启用状态</label>
						<div class="col-sm-8" id="warn-line-status">
							<label class="radio-inline">
								<input type="radio" name="status" value="1" checked>启用
							</label>
							<label class="radio-inline">
								<input type="radio" name="status" value="0">禁用
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
					保存
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
				<h4 class="modal-title" id="delete-modal-label"></h4>
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
<form method="POST" style="display: none;" id="change-status-form">
    {{ csrf_field() }}
</form>
<form method="POST" style="display: none;" id="delete-form">
    {{ csrf_field() }}
</form>
@endsection

@section('footer')
<script type="text/javascript">
$(function() {
	$('#add-class').on('click', function() {
		$('#add-modal').modal('show');
	});

	$('#add-confirm-btn').on('click', function() {
		$('#add-form').submit();
	});
	$('#edit-confirm-btn').on('click', function() {
		$('#edit-form').submit();
	});

	$('#warn-lines-table').on('click', '.btn-use', function() {
		url = "{{ U('Backend/Manage/changeWarnLineStatus') }}" + '?id=' + $(this).attr('data-id') + '&status=1';
		$('#change-status-form').attr('action', url).submit();
	}).on('click', '.btn-forbit', function() {
		url = "{{ U('Backend/Manage/changeWarnLineStatus') }}" + '?id=' + $(this).attr('data-id') + '&status=0';
		$('#change-status-form').attr('action', url).submit();
	}).on('click', '.btn-edit', function() {
		$('#warn-line-id').val($(this).attr('data-id'));
		$('#warn-line-line').val($(this).attr('data-line'));
		$('#warn-line-info').val($(this).attr('data-info'));
		status = $(this).attr('data-status');
		// $('#class-status input').attr('checked', false);
		$('#warn-line-status input[value=' + status + ']').prop('checked', true);
		name = $(this).attr('data-name');
		$('#warn-line-name option[value=' + name + ']').prop('selected', true);
		$('#edit-modal').modal('show');
	}).on('click', '.btn-delete', function() {
		text = "确定要删除库存警戒线分类  " + $(this).attr('data-name') + '  吗？';
		$('#delete-modal-label').text(text);
		$('#delete-modal').attr('data-id', $(this).attr('data-id'));
		$('#delete-modal').modal('show');
	});

	$('#delete-confirm-btn').on('click', function() {
		url = "{{ U('Backend/Manage/deleteWarnLine') }}" + '?id=' + $('#delete-modal').attr('data-id');
		$('#delete-form').attr('action', url).submit();
	});
});
</script>
@endsection