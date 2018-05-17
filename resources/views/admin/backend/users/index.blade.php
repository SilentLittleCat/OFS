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
					<h5>用户列表</h5>
					<div class="ibox-tools">
						<a class="collapse-link"> <i class="fa fa-chevron-up"></i>
						</a>
					</div>
				</div>
				<div class="ibox-content">
					<div class="row">
    					<div class="col-sm-4">
    						<form method="GET" action="{{ U('Backend/User/index') }}" accept-charset="UTF-8">
        						<div class="input-group">
                                    <input type="text" placeholder="请输入ID/姓名/电话" name="keyword" class="input-sm form-control" value="{{ $keyword }}"> 
                                    <span class="input-group-btn">
                                    	<button type="submit" class="btn btn-sm btn-primary">搜索</button>
                                    </span>
        						</div>
    						</form>
    					</div>
					</div>
					<table class="table table-striped table-bordered table-hover dataTable" id="users-table">
						<thead>
							<tr>
								<th class="sorting" data-sort="id">用户ID</th>
								<th>微信昵称</th>
								<th>真实姓名</th>
								<th>性别</th>
								<th>电话</th>
								<th>收货地址</th>
								<th class="sorting" data-sort="created_at">注册时间</th>
								<th>剩余总次数</th>
								<th>消费总次数</th>
								<th>余额</th>
								<th>操作</th>
							</tr>
						</thead>
						<tbody>
							@if(count($users) == 0)
								<tr>
									<td colspan="9" class="sg-centered">暂无用户！</td>
								</tr>
							@else
								@foreach($users as $user)
									<tr>
										<td>{{ $user->id }}</td>
										<td>{{ $user->wechat_name }}</td>
										<td>{{ $user->real_name }}</td>
										<td>{{ $user->gender == 1 ? '男' : '女' }}</td>
										<td>{{ $user->tel }}</td>
										<td>{{ $user->address }}</td>
										<td>{{ $user->created_at }}</td>
										<td>{{ $user->man_remain_times + $user->woman_remain_times + $user->work_remain_times . '次' }}</td>
										<td>{{ $user->man_times + $user->woman_times + $user->work_times . '次' }}</td>
										<td>{{ $user->remain_money . '元' }}</td>
										<td>
											<div class="btn-group">
												<div class="btn btn-sm btn-danger btn-coupon" data-id="{{ $user->id }}">优惠券</div>
												<div class="btn btn-sm btn-info btn-detail" data-id="{{ $user->id }}">详情</div>
												<div class="btn btn-sm btn-success btn-edit" data-id="{{ $user->id }}">编辑</div>
												<div class="btn btn-sm btn-danger btn-delete" data-id="{{ $user->id }}" data-name="{{ $user->real_name }}">删除</div>
												<div class="btn btn-sm btn-primary order-record" data-id="{{ $user->id }}">订单</div>
												<div class="btn btn-sm btn-warning send-record" data-id="{{ $user->id }}">配送</div>
												<div class="btn btn-sm btn-success pay-money" data-id="{{ $user->id }}">充值</div>
												<div class="btn btn-sm btn-info back-money" data-id="{{ $user->id }}">提现</div>
												<div class="btn btn-sm btn-primary weight-record" data-id="{{ $user->id }}">体重</div>
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
								 role="alert" aria-live="polite" aria-relevant="all">每页{{ $users->count() }}条，共{{ $users->lastPage() }}页，总{{ $users->total() }}条。</div>
						</div>
						<div class="col-sm-6">
							<div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_0_paginate">
								{{ $users->links() }}
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="delete-user-modal" tabindex="-1" role="dialog" aria-labelledby="delete-user-modal-label" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="delete-user-modal-label">
					
				</h4>
			</div>
			<div class="modal-body">
				注意！！！该操作会删除该用户的个人信息，优惠券，点餐记录，配送记录等所有信息！
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default pull-left" data-dismiss="modal">
					取消
				</button>
				<button type="button" class="btn btn-danger" id="delete-user-confirm-btn">
					删除
				</button>
			</div>
		</div>
	</div>
</div>
<form method="POST" style="display: none;" id="delete-usre-form">
    {{ csrf_field() }}
</form>
@endsection

@section('footer')
<script type="text/javascript">
$(function() {
	$('#users-table').on('click', '.btn-edit', function() {
		window.location = "{{ U('Backend/User/edit') }}" + '?id=' + $(this).attr('data-id');
 	}).on('click', '.btn-coupon', function() {
 		window.location = "{{ U('Backend/Coupon/userIndex') }}" + '?id=' + $(this).attr('data-id');
 	}).on('click', '.btn-detail', function() {
 		window.location = "{{ U('Backend/User/show') }}" + '?id=' + $(this).attr('data-id');
 	}).on('click', '.btn-delete', function() {
 		text = '确定要删除用户 ' + $(this).attr('data-name') + ' 吗？注意该操作为危险操作！';
 		$('#delete-user-modal .modal-title').text(text);
 		$('#delete-user-modal').attr('data-id', $(this).attr('data-id')).modal('show');
 	}).on('click', '.order-record', function() {
 		window.location = "{{ U('Backend/Order/userIndex') }}" + '?id=' + $(this).attr('data-id');
 	}).on('click', '.send-record', function() {
 		window.location = "{{ U('Backend/Order/userSendRecord') }}" + '?id=' + $(this).attr('data-id');
 	}).on('click', '.pay-money', function() {
 		window.location = "{{ U('Backend/PayMoney/index') }}" + '?user_id=' + $(this).attr('data-id');
 	}).on('click', '.back-money', function() {
 		window.location = "{{ U('Backend/Money/index') }}" + '?user_id=' + $(this).attr('data-id');
 	}).on('click', '.weight-record', function() {
 		window.location = "{{ U('Backend/User/weight') }}" + '?user_id=' + $(this).attr('data-id');
 	});

 	$('#delete-user-confirm-btn').on('click', function() {
 		url = "{{ U('Backend/User/delete') }}" + '?id=' + $('#delete-user-modal').attr('data-id');
 		$('#delete-usre-form').attr('action', url).submit();
 	});
});
</script>
@endsection