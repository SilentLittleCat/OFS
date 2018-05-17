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
					<h5>{{ '订单配送修改记录&nbsp;&nbsp;&nbsp;&nbsp;订单号：' . sprintf("%013d", $order->id) }}</h5>
					<div class="ibox-tools">
						<a class="collapse-link"> <i class="fa fa-chevron-up"></i>
						</a>
					</div>
				</div>
				<div class="ibox-content">
					<table class="table table-striped table-bordered table-hover dataTable" id="change-info-table">
						<thead>
							<th>配送ID</th>
							<th>操作类型</th>
							<th>操作人</th>
							<th>详细信息</th>
							<th>修改时间</th>
						</thead>
						<tbody>
							@if($records->count() == 0)
								<tr>
									<td colspan="5" class="sg-centered">暂无修改记录！</td>
								</tr>
							@else
								@foreach($records as $record)
									<tr>
										<td>{{ $record->send_id }}</td>
										@if($record->type == 1)
											<td class="primary-color">更换配餐</td>
										@elseif($record->type == 2)
											<td>更换地址</td>
										@elseif($record->type == 3)
											<td class="info-color">延时配送</td>
										@elseif($record->type == 4)
											<td>取消配送</td>
										@elseif($record->type == 5)
											<td class="warning-color">设置为配送中</td>
										@elseif($record->type == 6)
											<td class="danger-color">退款</td>
										@elseif($record->type == 7)
											<td class="danger-color">全部退款</td>
										@elseif($record->type ==8)
											<td class="success-color">已完成</td>
										@else
											<td>无</td>
										@endif
										<td>{{ $record->username }}</td>
										<td>{{ $record->info }}</td>
										<td>{{ $record->updated_at }}</td>
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
@endsection

@section('footer')
<script type="text/javascript">
$(function() {
	
});
</script>
@endsection