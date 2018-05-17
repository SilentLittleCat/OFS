@extends('web.layouts.master')

@section('header')
<style type="text/css">
	
</style>
@endsection

@section('content')
<div class="container sg-container">
	<div class="records">
		@if($records->count() == 0)
			<div style="margin-top: 10px; text-align: center">暂无余额流水记录！</div>
		@else
			@foreach($records as $record)
				<div class="record">
					<div class="row">
						<div class="col-sm-12 col-xs-12 pull-right">{{ $record->created_at }}</div>
					</div>
					<div class="sg-divider-light"></div>
					<div class="row">
						<div class="col-sm-8 col-xs-8">
							<strong>{{ $record->info }}</strong>
						</div>
						<div class="col-sm-4 col-sm-4 pull-right">
							@if($record->money >= 0)
								<span class="success-color">{{ '+' . $record->money . '元' }}</span>
							@else
								<span class="danger-color">{{ $record->money . '元' }}</span>
							@endif
						</div>
					</div>
				</div>
				<div class="sg-divider-bold"></div>
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