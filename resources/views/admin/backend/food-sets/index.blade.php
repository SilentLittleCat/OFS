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
					<h5>套餐管理</h5>
					<div class="ibox-tools">
						<a class="collapse-link"> <i class="fa fa-chevron-up"></i>
						</a>
					</div>
				</div>
				<div class="ibox-content">
					<table class="table table-striped table-bordered table-hover dataTable" id="food_sets-table">
						<thead>
							<tr>
								<th>套餐种类</th>
								<th>分类</th>
								<th>金额</th>
								<th>启用状态</th>
								<th>排序</th>
								<th>图片</th>
								<th>操作</th>
							</tr>
						</thead>
						<tbody>
							@if(count($food_sets) == 0)
								<tr>
									<td colspan="6" class="sg-centered">暂无套餐！</td>
								</tr>
							@else
								@foreach($food_sets as $food_set)
									<tr>
										@if($food_set->kind == 0)
											<td>周餐（5天）</td>
										@else
											<td>月餐（20天）</td>
										@endif
										@if($food_set->type == 1)
											<td>男士餐</td>
										@elseif($food_set->type == 2)
											<td>女士餐</td>
										@else
											<td>工作餐</td>
										@endif
										<td>{{ $food_set->money }}</td>
										@if($food_set->status == 0)
											<td class="danger-color">禁用</td>
										@else
											<td class="success-color">启用</td>
										@endif
										<td>{{ $food_set->sort }}</td>
										<td>
											<img src="{{ $food_set->poster }}" class="img-thumbnail" width="200px">
										</td>
										<td>
											<div class="btn-group">
												<div class="btn btn-sm btn-success btn-edit" data-id="{{ $food_set->id }}">编辑</div>
												<div class="btn btn-sm btn-primary change-status {{ $food_set->status == 0 ? '' : 'disabled' }}" data-id="{{ $food_set->id }}" data-status="1">启用</div>
												<div class="btn btn-sm btn-danger change-status {{ $food_set->status == 1 ? '' : 'disabled' }}" data-id="{{ $food_set->id }}" data-status="0">禁用</div>
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
<form class="sg-hide" method="POST" id="change-status-form">
	{{ csrf_field() }}
</form>
@endsection

@section('footer')
<script type="text/javascript">
$(function() {
	$('#food_sets-table').on('click', '.btn-edit', function() {
		url = "{{ U('Backend/FoodSet/edit') }}" + '?id=' + $(this).attr('data-id');
		window.location = url;
	}).on('click', '.change-status', function() {
		url = "{{ U('Backend/FoodSet/changeStatus') }}"  + '?id=' + $(this).attr('data-id') + '&status=' + $(this).attr('data-status');
		$('#change-status-form').attr('action', url).submit();
	});
});
</script>
@endsection