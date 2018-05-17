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
					<h5>配送记录</h5>
					<div class="ibox-tools">
						<a class="collapse-link"> <i class="fa fa-chevron-up"></i>
						</a>
					</div>
				</div>
				<div class="ibox-content">
					<div class="row">
						<form class="form-horizontal" method="POST" action="{{ U('Backend/Order/userSendRecord', ['id' => $user->id, 'search' => true]) }}">
							{{ csrf_field() }}

							<div class="form-group">
								<div class="col-sm-2 col-sm-offset-1">
									<select class="form-control" name="type">
										@if(isset($info['type']))
											<option value="0" {{ $info['type'] == 0 ? 'selected' : '' }}>所有</option>
											<option value="1" {{ $info['type'] == 1 ? 'selected' : '' }}>男士餐</option>
											<option value="2" {{ $info['type'] == 2 ? 'selected' : '' }}>女士餐</option>
											<option value="3" {{ $info['type'] == 3 ? 'selected' : '' }}>工作餐</option>
										@else
											<option value="0" selected>所有</option>
											<option value="1">男士餐</option>
											<option value="2">女士餐</option>
											<option value="3">工作餐</option>
										@endif
									</select>
								</div>
								<div class="col-sm-4">
									<div class="input-group">
										@if(isset($info['begin_time']))
											<input type="text" name="begin_time" class="form-control" placeholder="起始时间" id="begin_time" value="{{ $info['begin_time'] }}">
										@else
											<input type="text" name="begin_time" class="form-control" placeholder="起始时间" id="begin_time">
										@endif
										<span class="input-group-addon">到</span>
										@if(isset($info['end_time']))
											<input type="text" name="end_time" class="form-control" placeholder="终止时间" id="end_time" value="{{ $info['end_time'] }}">
										@else
											<input type="text" name="end_time" class="form-control" placeholder="终止时间" id="end_time">
										@endif
									</div>
								</div>
								<div class="col-sm-3">
									@if(isset($info['order_id']))
										<input type="text" name="order_id" class="form-control" placeholder="订单号" id="order_id" value="{{ $info['end_time'] }}">
									@else
										<input type="text" name="order_id" class="form-control" placeholder="订单号" id="order_id">
									@endif
								</div>
								<div class="col-sm-2">
									<button class="btn btn-sm btn-primary" type="submit">筛选</button>
								</div>
							</div>
						</form>
					</div>
                    <table class="table table-striped table-bordered table-hover dataTable" id="order-send-table">
                        <thead>
                            <tr>
                            	<th>来自订单</th>
                                <th>商品</th>
                                <th class="sorting" data-sort="time">配送时间</th>
                                <th>配送地址</th>
                                <th>状态</th>
                                <th>备注</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($records) == 0)
                                <tr>
                                    <td colspan="6" class="sg-centered">暂无配送记录！</td>
                                </tr>
                            @else
                                @foreach($records as $record)
                                    <tr>
                                    	<td>
                                    		{{ sprintf("%013d", $record->order_id) }}
                                    	</td>
                                    	@if($record->type == 1)
                                    		<td>{{ '男士餐' . $record->num . '份' }}</td>
                                    	@elseif($record->type == 2)
                                    		<td>{{ '女士餐' . $record->num . '份' }}</td>
                                    	@else
                                    		<td>{{ '工作餐' . $record->num . '份' }}</td>
                                    	@endif
                                        <td>{{ $record->time }}</td>
                                        <td>{{ $record->address }}</td>
                                        @if($record->status == 0)
                                        	<td>未配送</td>
                                        @elseif($record->status == 1)
                                        	<td style="color: #f2ad50">配送中</td>
                                        @elseif($record->status == 2)
                                        	<td style="color: #57b658">已完成</td>
                                        @elseif($record->status == 3)
                                        	<td style="color: #d8534c">已取消</td>
                                        @else
                                        	<td style="color: #59c1de">已退款</td>
                                        @endif
                                        <td>{{ $record->remarks }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="dataTables_info" id="DataTables_Table_0_info"
                                role="alert" aria-live="polite" aria-relevant="all">每页{{ $records->count() }}条，共{{ $records->lastPage() }}页，总{{ $records->total() }}条。</div>
                        </div>
                        <div class="col-sm-6">
                            <div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_0_paginate">
                                {!! $records->setPath('')->appends(Request::all())->render() !!}
                            </div>
                        </div>
                    </div>
				</div>
			</div>
		</div>
	</div>
</div>
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
});
</script>
@endsection