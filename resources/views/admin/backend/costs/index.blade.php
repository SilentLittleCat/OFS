@extends('admin.layout')

@section('header')
<style type="text/css">
	.input-sm {
		font-size: 0.8em;
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
					<h5>运营成本管理</h5>
					<div class="ibox-tools">
						<a class="collapse-link"> <i class="fa fa-chevron-up"></i>
						</a>
					</div>
				</div>
				<div class="ibox-content">
					<div class="row">
						<form class="form-horizontal" method="POST" action="{{ U('Backend/Cost/index') }}">
							{{ csrf_field() }}
							<div class="form-group">
								<div class="col-sm-2" style="margin-left: 15px">
									<select class="form-control input-sm" name="scene">
										<option value="-1">全部</option>
										@foreach($scenes as $scene)
											@if(isset($info['scene']) && $info['scene'] == $scene->name)
												<option value="{{ $scene->name }}" selected>{{ $scene->name }}</option>
											@else
												<option value="{{ $scene->name }}">{{ $scene->name }}</option>
											@endif
										@endforeach
									</select>
								</div>
								<div class="col-sm-2">
									<select class="form-control input-sm" name="type">
										<option value="-1">全部</option>
										@foreach($cost_classes as $cost_class)
											@if(isset($info['type']) && $info['type'] == $cost_class->name)
												<option value="{{ $cost_class->name }}" selected>{{ $cost_class->name }}</option>
											@else
												<option value="{{ $cost_class->name }}">{{ $cost_class->name }}</option>
											@endif
										@endforeach
									</select>
								</div>
								<div class="col-sm-2">
									<select class="form-control input-sm" name="time">
										<option value="-1">全部</option>
										@foreach(dict()->get('costs', 'times') as $key => $val)
											@if(isset($info['time']) && $info['time'] == $val)
												<option value="{{ $val }}" selected>{{ $val }}</option>
											@else
												<option value="{{ $val }}">{{ $val }}</option>
											@endif
										@endforeach
									</select>
								</div>
								<div class="col-sm-2">
									@if(isset($info['begin_time']))
										<input type="text" name="begin_time" class="form-control input-sm" placeholder="起始时间" id="begin_time" value="{{ $info['begin_time'] }}">
									@else
										<input type="text" name="begin_time" class="form-control input-sm" placeholder="起始时间" id="begin_time">
									@endif
								</div>
								<div class="col-sm-2">
									@if(isset($info['end_time']))
										<input type="text" name="end_time" class="form-control input-sm" placeholder="终止时间" id="end_time" value="{{ $info['end_time'] }}">
									@else
										<input type="text" name="end_time" class="form-control input-sm" placeholder="终止时间" id="end_time">
									@endif
								</div>
								<div class="col-sm-2" style="margin-left: -15px;">
									<button type="submit" class="btn btn-sm btn-primary">筛选</button>
									<div class="btn btn-sm btn-info" id="add-new-cost">新增</div>
								</div>
							</div>
						</form>
					</div>
					<div class="sg-divider-bold"></div>
					@if($cost_classes->count() != 0)
						<table class="table table-striped table-bordered table-hover dataTable" id="costs-stat">
							<thead>
								<tr>
									<th>费用总计</th>
									@foreach($cost_classes as $cost_class)
										<th>{{ $cost_class->name }}</th>
									@endforeach
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>{{ $total_money }}</td>
									@foreach($cost_classes as $cost_class)
										<td>{{ $cost_class->total_money }}</td>
									@endforeach
								</tr>
							</tbody>
						</table>
						<div class="sg-divider"></div>
					@endif
                    <table class="table table-striped table-bordered table-hover dataTable" id="costs-table">
                        <thead>
                            <tr>
                                <th>使用场景</th>
                                <th>费用类型</th>
                                <th>单位时间</th>
                                <th>金额</th>
                                <th>支出时间</th>
                                <th>添加人</th>
                                <th>备注</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($costs) == 0)
                                <tr>
                                    <td colspan="8" class="sg-centered">暂无运营成本！</td>
                                </tr>
                            @else
                                @foreach($costs as $cost)
                                    <tr>
                                        <td>{{ $cost->scene }}</td>
                                        <td>{{ $cost->type }}</td>
                                        <td>{{ $cost->time }}</td>
                                        <td>{{ $cost->money }}</td>
                                        <td>{{ $cost->add_date }}</td>
                                        <td>{{ $cost->username }}</td>
                                        <td>{{ $cost->remarks }}</td>
                                        <td>
                                        	<div class="btn-group">
                                        		<div class="btn btn-sm btn-success btn-edit" data-id="{{ $cost->id }}">编辑</div>
                                        		<div class="btn btn-sm btn-danger btn-delete" data-id="{{ $cost->id }}" data-name="{{ $cost->type }}">删除</div>
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
                                role="alert" aria-live="polite" aria-relevant="all">每页{{ $costs->count() }}条，共{{ $costs->lastPage() }}页，总{{ $costs->total() }}条。</div>
                        </div>
                        <div class="col-sm-6">
                            <div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_0_paginate">
								{{  $costs->links() }}
                            </div>
                        </div>
                    </div>
				</div>
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
					确认
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
	$('#begin_time').datepicker({
		autoclose: true
	});
	$('#end_time').datepicker({
		autoclose: true
	});

	$('#add-new-cost').on('click', function() {
		url = "{{ U('Backend/Cost/create') }}";
		window.location = url;
	});

	$('#costs-table').on('click', '.btn-edit', function() {
		url = "{{ U('Backend/Cost/edit') }}" + '?id=' + $(this).attr('data-id');
		window.location = url;
	}).on('click', '.btn-delete', function() {
		$('#delete-modal').attr('data-id', $(this).attr('data-id'));
		$('#delete-modal-label').text('确定删除 ' + $(this).attr('data-name') + ' 这条记录吗？');
		$('#delete-modal').modal('show');
	});

	$('#delete-confirm-btn').on('click', function() {
		url = "{{ U('Backend/Cost/delete') }}" + '?id=' + $('#delete-modal').attr('data-id');
		$('#delete-form').attr('action', url).submit();
	});
});
</script>
@endsection