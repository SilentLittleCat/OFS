@extends('web.layouts.master')

@section('header')
<style type="text/css">
	.user-addresses {
		margin-top: 20px;
	}
	.bottom-nav {
		width: 100%;
		position: fixed;
		bottom: 70px;
	}
	.add-address-btn {
		height: 50px;
		background-color: #418bca;
		color: white;
		padding: 12px;
		font-weight: bold;
		font-size: 1.1em;
	}
</style>
@endsection

@section('content')
<div class="app-container">
	<form role="form" id="change-add-form" method="POST" action="{{ route('orders.change.address', ['id' => $send->id]) }}">
		{{ csrf_field() }}
		<div class="weui-cells weui-cells_radio">
			@foreach($addresses as $address)
				<label class="weui-cell weui-check__label">
					<div class="weui-cell__bd">
						<p>{{ $address->address }}</p>
					</div>
					<div class="weui-cell__ft">
						<input type="radio" class="weui-check" name="address" value="{{ $address->address }}">
						<span class="weui-icon-checked"></span>
					</div>
				</label>
			@endforeach
		</div>
		<div class="weui-btn-area">
	      	<div class="weui-btn weui-btn_primary" id="change-add-btn">确定</div>
	    </div>
    </form>
</div>
<div class="bottom-nav">
	<div class="add-address-btn sg-centered">添加收货地址</div>
</div>
@endsection

@section('footer')
<script type="text/javascript">
$(function() {
	$('.add-address-btn').on('click', function() {
		window.location = "{{ route('users.address.add') }}";
	});

	$('#change-add-btn').on('click', function() {
		$('#change-add-form').submit();
	});
});
</script>
@endsection