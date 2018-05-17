@extends('admin.layout')

@section('header')
<style type="text/css">
	#number-cards {
		text-align: center;
		color: white;
		font-size: 1.6em;
	}
	.sg-number-card {
		padding: 20px;
		height: 130px;
	}
	.sg-card-text {
		font-size: 0.7em;
	}
	.sg-divider-bold {
		width: 100%;
		height: 3px;
		background-color: rgba(0, 0, 0, 0.1);
		margin: 20px 0;
	}
</style>
@endsection

@section('content')
<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
		<div class="col-sm-12">
			@if(isset($errors) && !$errors->isEmpty())
	        <div class="alert alert-danger alert-dismissable">
	            <button type="button" class="close" data-dismiss="alert"
	                    aria-hidden="true">
	                &times;
	            </button>
	            @foreach($errors->keys() as $key)
	            	{{ $errors->first($key) }}
	            @endforeach
	        </div>
			@endif

			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<h5>营收管理</h5>
					<div class="ibox-tools">
						<a class="collapse-link"> <i class="fa fa-chevron-up"></i>
						</a>
					</div>
				</div>
				<div class="ibox-content">
					<div class="row" id="number-cards">
						<div class="col-sm-2">
							<div class="sg-number-card" style="background-color: #fe5722">
								<div style="font-size: 0.7em">
									<div>{{ $total_money }}</div>
									<div class="sg-card-text">一月流水收入</div>
									<div>{{ $total_money_except_not_pay }}</div>
									<div class="sg-card-text">一月营业收入</div>
								</div>
							</div>
						</div>
						<div class="col-sm-2">
							<div class="sg-number-card" style="background-color: #ffcc00">
								<div style="font-size: 0.7em">
									<div>{{ $profit }}</div>
									<div class="sg-card-text">一月毛利</div>
									<div>{{ $profit_rate . '%' }}</div>
									<div class="sg-card-text">毛利率</div>
								</div>
							</div>
						</div>
						<div class="col-sm-2">
							<div class="sg-number-card" style="background-color: #00bcd5">
								<div>{{ $total_send }}</div>
								<div class="sg-card-text">一月配送量</div>
							</div>
						</div>
						<div class="col-sm-2">
							<div class="sg-number-card" style="background-color: #199ed8">
								<div>{{ $continue_buy_rate . '%' }}</div>
								<div class="sg-card-text">连续购餐率</div>
							</div>
						</div>
						<div class="col-sm-2">
							<div class="sg-number-card" style="background-color: #16b75a">
								<div>{{ $inventories }}</div>
								<div class="sg-card-text">一月流水支出</div>
							</div>
						</div>
						<div class="col-sm-2">
							<div class="sg-number-card" style="background-color: #999999">
								<div>{{ $costs_money }}</div>
								<div class="sg-card-text">一月运营成本</div>
							</div>
						</div>
					</div>
					<div class="sg-divider-bold"></div>
					<div class="row">
						<div class="col-sm-12">
							<div class="btn-group pull-right">
								<div class="btn btn-sm btn-success" id="export-send">导出明细</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-8 col-sm-offset-2">
							<canvas id="myChart"></canvas>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('footer')
<script type="text/javascript">
$(function() {
	$('#export-send').on('click', function() {
		window.location = "{{ U('Backend/Revenue/exportSend') }}";
	});
	function randomScalingFactor() {
		return (Math.random() > 0.5 ? 1.0 : -1.0) * Math.round(Math.random() * 100);
	};
    var ctx = document.getElementById("myChart");
    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ["第一周", "第二周", "第三周", "第四周"],
            datasets: [{
                label: "配送量",
                backgroundColor: window.chartColors.blue,
                borderColor: window.chartColors.blue,
                fill: false,
                yAxisID: "y-axis-right",
                borderDash: [5, 5],
                data: [{{ $send_num_array[0] }}, {{ $send_num_array[1] }}, {{ $send_num_array[2] }}, {{ $send_num_array[3] }}]
            }, {
                label: "预收款",
                fill: false,
                backgroundColor: window.chartColors.orange,
                borderColor: window.chartColors.orange,
                yAxisID: "y-axis-left",
                data: [{{ $pre_get_money_array[0] }}, {{ $pre_get_money_array[1] }}, {{ $pre_get_money_array[2] }}, {{ $pre_get_money_array[3] }}]
            }, {
                label: "实收款",
                fill: false,
                backgroundColor: window.chartColors.purple,
                borderColor: window.chartColors.purple,
                yAxisID: "y-axis-left",
                data: [{{ $real_get_money_array[0] }}, {{ $real_get_money_array[1] }}, {{ $real_get_money_array[2] }}, {{ $real_get_money_array[3] }}]
            }, {
                label: "毛利",
                fill: false,
                backgroundColor: window.chartColors.red,
                borderColor: window.chartColors.red,
                yAxisID: "y-axis-left",
                data: [{{ $profit_array[0] }}, {{ $profit_array[1] }}, {{ $profit_array[2] }}, {{ $profit_array[3] }}]
            }]
        },
        options: {
            responsive: true,
            title:{
                display:true,
                text:'{{ $month . "月营收管理" }}',
                fontSize: 20
            },
            tooltips: {
                mode: 'index',
                intersect: false,
            },
            hover: {
                mode: 'nearest',
                intersect: true
            },
            scales: {
                xAxes: [{
                    display: true,
                    scaleLabel: {
                        display: true,
                        labelString: '周数',
                		fontSize: 16
                    }
                }],
                yAxes: [{
                        type: "linear", // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
                        display: true,
                        position: "left",
                        id: "y-axis-left",
                        scaleLabel: {
                        	display: true,
                        	labelString: '金额（元）',
                			fontSize: 16
                    	}
                    }, {
                        type: "linear", // only linear but allow scale type registration. This allows extensions to exist solely for log scale for instance
                        display: true,
                        position: "right",
                        id: "y-axis-right",

                        // grid line settings
                        gridLines: {
                            drawOnChartArea: false, // only want the grid lines for one axis to show up
                        },
                        scaleLabel: {
                        	display: true,
                        	labelString: '份数（份）',
                			fontSize: 16
                    	}
                    }],
            }
        }
    });
});
</script>
@endsection