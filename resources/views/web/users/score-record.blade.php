@extends('web.layouts.master')

@section('header')
<style type="text/css">
	
</style>
@endsection

@section('content')
<div class="container sg-container">
	<div class="records">
		@if($records->count() == 0)
			<div style="margin-top: 20px; text-align: center">暂无积分记录！</div>
		@else
			@foreach($records as $record)
				@if($record->status == 0)
					<div class="record">
						<div class="row">
							<div class="col-xs-8 col-sm-8">
								订单：{{ sprintf('%013d', $record->order_id) }}
							</div>
							<div class="col-xs-4 col-sm-4">
								<div class="pull-right">
									{{ $record->updated_at }}
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-xs-8 col-sm-8 success-color">
								{{ '+' . $record->score . '积分' }}
							</div>
						</div>
					</div>
				@else
					<div class="record">
						<div class="row">
							<div class="col-xs-8 col-sm-8">
								{{ $record->name }}
							</div>
							<div class="col-xs-4 col-sm-4">
								<div class="pull-right">
									{{ $record->created_at }}
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-xs-8 col-sm-8 danger-color">
								{{ '-' . $record->score . '积分' }}
							</div>
						</div>
					</div>
				@endif
				<div class="sg-divider-light"></div>
			@endforeach
		@endif
	</div>
</div>
@endsection

@section('footer')
<script type="text/javascript">
$(function() {
	
});
</script>
@endsection