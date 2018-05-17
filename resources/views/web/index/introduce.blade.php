@extends('web.layouts.master')

@section('header')
<style type="text/css">
	.introduce-container {
		margin-top: 50px;
	}
</style>
@endsection

@section('content')
<div class="container introduce-container">
	@foreach($foods as $food)
	<div class="panel panel-default">
	    <div class="panel-heading">
	        <h3 class="panel-title">
	            {{ $food->name }}
	        </h3>
	    </div>
	    <div class="panel-body">
	        <img src="{{ $food->poster }}" class="img-responsive" alt="餐类图片" id="food-img">
	    </div>
	    <div class="panel-footer">
	        {{ $food->info }}
	    </div>
	</div>
	@endforeach
</div>
@endsection

@section('footer')

@endsection