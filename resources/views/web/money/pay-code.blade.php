@extends('web.layouts.master')

@section('header')
<style type="text/css">
	.sg-container {
		margin-top: 70px;
	}
	#btn-row {
		margin-top: 30px;
	}
</style>
@endsection

@section('content')
<div class="container sg-container">
	@if($pay_code == null)
		<div class="row">
			<div class="col-xs-12 col-sm-12 sg-centered">暂无二维码！</div>
		</div>
	@else
		<div class="row">
			<div class="col-xs-12 col-sm-12" align="center">
				<img src="{{ $pay_code->val }}">
			</div>
		</div>
	@endif
	<div class="row" id="btn-row">
		<div class="col-sm-8 col-xs-8 col-sm-offset-2 col-xs-offset-2">
			<div class="weui-btn weui-btn_primary" id="back-btn">我已付款</div>
		</div>
	</div>
	
</div>
@endsection

@section('footer')
<script type="text/javascript">
$(function() {
	$('#back-btn').on('click', function() {
		url = "{{ route('users.index') }}";
		window.location = url;
	});
});
</script>
@endsection