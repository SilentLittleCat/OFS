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
					<h5>运营成本分类管理</h5>
					<div class="ibox-tools">
						<a class="collapse-link"> <i class="fa fa-chevron-up"></i>
						</a>
					</div>
				</div>
				<div class="ibox-content">
					<div class="row">
						<div class="col-sm-4 col-sm-offset-8">
							<div class="btn-group pull-right">
								<div class="btn btn-sm btn-primary pull-right" id="add-class">添加</div>
							</div>
						</div>
					</div>
					<table class="table table-striped table-bordered table-hover dataTable" id="cost-classes-table">
						<thead>
							<tr>
								<th>排序编号</th>
								<th>名称</th>
								<th>状态</th>
								<th>操作</th>
							</tr>
						</thead>
						<tbody>
							@if(count($cost_classes) == 0)
								<tr>
									<td colspan="4" class="sg-centered">暂无运营成本分类！</td>
								</tr>
							@else
								@foreach($cost_classes as $cost_class)
									<tr>
										<td>{{ $cost_class->sort }}</td>
										<td>{{ $cost_class->name }}</td>
										@if($cost_class->status == 0)
											<td class="danger-color">禁用</td>
										@else
											<td class="success-color">启用</td>
										@endif
										<td>
											<div class="btn-group">
												<div class="btn btn-sm btn-primary btn-use {{ $cost_class->status == 0 ? '' : 'disabled' }}" data-id="{{ $cost_class->id }}">启用</div>
												<div class="btn btn-sm btn-default btn-forbit {{ $cost_class->status == 1 ? '' : 'disabled' }}" data-id="{{ $cost_class->id }}">禁用</div>
												<div class="btn btn-sm btn-success btn-edit"  data-id="{{ $cost_class->id }}" data-name="{{ $cost_class->name }}" data-status="{{ $cost_class->status }}" data-sort="{{ $cost_class->sort }}">编辑</div>
												<div class="btn btn-sm btn-danger btn-delete" data-id="{{ $cost_class->id }}" data-name="{{ $cost_class->name }}">删除</div>
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
					添加运营成本分类
				</h4>
			</div>
			<div class="modal-body">
				<form class="form-horizontal" id="add-form" method="POST" action="{{ U('Backend/Manage/storeCostClass') }}">
					{{ csrf_field() }}

					<div class="form-group">
						<label class="col-sm-4 control-label">分类名称</label>
						<div class="col-sm-8">
							<input type="text" name="name" class="form-control" required>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label">分类排序</label>
						<div class="col-sm-8">
							<input type="number" name="sort" class="form-control" placeholder="正整数，越大越靠后，0为二级分类">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label">分类状态</label>
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
					编辑采购分类
				</h4>
			</div>
			<div class="modal-body">
				<form class="form-horizontal" id="edit-form" method="POST" action="{{ U('Backend/Manage/updateCostClass') }}">
					{{ csrf_field() }}

					<input type="text" name="id" style="display: none" id="class-id">
					<div class="form-group">
						<label class="col-sm-4 control-label">分类名称</label>
						<div class="col-sm-8">
							<input type="text" name="name" class="form-control" required id="class-name">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label">分类排序</label>
						<div class="col-sm-8">
							<input type="number" name="sort" class="form-control" placeholder="正整数，越大越靠后" id="class-sort">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-4 control-label">分类状态</label>
						<div class="col-sm-8" id="class-status">
							<label class="radio-inline">
								<input type="radio" name="status" value="1">启用
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

	$('#cost-classes-table').on('click', '.btn-use', function() {
		url = "{{ U('Backend/Manage/changeCostClassStatus') }}" + '?id=' + $(this).attr('data-id') + '&status=1';
		$('#change-status-form').attr('action', url).submit();
	}).on('click', '.btn-forbit', function() {
		url = "{{ U('Backend/Manage/changeCostClassStatus') }}" + '?id=' + $(this).attr('data-id') + '&status=0';
		$('#change-status-form').attr('action', url).submit();
	}).on('click', '.btn-edit', function() {
		$('#class-id').val($(this).attr('data-id'));
		$('#class-name').val($(this).attr('data-name'));
		$('#class-sort').val($(this).attr('data-sort'));
		status = $(this).attr('data-status');
		// $('#class-status input').attr('checked', false);
		$('#class-status input[value=' + status + ']').prop('checked', true);
		$('#edit-modal').modal('show');
	}).on('click', '.btn-delete', function() {
		text = "确定要删除运营成本分类  " + $(this).attr('data-name') + '  吗？';
		$('#delete-modal-label').text(text);
		$('#delete-modal').attr('data-id', $(this).attr('data-id'));
		$('#delete-modal').modal('show');
	});

	$('#delete-confirm-btn').on('click', function() {
		url = "{{ U('Backend/Manage/deleteCostClass') }}" + '?id=' + $('#delete-modal').attr('data-id');
		$('#delete-form').attr('action', url).submit();
	});
});
</script>
@endsection