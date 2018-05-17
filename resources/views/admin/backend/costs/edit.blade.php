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
					<h5>编辑运营成本：{{ $cost->type }}</h5>
					<div class="ibox-tools">
						<a class="collapse-link"> <i class="fa fa-chevron-up"></i>
						</a>
					</div>
				</div>
				<div class="ibox-content">
					<div class="row">
						<form class="form-horizontal" method="POST" action="{{ U('Backend/Cost/update', ['id' => $cost->id]) }}" id="edit-form">
							{{ csrf_field() }}

							<div class="form-group">
								<label class="col-sm-2 col-sm-offset-2 control-label">使用场景</label>
								<div class="col-sm-6">
									<select class="form-control" name="scene">
										@foreach($scenes as $scene)
											<option value="{{ $scene->name }}" {{ $scene->name == $cost->name ? 'selected' : '' }}>
												{{ $scene->name }}
											</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 col-sm-offset-2 control-label">费用类型</label>
								<div class="col-sm-6">
									<select class="form-control" name="type">
										@foreach($cost_classes as $cost_class)
											<option value="{{ $cost_class->name }}" {{ $cost_class->name == $cost->type ? 'selected' : '' }}>
												{{ $cost_class->name }}
											</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 col-sm-offset-2 control-label">单位时间</label>
								<div class="col-sm-6">
									<select class="form-control" name="time">
										@foreach(dict()->get('costs', 'times') as $key => $val)
											<option value="{{ $cost_class->name }}" {{ $val == $cost->time ? 'selected' : '' }}>
												{{ $val }}
											</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 col-sm-offset-2 control-label">金额</label>
								<div class="col-sm-6">
									<input type="text" name="money" class="form-control" value="{{ $cost->money }}">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 col-sm-offset-2 control-label">支出时间</label>
								<div class="col-sm-6">
									<input type="text" name="add_date" class="form-control" value="{{ $cost->add_date }}" id="add-date">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 col-sm-offset-2 control-label">添加人</label>
								<div class="col-sm-6">
									<input type="text" name="username" class="form-control" value="{{ $cost->username }}">
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-2 col-sm-offset-2 control-label">备注信息</label>
								<div class="col-sm-6">
									<textarea class="form-control" name="remarks">{{ $cost->remarks }}</textarea>
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-6 col-sm-offset-4">
									<div class="btn btn-sm btn-default btn-reset">重置</div>
									<div class="btn btn-sm btn-primary btn-save">保存</div>
									<div class="btn btn-sm btn-success btn-cancel">取消</div>
								</div>
							</div>
						</form>
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
    $('#add-date').datepicker({
    	autoclose: true
    });

    $('#edit-form').on('click', '.btn-reset', function() {
    	url = "{{ U('Backend/Cost/edit', ['id' => $cost->id]) }}";
    	window.location = url;
    }).on('click', '.btn-save', function() {
    	$('#edit-form').submit();
    }).on('click', '.btn-cancel', function() {
    	url = "{{ U('Backend/Cost/index') }}";
    	window.location = url;
    });
});
</script>
@endsection