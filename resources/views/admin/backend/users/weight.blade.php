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
					<h5>体重记录：&nbsp;&nbsp;&nbsp;&nbsp;{{ $user->wechat_name }}</h5>
					<div class="ibox-tools">
						<a class="collapse-link"> <i class="fa fa-chevron-up"></i>
						</a>
					</div>
				</div>
				<div class="ibox-content">
					<div class="row">
						<form class="form-horizontal" method="POST" action="{{ U('Backend/User/weight', ['search' => true]) }}">
							{{ csrf_field() }}

							<div class="form-group">
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
									<button class="btn btn-sm btn-primary" type="submit">筛选</button>
								</div>
							</div>
						</form>
					</div>
					<table class="table table-striped table-bordered table-hover dataTable" id="weight-table">
						<thead>
                            <tr>
                                <th>时间</th>
                                <th>体重</th>
                            </tr>
                        </thead>
                        <tbody>
                        	@if(count($weights) == 0)
                        		<tr>
                                    <td colspan="2" class="sg-centered">暂无体重记录！</td>
                                </tr>
                        	@else
								@foreach($weights as $weight)
                                    <tr>
                                        <td>{{ $weight->created_at }}</td>
                                        <td>{{ $weight->weight . 'KG' }}</td>
                                    </tr>
                                @endforeach
                        	@endif
                        </tbody>
					</table>
					<div class="row">
                        <div class="col-sm-6">
                            <div class="dataTables_info" id="DataTables_Table_0_info"
                                role="alert" aria-live="polite" aria-relevant="all">每页{{ $weights->count() }}条，共{{ $weights->lastPage() }}页，总{{ $weights->total() }}条。</div>
                        </div>
                        <div class="col-sm-6">
                            <div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_0_paginate">
                                {!! $weights->setPath('')->appends(Request::all())->render() !!}
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