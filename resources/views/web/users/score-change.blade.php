@extends('web.layouts.master')

@section('header')
<style type="text/css">
	
</style>
@endsection

@section('content')
<div class="container sg-container">
	<div class="records">
		@foreach($records as $record)
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
			<div class="sg-divider-light"></div>
		@endforeach
	</div>
</div>
@endsection

@section('footer')
<script type="text/javascript">
$(function() {
	
});
</script>
@endsection