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
					<h5>活动管理</h5>
					<div class="ibox-tools">
						<a class="collapse-link"> <i class="fa fa-chevron-up"></i>
						</a>
					</div>
				</div>
				<div class="ibox-content">
					<div class="row">
    					<div class="col-sm-4">
    						<form method="GET" action="{{ U('Backend/Activity/index') }}" accept-charset="UTF-8">
        						<div class="input-group">
                                    <input type="text" placeholder="活动名称/ID" name="keyword" class="input-sm form-control" value="{{ $keyword }}"> 
                                    <span class="input-group-btn">
                                    	<button type="submit" class="btn btn-sm btn-primary">搜索</button>
                                    </span>
        						</div>
    						</form>
    					</div>
    					<div class="col-sm-4 pull-right">
    						<div class="btn btn-sm btn-primary pull-right" id="add_new_activity">新增</div>
    					</div>
					</div>
					<table class="table table-striped table-bordered table-hover dataTable" id="activities-table">
						<thead>
							<tr>
								<th>ID</th>
								<th>活动名称</th>
								<th>指定范围</th>
								<th>使用条件</th>
								<th>金额</th>
								<th>启用状态</th>
								<th>开始时间</th>
								<th>结束时间</th>
								<th>操作</th>
							</tr>
						</thead>
						<tbody>
							@if(count($activities) == 0)
								<tr>
                                    <td colspan="9" class="sg-centered">暂无活动！</td>
                                </tr>
                            @else
                            	@foreach($activities as $activity)
                            		<tr>
                            			<td>{{ $activity->id }}</td>
                            			<td>{{ $activity->name }}</td>
                            			@if($activity->type == 0)
                            				<td>通用范围</td>
                            			@elseif($activity->type == 1)
                            				<td>男士餐</td>
                            			@elseif($activity->type == 2)
                            				<td>女士餐</td>
                            			@else
                            				<td>工作餐</td>
                            			@endif
                            			<td>{{ '次数满' . $activity->times . '次' }}</td>
										<td>{{ $activity->money }}</td>
										<td>{{ $activity->status == 0 ? '否' : '是' }}</td>
										<td>{{ $activity->begin_time }}</td>
										<td>{{ $activity->end_time }}</td>
										<td>
											<div class="btn-group">
												<div class="btn btn-sm btn-success btn-edit" data-id="{{ $activity->id }}">编辑</div>
												<div class="btn btn-sm btn-danger btn-delete" data-id="{{ $activity->id }}" data-name="{{ $activity->name }}">删除</div>
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
                                role="alert" aria-live="polite" aria-relevant="all">每页{{ $activities->count() }}条，共{{ $activities->lastPage() }}页，总{{ $activities->total() }}条。</div>
                        </div>
                        <div class="col-sm-6">
                            <div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_0_paginate">
								{{  $activities->links() }}
                            </div>
                        </div>
                    </div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="delete-activity-modal" tabindex="-1" role="dialog" aria-labelledby="delete-activity-modal-label" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="delete-activity-modal-label">
					
				</h4>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default pull-left" data-dismiss="modal">
					取消
				</button>
				<button type="button" class="btn btn-danger" id="delete-activity-confirm-btn">
					删除
				</button>
			</div>
		</div>
	</div>
</div>
<form method="POST" style="display: none;" id="delete-activity-form">
    {{ csrf_field() }}
</form>
@endsection

@section('footer')
<script type="text/javascript">
$(function() {
	$('#add_new_activity').on('click', function() {
		url = "{{ U('Backend/Activity/create') }}";
		window.location = url;
	});

	$('#activities-table').on('click', '.btn-edit', function() {
		url = "{{ U('Backend/Activity/edit') }}" + '?id=' + $(this).attr('data-id');
		window.location = url;
	}).on('click', '.btn-delete', function() {
		$('#delete-activity-modal').attr('data-id', $(this).attr('data-id'));
		label = '确定要删除  ' + $(this).attr('data-name') + '  吗？';
		$('#delete-activity-modal-label').text(label);
		$('#delete-activity-modal').modal('show');
	});

	$('#delete-activity-confirm-btn').on('click', function() {
		url = "{{ U('Backend/Activity/delete') }}" + '?id=' + $('#delete-activity-modal').attr('data-id');
		$('#delete-activity-form').attr('action', url).submit();
	});
});
</script>
@endsection