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
					<h5>商品列表</h5>
					<div class="ibox-tools">
						<a class="collapse-link"> <i class="fa fa-chevron-up"></i>
						</a>
					</div>
				</div>
				<div class="ibox-content">
					<table class="table table-striped table-bordered table-hover dataTable" id="foods-table">
						<thead>
							<tr>
								<th>商品名</th>
								<th>单价</th>
								<th>多人价格</th>
								<th>海报</th>
								<th>简介</th>
								<th>添加时间</th>
								<th>状态</th>
								<th>操作</th>
							</tr>
						</thead>
						<tbody>
							@if(count($foods) == 0)
								<tr>
									<td colspan="8" class="sg-centered">暂无商品！</td>
								</tr>
							@else
								@foreach($foods as $food)
									<tr>
										<td>{{ $food->name }}</td>
										<td>{{ $food->money }}</td>
										@if($food->name == '工作餐')
											<td>{{ $food->a_min . '到' . $food->a_max . '人：' . $food->a_price  }}<br>{{ $food->b_min . '人以上：' . $food->b_price }}</td>
										@else
											<td>无</td>
										@endif
										@if($food->poster == null || $food->poster == '')
											<td>无</td>
										@else
											<td>
												<img src="{{ $food->poster }}" class="img-thumbnail" width="200px">
											</td>
										@endif
										<td>{{ $food->info }}</td>
										<td>{{ $food->created_at }}</td>
										@if($food->status == 0)
											<td class="danger-color">下架</td>
										@else
											<td class="success-color">上架</td>
										@endif
										<td>
											<div class="btn-group">
												<div class="btn btn-sm btn-success btn-edit" data-id="{{ $food->id }}">编辑</div>
												<div class="btn btn-sm btn-primary change-status {{ $food->status == 0 ? '' : 'disabled' }}" data-id="{{ $food->id }}" data-status="1">上架</div>
												<div class="btn btn-sm btn-danger change-status {{ $food->status == 1 ? '' : 'disabled' }}" data-id="{{ $food->id }}" data-status="0">下架</div>
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
	$('#foods-table').on('click', '.btn-edit', function() {
		url = "{{ U('Backend/Food/edit') }}" + '?id=' + $(this).attr('data-id');
		window.location = url;
	}).on('click', '.change-status', function() {
		url = "{{ U('Backend/Food/changeStatus') }}"  + '?id=' + $(this).attr('data-id') + '&status=' + $(this).attr('data-status');
		$('#change-status-form').attr('action', url).submit();
	});
});
</script>
@endsection