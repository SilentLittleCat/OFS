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
					<h5>编辑入库信息：{{ $inventory->name }}</h5>
					<div class="ibox-tools">
						<a class="collapse-link"> <i class="fa fa-chevron-up"></i>
						</a>
					</div>
				</div>
				<div class="ibox-content">
					<div class="row">
						<form class="form-horizontal" role="form" method="POST" action="{{ U('Backend/Inventory/updateEnter', ['id' => $inventory->id]) }}" id="edit-form">
							{{ csrf_field() }}

							<div class="form-group">
								<label class="control-label col-sm-2 col-offset-2">使用场景</label>
								<div class="col-sm-6">
									<select class="form-control" name="scene">
						                @foreach($scenes as $scene)
						                    <option value="{{ $scene->name }}" {{ $inventory->scene == $scene->name ? 'selected' : '' }}>{{ $scene->name }}</option>
						                @endforeach
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-2 col-offset-2">二级分类</label>
								<div class="col-sm-6">
									<select class="form-control" name="two_level" id="two-level">
						                @foreach($two_levels as $two_level)
						                    <option value="{{ $two_level->name }}" {{ $inventory->two_level == $two_level->name ? 'selected' : '' }}>{{ $two_level->name }}</option>
						                @endforeach
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-2 col-offset-2">三级分类</label>
								<div class="col-sm-6">
									<select class="form-control" name="three_level" id="three-level">
						                @foreach($three_levels as $three_level)
						                    <option value="{{ $three_level->name }}" class="{{ $inventory->two_level == $three_level->fa_class ? '' : 'sg-hide' }}" data-fa="{{ $three_level->fa_class }}" {{ $inventory->three_level == $three_level->name ? 'selected' : '' }}>{{ $three_level->name }}</option>
						                @endforeach
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-2 col-offset-2">是否进库</label>
								<div class="col-sm-6">
									<label class="radio-inline">
						                <input type="radio" value="1" name="is_in" {{ $inventory->is_in == 1 ? 'checked' : '' }}>是
						            </label>
						            <label class="radio-inline">
						                <input type="radio" value="0" name="is_in" {{ $inventory->is_in == 0 ? 'checked' : '' }}>否
						            </label>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-2 col-offset-2">产品名</label>
								<div class="col-sm-6">
									<input type="text" name="name" class="form-control" value="{{ $inventory->name }}">
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-2 col-offset-2">规格</label>
								<div class="col-sm-6">
									<input type="text" name="standard" class="form-control" value="{{ $inventory->standard }}">
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-2 col-offset-2">单位</label>
								<div class="col-sm-6">
									<input type="text" name="unit" class="form-control" value="{{ $inventory->unit }}">
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-2 col-offset-2">库存量</label>
								<div class="col-sm-6">
									<input type="text" name="num" class="form-control" value="{{ $inventory->num }}">
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-2 col-offset-2">单价</label>
								<div class="col-sm-6">
									<input type="text" name="price" class="form-control" value="{{ $inventory->price }}">
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-2 col-offset-2">总价</label>
								<div class="col-sm-6">
									<input type="text" name="total_money" class="form-control" value="{{ $inventory->total_money }}">
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-2 col-offset-2">折旧年</label>
								<div class="col-sm-6">
						            <select class="form-control" style="width: 120px" name="old_years">
						                <option value="null" {{ $inventory->old_years == '' ? 'selected' : '' }}>无</option>
						                @foreach(dict()->get('inventory', 'old_years') as $key => $val)
						                    <option value="{{ $key }}" {{ $inventory->old_years == $key ? 'selected' : '' }}>{{ $val }}</option>
						                @endforeach
						            </select>
								</div>
							</div>
                            <div class="form-group">
                                <label class="control-label col-sm-2 col-offset-2">采购时间</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="time" id="enter_time" value="{{ $inventory->last_enter_time }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2 col-offset-2">采购人</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" name="username" value="{{ $inventory->username }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-2 col-offset-2">备注</label>
                                <div class="col-sm-6">
                                    <textarea rows="3" class="form-control" name="remarks">{{ $inventory->remarks }}</textarea>
                                </div>
                            </div>
                            <div class="form-group" id="operations">
                            	<div class="col-sm-4 col-sm-offset-2">
                            		<div class="btn btn-default btn-cancel">取消</div>
                            		<div class="btn btn-primary btn-save">保存</div>
                            		<div class="btn btn-success btn-reset">重置</div>
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
	$('#enter_time').datepicker();

	$('#two-level').change(function () {
		value = $(this).val();
    	$('#three-level').find('option').addClass('sg-hide');
    	$('#three-level').find('option[data-fa=' + value + ']').removeClass('sg-hide');
    	$('#three-level').find('option[data-fa=' + value + ']')[0].selected = true;
	});

	$('#operations').on('click', '.btn-cancel', function() {
		url = "{{ U('Backend/Inventory/enterIndex') }}";
		window.location = url;
	}).on('click', '.btn-reset', function() {
		url = "{{ U('Backend/Inventory/editEnter', ['id' => $inventory->id]) }}";
		window.location = url;
	}).on('click', '.btn-save', function() {
		$('#edit-form').submit();
	});
});
</script>
@endsection