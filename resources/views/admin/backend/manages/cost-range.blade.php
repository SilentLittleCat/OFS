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
					<h5>成本区间设置</h5>
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
					<table class="table table-striped table-bordered table-hover dataTable" id="cost-ranges-table">
						<thead>
							<tr>
								<th>分类</th>
								<th>规定区间</th>
								<th>提示信息</th>
								<th>状态</th>
								<th>操作</th>
							</tr>
						</thead>
						<tbody>
							@if(count($cost_ranges) == 0)
								<tr>
									<td colspan="5" class="sg-centered">暂无成本区间！</td>
								</tr>
							@else
								@foreach($cost_ranges as $cost_range)
									<tr>
										<td>{{ $cost_range->name }}</td>
										<td>{{ '营收' . $cost_range->range_min . '%到' . $cost_range->range_max . '%' }}</td>
										<td>{{ $cost_range->info }}</td>
										@if($cost_range->status == 0)
											<td class="danger-color">禁用</td>
										@else
											<td class="success-color">启用</td>
										@endif
										<td>
											<div class="btn-group">
												<div class="btn btn-sm btn-primary btn-use {{ $cost_range->status == 0 ? '' : 'disabled' }}" data-id="{{ $cost_range->id }}">启用</div>
												<div class="btn btn-sm btn-default btn-forbit {{ $cost_range->status == 1 ? '' : 'disabled' }}" data-id="{{ $cost_range->id }}">禁用</div>
												<div class="btn btn-sm btn-success btn-edit"  data-id="{{ $cost_range->id }}" data-name="{{ $cost_range->name }}" data-status="{{ $cost_range->status }}" data-info="{{ $cost_range->info }}" data-range-min="{{ $cost_range->range_min }}" data-range-max="{{ $cost_range->range_max }}">编辑</div>
												<div class="btn btn-sm btn-danger btn-delete" data-id="{{ $cost_range->id }}" data-name="{{ $cost_range->name }}">删除</div>
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
					添加成本区间
				</h4>
			</div>
			<div class="modal-body">
				<form class="form-horizontal" id="add-form" method="POST" action="{{ U('Backend/Manage/storeCostRange') }}">
					{{ csrf_field() }}

					<div class="form-group">
						<label class="col-sm-2 control-label">名称</label>
						<div class="col-sm-10">
							<select class="form-control" name="name">
								@foreach($cost_classes as $cost_class)
									<option value="{{ $cost_class->name }}">{{ $cost_class->name }}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">规定区间</label>
						<div class="col-sm-5">
							<input type="number" name="range_min" class="form-control" placeholder="区间下限，请填写整数">
						</div>
						<div class="col-sm-5">
							<input type="number" name="range_max" class="form-control" placeholder="区间上限，请填写整数">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">提示信息</label>
						<div class="col-sm-10">
							<input type="text" name="info" class="form-control">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">启用状态</label>
						<div class="col-sm-10">
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
					编辑规定区间
				</h4>
			</div>
			<div class="modal-body">
				<form class="form-horizontal" id="edit-form" method="POST" action="{{ U('Backend/Manage/updateCostRange') }}">
					{{ csrf_field() }}

					<input type="text" name="id" style="display: none" id="cost-range-id">
					<div class="form-group">
						<label class="col-sm-2 control-label">分类</label>
						<div class="col-sm-10">
							<select class="form-control" name="name" id="cost-range-name">
								@foreach($cost_classes as $cost_class)
									<option value="{{ $cost_class->name }}">{{ $cost_class->name }}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">规定区间</label>
						<div class="col-sm-5">
							<input type="number" name="range_min" class="form-control" placeholder="区间下限，请填写整数" id="cost-range-min">
						</div>
						<div class="col-sm-5">
							<input type="number" name="range_max" class="form-control" placeholder="区间上限，请填写整数" id="cost-range-max">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">提示信息</label>
						<div class="col-sm-10">
							<input type="text" name="info" class="form-control" id="cost-range-info">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">启用状态</label>
						<div class="col-sm-10" id="cost-range-status">
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

	$('#cost-ranges-table').on('click', '.btn-use', function() {
		url = "{{ U('Backend/Manage/changeCostRangeStatus') }}" + '?id=' + $(this).attr('data-id') + '&status=1';
		$('#change-status-form').attr('action', url).submit();
	}).on('click', '.btn-forbit', function() {
		url = "{{ U('Backend/Manage/changeCostRangeStatus') }}" + '?id=' + $(this).attr('data-id') + '&status=0';
		$('#change-status-form').attr('action', url).submit();
	}).on('click', '.btn-edit', function() {
		$('#cost-range-id').val($(this).attr('data-id'));
		$('#cost-range-min').val($(this).attr('data-range-min'));
		$('#cost-range-max').val($(this).attr('data-range-max'));
		$('#cost-range-info').val($(this).attr('data-info'));
		status = $(this).attr('data-status');
		// $('#class-status input').attr('checked', false);
		$('#cost-range-status input[value=' + status + ']').prop('checked', true);
		name = $(this).attr('data-name');
		$('#cost-range-name option[value=' + name + ']').prop('selected', true);
		$('#edit-modal').modal('show');
	}).on('click', '.btn-delete', function() {
		text = "确定要删除成本区间分类  " + $(this).attr('data-name') + '  吗？';
		$('#delete-modal-label').text(text);
		$('#delete-modal').attr('data-id', $(this).attr('data-id'));
		$('#delete-modal').modal('show');
	});

	$('#delete-confirm-btn').on('click', function() {
		url = "{{ U('Backend/Manage/deleteCostRange') }}" + '?id=' + $('#delete-modal').attr('data-id');
		$('#delete-form').attr('action', url).submit();
	});
});
</script>
@endsection