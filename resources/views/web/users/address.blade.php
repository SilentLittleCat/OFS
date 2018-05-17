@extends('web.layouts.master')

@section('header')
<style type="text/css">
	.user-addresses {
		margin-top: 20px;
	}
	.bottom-nav {
		width: 100%;
		position: fixed;
		bottom: 0;
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
<div class="container sg-container">
	<div class="user-addresses">
		@if($addresses->count() == 0)
			<div class="user-address">
				<div class="row">
					<div class="col-xs-12 col-sm-12">
						暂无收货地址
					</div>
				</div>
			</div>
		@else
			@foreach($addresses as $address)
				<div class="user-address">
					<div class="row">
						<div class="col-xs-12 col-sm-12">
							<i class="fa fa-user fa-lg" aria-hidden="true"></i>
							&nbsp;&nbsp;{{ $address->username . ' ' . $address->tel }}
						</div>
					</div>
					<div class="sg-divider-none"></div>
					<div class="row">
						<div class="col-xs-12 col-sm-12">
							<i class="fa fa-map-marker fa-lg" aria-hidden="true"></i>
							&nbsp;&nbsp;&nbsp;{{ $address->address }}
						</div>
					</div>
				</div>
				<div class="sg-divider"></div>
			@endforeach
		@endif
	</div>
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
});
</script>
@endsection