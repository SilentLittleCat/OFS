@extends('web.layouts.master')

@section('header')
<style type="text/css">
	.row {
		margin-top: 10px;
		margin-bottom: 10px;
	}
	.sg-divider-light {
		margin-bottom: 40px;
	}
</style>
@endsection

@section('content')
<div class="container sg-container">
	<div class="score-header">
		<div class="row">
			<div class="col-xs-6 col-sm-6 sg-strong">
				我的积分
			</div>
			<div class="col-xs-6 col-sm-6">
				<a href="{{ route('users.score.record') }}" class="pull-right">积分记录</a>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-6 col-sm-6 red-strong">
				{{ Auth::user()->score }}
			</div>
			<div class="col-xs-6 col-sm-6">
				<a href="{{ route('users.score.show-change') }}" class="pull-right">兑换记录</a>
			</div>
		</div>
	</div>
	<div class="sg-divider-light"></div>
	<div class="goods">
		@foreach($goods as $good)
			<div class="good">
				@if($good->poster != null)
					<div class="good-img row">
						<div class="col-xs-12 col-sm-12">
							<img src="{{ $good->poster }}" class="img-responsive img-rounded">
						</div>
					</div>
				@endif
				<div class="good-content">
					<div class="row">
						<div class="col-xs-6 col-sm-6">
							{{ $good->name }}
						</div>
						<div class="col-xs-6 col-sm-6">
							<div class="pull-right">{{ $good->score . '积分' }}</div>
						</div>
					</div>
					<div class="row sg-light">
						<div class="col-xs-3 col-sm-3">
							简介
						</div>
						<div class="col-xs-9 col-sm-9">
							{{ $good->info }}
						</div>
					</div>
				</div>
				@if($good->score <= Auth::user()->score)
					<div class="good-actions">
						<div class="row">
							<div class="col-xs-12 col-sm-12">
								<button type="button" class="btn btn-info btn-sm pull-right change-score" data-id="{{ $good->id }}">兑换</button>
							</div>
						</div>
					</div>
				@endif
			</div>
			<div class="sg-divider-bold"></div>
		@endforeach
	</div>
</div>
<form class="sg-hide" method="POST" id="change-score-form">
	{{ csrf_field() }}
</form>
@endsection

@section('footer')
<script type="text/javascript">
$(function() {
	$('.goods').on('click', '.change-score', function() {
		url = "{{ route('users.score.change') }}" + '?good_id=' + $(this).attr('data-id');
		$('#change-score-form').attr('action', url).submit();
	});
});
</script>
@endsection