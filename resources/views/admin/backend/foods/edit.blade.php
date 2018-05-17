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
					<h5>编辑商品</h5>
					<div class="ibox-tools">
						<a class="collapse-link"> <i class="fa fa-chevron-up"></i>
						</a>
					</div>
				</div>
				<div class="ibox-content">
					<div class="row">
						<form class="form-horizontal" method="POST" action="{{ U('Backend/Food/update') }}" id="food-form">
							{{ csrf_field() }}

							<input type="text" name="id" value="{{ $food->id }}" class="sg-hide">
							<div class="form-group">
								<label class="control-label col-sm-2 col-sm-offset-1">商品名</label>
								<div class="col-sm-6">
									<div class="form-control">{{ $food->name }}</div>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-2 col-sm-offset-1">1人价格</label>
								<div class="col-sm-6">
									<input type="number" name="money" class="form-control" value="{{ $food->money }}" readonly>
								</div>
							</div>
							@if($food->name == '工作餐')
								<div class="form-group">
									<label class="control-label col-sm-2 col-sm-offset-1">第一级别</label>
									<div class="col-sm-2">
										<input type="number" name="a_min" class="form-control" value="{{ $food->a_min }}" placeholder="最低人数">
									</div>
									<div class="col-sm-2">
										<input type="number" name="a_max" class="form-control" value="{{ $food->a_max }}" placeholder="最多人数">
									</div>
									<div class="col-sm-2">
										<input type="number" name="a_price" class="form-control" value="{{ $food->a_price }}" placeholder="单价">
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-sm-2 col-sm-offset-1">第二级别</label>
									<div class="col-sm-2">
										<input type="number" name="b_min" class="form-control" value="{{ $food->b_min }}" placeholder="最低人数">
									</div>
									<div class="col-sm-2">
										<input type="number" name="b_price" class="form-control" value="{{ $food->b_price }}" placeholder="单价">
									</div>
								</div>
								<div class="form-group">
									<label class="control-label col-sm-2 col-sm-offset-1">关于价格</label>
									<div class="col-sm-6">
										<textarea rows="3" readonly="true" class="form-control">第一级别从左到右依次是：最少人数（包含），最多人数（包含），单价，第二级别为：最少人数（包含），单价。例如：3-5人每人45元；6人以上每人40元</textarea>
									</div>
								</div>
							@endif
							<div class="form-group">
								<label class="control-label col-sm-2 col-sm-offset-1">是否更换海报</label>
								<div class="col-sm-6">
								    <label class="radio-inline">
								        <input type="radio" name="is_change_poster" value="1">是
								    </label>
								    <label class="radio-inline">
								        <input type="radio" name="is_change_poster" value="0" checked>否
								    </label>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-2 col-sm-offset-1">海报</label>
								<div class="col-sm-6">
									{!!  widget('Tools.ImgUpload')->single2('/upload/user','poster',"poster", isset($data['poster'])? $data['poster'] : "", ['class' => '餐类图片']) !!}
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-2 col-sm-offset-1">商品简介</label>
								<div class="col-sm-6">
									<textarea class="form-control" name="info" rows="3">{{ $food->info }}</textarea>
								</div>
							</div>
							<div class="form-group" id="operations">
								<div class="col-sm-6 col-sm-offset-3">
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
	$('#operations').on('click', '.btn-reset', function() {
		url = "{{ U('Backend/Food/edit', ['id' => $food->id]) }}";
		window.location = url;
	}).on('click', '.btn-save', function() {
		$('#food-form').submit();
	}).on('click', '.btn-cancel', function() {
		url = "{{ U('Backend/Food/index') }}";
		window.location = url;
	});


});
</script>
@endsection