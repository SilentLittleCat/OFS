@extends('web.layouts.master')

@section('header')
<style type="text/css">

</style>
@endsection

@section('content')
<div class="container sg-container">
	@if($weights->count() != 0)
		<div class="row">
			<div class="col-xs-12 col-sm-12">
				<div id="weight-chart" style="width: 100%; height:400px;"></div>
			</div>
		</div>
	@endif
	@if($weights->count() == 0)
		<div class="row">
			<div class="col-xs-12 col-sm-12 sg-centered">暂无体重记录</div>
		</div>
	@else
		@foreach($weights as $weight)
			<div class="row sg-centered">
				<div class="col-xs-4 col-sm-4 danger-color">{{ $weight->weight . 'KG' }}</div>
				<div class="col-xs-8 col-sm-8 pull-right">{{ $weight->created_at }}</div>
			</div>
			<div class="sg-divider-1"></div>
		@endforeach
	@endif
</div>
@endsection

@section('footer')
@if($weights->count() != 0)
<script type="text/javascript">
$(function() {
	
    var myChart = echarts.init(document.getElementById('weight-chart'));

    // 指定图表的配置项和数据
    var option = {
        title: {
        	left: 'center',
            text: '{{ Auth::user()->wechat_name . "体重记录" }}'
        },
        tooltip: {
        	trigger: 'axis',
        	position: function (pt) {
            	return [pt[0], '10%'];
        	}
        },
        grid: {
	        left: '3%',
	        containLabel: true
	    },
        xAxis: {
        	boundaryGap: false,
            data: "{{ $date_arr }}".split('|')
        },
        yAxis: {
	        type: 'value',
	        axisLabel: {
	            formatter: '{value}KG'
	        }
	    },
	    dataZoom: [{
	        type: 'inside',
	        start: 0,
	        end: 100
	    }, {
	        start: 0,
	        end: 10,
	        handleIcon: 'M10.7,11.9v-1.3H9.3v1.3c-4.9,0.3-8.8,4.4-8.8,9.4c0,5,3.9,9.1,8.8,9.4v1.3h1.3v-1.3c4.9-0.3,8.8-4.4,8.8-9.4C19.5,16.3,15.6,12.2,10.7,11.9z M13.3,24.4H6.7V23h6.6V24.4z M13.3,19.6H6.7v-1.4h6.6V19.6z',
	        handleSize: '80%',
	        handleStyle: {
	            color: '#fff',
	            shadowBlur: 3,
	            shadowColor: 'rgba(0, 0, 0, 0.6)',
	            shadowOffsetX: 2,
	            shadowOffsetY: 2
	        }
	    }],
        series: [{
            name: '体重',
            type: 'line',
            data: "{{ $weight_arr }}".split('|')
        }]
    };

    // 使用刚指定的配置项和数据显示图表。
    myChart.setOption(option);
});
</script>
@endif
@endsection