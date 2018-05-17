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
					<h5>充值二维码管理</h5>
					<div class="ibox-tools">
						<a class="collapse-link"> <i class="fa fa-chevron-up"></i>
						</a>
					</div>
				</div>
				<div class="ibox-content">
					<form class="form-horizontal" method="POST" action="{{ U('Backend/Manage/updatePayCode') }}" id="pay-code-form">
						
						{{ csrf_field() }}
						<div class="form-group">
							<label class="form-label col-sm-3 control-label">充值二维码</label>
							<div class="col-sm-7">
								@if($pay_code != null && $pay_code->val != null)
									<img src="{{ $pay_code != null ? $pay_code->val : '' }}" width="200px">
								@else
									<input type="text" name="tel" class="form-control" id="contact-us-tel" value="暂无充值二维码"  readonly>
								@endif
							</div>
						</div>
						<div class="form-group">
							<label class="form-label col-sm-3 control-label">上传新二维码</label>
							<div class="col-sm-9">
								{!!  widget('Tools.ImgUpload')->single2('/upload/user', 'contact-us-two-code', 'pay_code') !!}
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-9 col-sm-offset-3">
								<div class="btn btn-sm btn-primary" id="btn-save">保存</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('footer')
<script type="text/javascript">
$(function() {

	$('#btn-save').on('click', function() {
		$('#pay-code-form').submit();
	});
});
</script>
@endsection