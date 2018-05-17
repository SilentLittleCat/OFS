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
	<div class="row sg-centered">订单号：{{ sprintf('%013s', $order->id) }}</div>
	<div class="row">
		<div class="col-xs-12 col-sm-12" align="center">
			{!! QrCode::size(200)->generate(route('orders.share', ['id' => $order->id])) !!}
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