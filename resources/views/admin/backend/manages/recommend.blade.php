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
					<h5>推荐有礼设置</h5>
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
					<table class="table table-striped table-bordered table-hover dataTable" id="recommends-table">
						<thead>
							<tr>
								<th>名称</th>
								<th>条件</th>
								<th>活动信息</th>
								<th>简介</th>
								<th>启用状态</th>
								<th>操作</th>
							</tr>
						</thead>
						<tbody>
							@if(count($recommends) == 0)
								<tr>
									<td colspan="6" class="sg-centered">暂无推荐有礼！</td>
								</tr>
							@else
								@foreach($recommends as $recommend)
									<tr>
										<td>{{ $recommend->name }}</td>
										<td>{{ '消费满' . $recommend->condition . '元' }}</td>
										<td>{{ '好友订单返现' . $recommend->back_money . '%' }}</td>
										<td>{{ $recommend->info }}</td>
										@if($recommend->status == 0)
											<td class="danger-color">禁用</td>
										@else
											<td class="success-color">启用</td>
										@endif
										<td>
											<div class="btn-group">
												<div class="btn btn-sm btn-primary btn-use {{ $recommend->status == 0 ? '' : 'disabled' }}" data-id="{{ $recommend->id }}">启用</div>
												<div class="btn btn-sm btn-default btn-forbit {{ $recommend->status == 1 ? '' : 'disabled' }}" data-id="{{ $recommend->id }}">禁用</div>
												<div class="btn btn-sm btn-success btn-edit" data-id="{{ $recommend->id }}" data-name="{{ $recommend->name }}" data-status="{{ $recommend->status }}" data-info="{{ $recommend->info }}" data-condition="{{ $recommend->condition }}" data-back-money="{{ $recommend->back_money }}">编辑</div>
												<div class="btn btn-sm btn-danger btn-delete" data-id="{{ $recommend->id }}" data-name="{{ $recommend->name }}">删除</div>
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
					添加活动有礼
				</h4>
			</div>
			<div class="modal-body">
				<form class="form-horizontal" id="add-form" method="POST" action="{{ U('Backend/Manage/storeRecommend') }}">
					{{ csrf_field() }}

					<div class="form-group">
						<label class="col-sm-2 control-label">名称</label>
						<div class="col-sm-10">
							<input type="text" name="name" class="form-control">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">条件</label>
						<div class="col-sm-10">
							<input type="number" name="condition" placeholder="消费金额满多少元" class="form-control" name="condition">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">返现百分比</label>
						<div class="col-sm-10">
							<input type="number" name="back_money" class="form-control" placeholder="请输入整数">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">活动简介</label>
						<div class="col-sm-10">
							<textarea class="form-control" rows="3" name="info"></textarea>
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
				<form class="form-horizontal" id="edit-form" method="POST" action="{{ U('Backend/Manage/updateRecommend') }}">
					{{ csrf_field() }}

					<input type="text" name="id" style="display: none" id="recommend-id">
					<div class="form-group">
						<label class="col-sm-2 control-label">名称</label>
						<div class="col-sm-10">
							<input type="text" name="name" class="form-control" id="recommend-name">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">条件</label>
						<div class="col-sm-10">
							<input type="number" name="condition" placeholder="消费金额满多少元" class="form-control" name="condition" id="recommend-condition">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">返现百分比</label>
						<div class="col-sm-10">
							<input type="number" name="back_money" class="form-control" placeholder="请输入整数" id="recommend-back-money">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">活动简介</label>
						<div class="col-sm-10">
							<textarea class="form-control" rows="3" name="info" id="recommend-info"></textarea>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">启用状态</label>
						<div class="col-sm-10" id="recommend-status">
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

	$('#recommends-table').on('click', '.btn-use', function() {
		url = "{{ U('Backend/Manage/changeRecommendStatus') }}" + '?id=' + $(this).attr('data-id') + '&status=1';
		$('#change-status-form').attr('action', url).submit();
	}).on('click', '.btn-forbit', function() {
		url = "{{ U('Backend/Manage/changeRecommendStatus') }}" + '?id=' + $(this).attr('data-id') + '&status=0';
		$('#change-status-form').attr('action', url).submit();
	}).on('click', '.btn-edit', function() {
		$('#recommend-id').val($(this).attr('data-id'));
		$('#recommend-name').val($(this).attr('data-name'));
		$('#recommend-condition').val($(this).attr('data-condition'));
		$('#recommend-back-money').val($(this).attr('data-back-money'));
		$('#recommend-info').val($(this).attr('data-info'));
		status = $(this).attr('data-status');
		// $('#class-status input').attr('checked', false);
		$('#recommend-status input[value=' + status + ']').prop('checked', true);
		$('#edit-modal').modal('show');
	}).on('click', '.btn-delete', function() {
		text = "确定要删除成本区间分类  " + $(this).attr('data-name') + '  吗？';
		$('#delete-modal-label').text(text);
		$('#delete-modal').attr('data-id', $(this).attr('data-id'));
		$('#delete-modal').modal('show');
	});

	$('#delete-confirm-btn').on('click', function() {
		url = "{{ U('Backend/Manage/deleteRecommend') }}" + '?id=' + $('#delete-modal').attr('data-id');
		$('#delete-form').attr('action', url).submit();
	});
});
</script>
@endsection