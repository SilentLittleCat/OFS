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
					<h5>更改记录</h5>
					<div class="ibox-tools">
						<a class="collapse-link"> <i class="fa fa-chevron-up"></i>
						</a>
					</div>
				</div>
				<div class="ibox-content">
					<div class="row">
						<form class="form-horizontal" method="POST" action="{{ U('Backend/Order/amendRecord', ['id' => $user->id, 'search' => true]) }}">
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
								<div class="col-sm-5">
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
								<div class="col-sm-2">
									<button class="btn btn-sm btn-primary" type="submit">筛选</button>
								</div>
							</div>
						</form>
					</div>
                    <table class="table table-striped table-bordered table-hover dataTable" id="user-amend-table">
                        <thead>
                            <tr>
                                <th>餐名</th>
                                <th>原有次数</th>
                                <th>修改次数</th>
                                <th>修改后次数</th>
                                <th>修改原因</th>
                                <th class="sorting" data-sort="created_at">修改时间</th>
                                <th>操作人</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($records) == 0)
                                <tr>
                                    <td colspan="7" class="sg-centered">暂无修改记录！</td>
                                </tr>
                            @else
                                @foreach($records as $record)
                                    <tr>
                                    	@if($record->type == 1)
                                    		<td>男士餐</td>
                                    	@elseif($record->type == 2)
                                    		<td>女士餐</td>
                                    	@else
                                    		<td>工作餐</td>
                                    	@endif
                                        <td>{{ $record->origin_times }}</td>
                                        @if($record->amend_times > 0)
                                        	<td style="color: green">{{ '+' . $record->amend_times }}</td>
                                        @else
                                        	<td style="color: red">{{ $record->amend_times }}</td>
                                        @endif
                                        <td>{{ $record->origin_times + $record->amend_times }}</td>
                                        <td>{{ $record->reason }}</td>
                                        <td>{{ $record->created_at }}</td>
                                        <td>{{ $record->amend_user_name }}</td>
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