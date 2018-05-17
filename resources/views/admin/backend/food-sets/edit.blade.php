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
					<h5>编辑套餐</h5>
					<div class="ibox-tools">
						<a class="collapse-link"> <i class="fa fa-chevron-up"></i>
						</a>
					</div>
				</div>
				<div class="ibox-content">
					<div class="row">
						<form class="form-horizontal" method="POST" action="{{ U('Backend/FoodSet/update') }}" id="food-set-form">
							{{ csrf_field() }}

							<input type="text" name="id" value="{{ $food_set->id }}" class="sg-hide">
							<div class="form-group">
								<label class="control-label col-sm-2 col-sm-offset-1">套餐种类</label>
								<div class="col-sm-6">
									<input type="text" name="kind" class="form-control" value="{{ $food_set->kind == 0 ? '周餐（5天）' : '月餐（20天）' }}" readonly="">
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-2 col-sm-offset-1">分类</label>
								<div class="col-sm-6">
									<input type="text" name="type" class="form-control" value="{{ $food_set->type == 1 ? '男士餐' : ($food_set->type == 2 ? '女士餐' : '工作餐')  }}" readonly="">
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-2 col-sm-offset-1">价格</label>
								<div class="col-sm-6">
									<input type="number" name="money" class="form-control" value="{{ $food_set->money }}">
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-2 col-sm-offset-1">排序</label>
								<div class="col-sm-6">
									<input type="number" name="sort" class="form-control" value="{{ $food_set->sort }}">
								</div>
							</div>
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
		url = "{{ U('Backend/FoodSet/edit', ['id' => $food_set->id]) }}";
		window.location = url;
	}).on('click', '.btn-save', function() {
		$('#food-set-form').submit();
	}).on('click', '.btn-cancel', function() {
		url = "{{ U('Backend/FoodSet/index') }}";
		window.location = url;
	});


});
</script>
@endsection