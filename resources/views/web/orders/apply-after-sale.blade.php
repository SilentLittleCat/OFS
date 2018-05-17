@extends('web.layouts.master')

@section('header')
<style type="text/css">
	.sg-container {
		margin: 50px 0px;
	}
	.order-id {
		margin-left: 10px;
	}
	.sg-info {
		font-size: 0.8em;
		color: grey;
	}
</style>
@endsection

@section('content')
<div class="sg-container container">
	<form method="POST" action="{{ route('orders.create.after.sale') }}" enctype="multipart/form-data" id="apply-after-form">

		{{ csrf_field() }}
		<div class="row">
			<div class="col-sm-12 col-xs-12 sg-info">
				选择您需要售后的订单日期
			</div>
		</div>
		<div class="sg-divider-light"></div>
		<div class="row">
			<div class="col-sm-12 col-xs-12" id="date-picker-container"></div>
			<input type="hidden" name="date" id="date-picker" value="">
		</div>
		<div class="sg-divider-bold"></div>
		<div class="row">
			<div class="col-sm-12 col-xs-12 sg-info">
				您选择的日期是：<span id="select-date-info"></span>&nbsp;&nbsp;&nbsp;以下是这一天的订单
			</div>
		</div>
		<div class="sg-divider-light"></div>
		<div class="row">
			<div class="col-sm-12 col-xs-12">
				<div class="weui-btn weui-btn_mini weui-btn_plain-primary" id="select-all">全选</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12 col-xs-12">
				<div id="order-info"></div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12 col-xs-12">
				<div id="total-money"></div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12 col-xs-12 sg-info">
				请提供需要申请售后的订单照片
			</div>
		</div>
		<div class="sg-divider-light"></div>
		<div class="row">
			<div class="col-sm-12 col-xs-12">
				{!!  widget('Tools.ImgUpload')->single4('/upload/user', 'poster') !!}
			</div>
		</div>
		<div class="sg-divider-light"></div>
		<div class="row">
			<div class="col-sm-12 col-xs-12 sg-info">
				请陈述您的理由
			</div>
		</div>
		<div class="sg-divider-light"></div>
		<div class="row">
			<div class="col-sm-12 col-xs-12">
				<textarea class="form-control" rows="3" name="reason"></textarea>
			</div>
		</div>
		<div class="sg-divider-light"></div>
		<div class="row">
			<div class="col-sm-12 col-xs-12">
				<button class="weui-btn weui-btn_plain-primary">申请售后</button>
			</div>
		</div>
	</form>
</div>
@endsection

@section('footer')
<script type="text/javascript">
$(function() {
	$('#date-picker').calendar({
		value: [],
		container: "#date-picker-container",
		onChange: function (p, values, displayValues) {
			if(values.length > 0) {
				date = values[0];
				$('#select-date-info').text(date);
				type = 'POST';
				my_url = "{{ route('orders.ajax.after.sale') }}";
		        $.ajax({
		            headers: {
		                'X-CSRF-TOKEN': "{{ csrf_token() }}"
		            },
		            type: type,
		            url: my_url,
		            data: {
		            	date: date
		            },
		            dataType: 'json',
		            success: function (data) {
		            	if(data.length == 0) {
		            		$('#order-info').empty();
		            	} else {
		            		tmp = '';
		            		for(var i = 0; i < data.length; ++i) {
		            			kind = (data[i].type == 1 ? '男士餐' : (data[i].type == 2 ? '女士餐' : '工作餐'));
		            			tmp += '<div class="order-item"><input type="checkbox" name="order[' + i + ']" value="' + data[i].id + '"/>' + '&nbsp;&nbsp;&nbsp;' + kind + '<span class="order-id">' + '订单号：&nbsp;&nbsp;&nbsp;' + data[i].order_id + '&nbsp;&nbsp;&nbsp;</span>' + '</div>';
		            		}
		            		$('#order-info').empty().append(tmp);
		            	}
		            	$('#total-money').val(data.money);
		            },
		            error: function (data) {

		            }
		        });
			}
		}
	});

	$('#select-all').on('click', function() {
		$('#order-info input').prop('checked', true);
	});
});
</script>
@endsection