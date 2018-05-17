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
					<h5>用户优惠券&nbsp;&nbsp;&nbsp;<span>用户名：{{ $user->real_name }}</span></h5>
					<div class="ibox-tools">
						<a class="collapse-link"> <i class="fa fa-chevron-up"></i>
						</a>
					</div>
				</div>
				<div class="ibox-content">
					<div class="row">
    					<div class="col-sm-4">
    						<form method="GET" action="{{ U('Backend/Coupon/userIndex') }}" accept-charset="UTF-8">
    							<div class="input-group" style="display: none;">
    								<input type="text" placeholder="优惠券名称/ID" name="id" class="input-sm form-control" value="{{ $user->id }}"> 
    							</div>
        						<div class="input-group">
                                    <input type="text" placeholder="优惠券名称/ID" name="keyword" class="input-sm form-control" value="{{ $keyword }}"> 
                                    <span class="input-group-btn">
                                    	<button type="submit" class="btn btn-sm btn-primary">搜索</button>
                                    </span>
        						</div>
    						</form>
    					</div>
    					<div class="col-sm-4 pull-right">
    						<div class="btn btn-sm btn-primary pull-right" id="add-coupon">新增</div>
    					</div>
					</div>
					<table class="table table-striped table-bordered table-hover dataTable" id="coupons-table">
						<thead>
							<tr>
								<th class="sorting" data-sort="id">ID</th>
								<th>优惠券名称</th>
								<th>优惠券类型</th>
								<th>使用条件</th>
								<th>金额</th>
								<th>使用状态</th>
								<th>发放总量</th>
								<th>使用总量</th>
								<th class="sorting" data-sort="begin_time">开始时间</th>
								<th class="sorting" data-sort="end_time">结束时间</th>
								<th>操作</th>
							</tr>
						</thead>
						<tbody>
							@if(count($coupons) == 0)
								<tr>
									<td colspan="11" class="sg-centered">暂无优惠券！</td>
								</tr>
							@else
								@foreach($coupons as $coupon)
									<tr>
										<td>{{ $coupon->id }}</td>
										<td>{{ $coupon->name }}</td>
										@if($coupon->type == 0)
											<td>通用范围</td>
										@elseif($coupon->type == 1)
											<td>男士餐</td>
										@elseif($coupon->type == 2)
											<td>女士餐</td>
										@elseif($coupon->type == 3)
											<td>工作餐</td>
										@else
											<td>优惠券模板</td>
										@endif
										<td>{{ '购物满' . $coupon->condition . '元' }}</td>
										<td>{{ $coupon->money . '元' }}</td>
										@if($coupon->pivot->status == 1)
											<td style="color: red">已使用</td>
										@else
											<td style="color: green">未使用</td>
										@endif
										<td>{{ $coupon->total }}</td>
										<td>{{ $coupon->use_total }}</td>
										<td>{{ $coupon->begin_time }}</td>
										<td>{{ $coupon->end_time }}</td>
										<td>
											<div class="btn-group">
												<div class="btn btn-sm btn-success btn-edit" data-id="{{ $coupon->id }}">编辑</div>
												<div class="btn btn-sm btn-danger btn-delete" data-id="{{ $coupon->pivot->id }}" data-name="{{ $coupon->name }}">删除</div>
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
								role="alert" aria-live="polite" aria-relevant="all">每页{{ $coupons->count() }}条，共{{ $coupons->lastPage() }}页，总{{ $coupons->total() }}条。</div>
						</div>
						<div class="col-sm-6">
							<div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_0_paginate">
								{!! $coupons->setPath('')->appends(Request::all())->render() !!}
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="delete-coupon-modal" tabindex="-1" role="dialog" aria-labelledby="delete-coupon-modal-label" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="delete-coupon-modal-label">
					确定要删除用户&nbsp;&nbsp;{{ $user->real_name }}&nbsp;&nbsp;的以下优惠券吗？
				</h4>
			</div>
			<div class="modal-body">
				
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default pull-left" data-dismiss="modal">
					取消
				</button>
				<button type="button" class="btn btn-danger" id="delete-coupon-confirm-btn">
					确认
				</button>
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
					添加优惠券
				</h4>
			</div>
			<div class="modal-body">
				<form method="POST" class="form-horizontal" action="{{ U('Backend/Coupon/addForUser', ['user_id' => $user->id]) }}" id="add-form">
					{{ csrf_field() }}

					<div class="form-group">
						<label class="col-sm-3 control-label">选择优惠券</label>
						<div class="col-sm-9">
							<select class="form-control" name="coupon_id">
								@foreach($select_coupons as $coupon)
									<option value="{{ $coupon->id }}">{{ $coupon->text }}</option>
								@endforeach
							</select>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default pull-left" data-dismiss="modal">
					取消
				</button>
				<button type="button" class="btn btn-success" id="add-confirm-btn">
					确认
				</button>
			</div>
		</div>
	</div>
</div>
<form method="POST" style="display: none;" id="delete-coupon-form">
    {{ csrf_field() }}
</form>
@endsection

@section('footer')
<script type="text/javascript">
$(function() {
	$('#coupons-table').on('click', '.btn-edit', function() {
		window.location = "{{ U('Backend/Coupon/edit') }}" + '?id=' + $(this).attr('data-id');
	}).on('click', '.btn-delete', function() {
		$('#delete-coupon-modal').attr('data-id', $(this).attr('data-id'));
		$('#delete-coupon-modal .modal-body').text($(this).attr('data-name'));
		$('#delete-coupon-modal').modal('show');
	});

	$('#delete-coupon-confirm-btn').on('click', function() {
		url = "{{ U('Backend/Coupon/userDelete') }}" + '?id=' + $('#delete-coupon-modal').attr('data-id') + "&user_id=" + "{{ $user->id }}";
		$('#delete-coupon-form').attr('action', url).submit();
	});

	$('#add-coupon').on('click', function() {
		$('#add-modal').modal('show');
		// window.location = "{{ U('Backend/Coupon/create') }}";
	});

	$('#add-confirm-btn').on('click', function() {
		$('#add-form').submit();
	});
});
</script>
@endsection