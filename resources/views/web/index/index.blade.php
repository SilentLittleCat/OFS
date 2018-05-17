@extends('web.layouts.master')

@section('header')
<style type="text/css">
	.order-food-container {
		margin-top: 10px;
		margin-bottom: 80px;
	}
	.sg-hide {
		display: none;
	}
	.sg-show {
		display: block;
	}
	.money-group {
		font-size: 0.7em;
	}
	.wechat-info-margin {
		margin-top: 20px;
	}
	#send-num-modal {
		width: 100%;
		position: fixed;
		z-index: 1200;
		bottom: 60px;
		text-align: center;
		background: white;
		margin: 0;
		padding: 0;
	}
	.sg-modal-title {
		background-color: #f7f7f8;
		margin: 0;
		height: 70px;
		line-height: 70px;
	}
	.sg-modal-title i {
		padding: 22px;
	}
	.sg-modal-title h5 {
		margin: 0;
		line-height: 70px;
	}
	.sg-select-row {
		margin: 0;
		padding: 8px 0;
		width: 100%;
	}
	.sg-select-item {
		width: calc(100% / 7);
		display: inline-block;
		text-align: center;
	}
	.sg-select-item.sg-selected div {
		background-color: #04be02;
		color: white;
	}
	.sg-select-item div {
		width: 30px;
		height: 30px;
		border-radius: 50%;
		line-height: 30px;
		margin: auto;
	}
	.sg-day-disabled span {
		background-color: #888888;
		color: #D0D0D0;
	}
	.picker-calendar-day-today.sg-day-disabled span {
		background-color: #888888;
		color: #D0D0D0;
	}
	.picker-calendar-day-selected.sg-day-disabled span {
		background-color: #888888;
		color: #D0D0D0;
	}
	#detail-modal {
		margin-top: 100px;
	}
	.sg-hide {
		display: none;
	}
	.sg-show {
		display: block;
	}
	#inline-calendar {
		position: fixed;
		bottom: 70px;
		width: 100%;
		z-index: 1000;
	}
</style>
@endsection

@section('content')
<div id="sg-container">
<div id="indexCarousel" class="carousel slide">
    <ol class="carousel-indicators">
    	@foreach($carousels as $carousel)
    		@if($loop->index == 0)
    			<li data-target="#indexCarousel" data-slide-to="0" class="active"></li>
    		@else
    			<li data-target="#indexCarousel" data-slide-to="{{ $loop->index }}"></li>
    		@endif
    	@endforeach
    </ol>
    <div class="carousel-inner">
    	@foreach($carousels as $carousel)
    		@if($loop->index == 0)
		        <div class="item active" align="center">
		            <img src="{{ $carousel->poster }}" alt="First slide">
		        </div>
    		@else
		        <div class="item" align="center">
		            <img src="{{ $carousel->poster }}" alt="Second slide">
		        </div>
    		@endif
    	@endforeach
    </div>
</div>
<div class="order-food-container ofs-content-padded">
	<form role="form" id="order-form" method="POST" action="{{ route('orders.generate', ['recommend' => $recommend]) }}">

		<div class="form-group sg-hide">
			{{ csrf_field() }}
			
			<input class="form-control input-sm sg-hide" type="text" value="0" name="method">
			<input class="form-control input-sm sg-hide" type="text" value="1" name="total_num" id="total_num_input">
			<input class="form-control input-sm sg-hide" type="text" value="" name="total_money" id="total_money_input">
			<input class="form-control input-sm sg-hide" type="text" value="" name="food_set" id="food_set_input">
		</div>

		<div class="weui-cells weui-cells_form">
            <div class="weui-cells">
                <div class="weui-cell weui-cell_select">
                    <div class="weui-cell__bd">
                        <select id="food-select" name="type" class="weui-select">
                            @foreach($foods as $food)
								<option value="{{ $food->type }}" data-poster="{{ $food->poster }}">{{ $food->name }}</option>
							@endforeach
                        </select>
                    </div>
                </div>
                <div class="weui-cell weui-cell_select">
                    <div class="weui-cell__bd">
                        <select id="food-set" class="weui-select" data-change="1" name="food_set_money">
                        	@foreach($food_sets as $food_set)
                        		<option value="{{ $food_set->money }}" data-type="{{ $food_set->type }}" data-kind="{{ $food_set->kind }}">{{ $food_set->kind == 0 ? '周餐（5天）' : '月餐（20天）' }}</option>
                        	@endforeach
                        	<option data-type="custom" data-kind="custom" value="0">自定义</option>
                        </select>
                    </div>
                </div>
                <div class="weui-cells__tips">
                	<div class="weui-flex">
                		<div class="weui-flex__item">
                			已选择：<span id="food-kind">男士餐</span>
                		</div>
                		<div class="weui-flex__item">
                			剩余次数：<span id="remain-times">{{ $man_remain_times }}</span>
                		</div>
                	</div>
                </div>
                <div class="weui-cell">
                    <div class="weui-cell__bd">
                        <img src="{{ $foods->first()->poster }}" class="img-responsive" alt="餐类图片" id="food-img">
                    </div>
                </div>
                <div class="weui-cell">
                    <div class="weui-cell__bd">
                        <input class="weui-input" type="text" value="" readonly id="date-picker" name="dates" required data-last-num="0" placeholder="日期选择" data-last-val="" class="sg-hide">
                        <input class="form-control input-sm sg-hide" type="text" value="" readonly id="send-info-input" name="dates_details">
                    </div>
                </div>
                <div class="weui-cell">
                    <div class="weui-cell__bd">
                        <input type="text" class="weui-input" id="user-name" name="real_name" required value="{{ Auth::check() ? Auth::user()->real_name : '' }}" placeholder="姓名">
                    </div>
                </div>
                <div class="weui-cell">
                    <div class="weui-cell__bd">
                        <input type="text" class="weui-input" name="tel" value="{{ Auth::check() ? Auth::user()->tel : '' }}" required placeholder="手机" id="send-tel">
                    </div>
                </div>
                <div class="weui-cell weui-cell_select">
                    <div class="weui-cell__bd">
						<select class="weui-select" id="address-select" name="address">
							@foreach($addresses as $address)
								<option value="{{ $address->address }}" {{ $loop->index == 0 ? 'selected' : '' }}>{{ $address->address }}</option>
							@endforeach
							<option value="new-address">新增地址</option>
						</select>
                    </div>
                </div>
                <div class="add-new-address {{ $addresses->count() == 0 ? '' : 'sg-hide' }}">
					<div class="weui-cell weui-cell_select">
	                    <div class="weui-cell__bd">
							<select class="weui-select" id="select-city" name="">
								@if($cities != null && $cities->count() != 0)
                    			@foreach($cities as $city)
                    				<option value="{{ $city->city }}">{{ $city->city }}</option>
                    			@endforeach
                    			@endif
							</select>
	                    </div>
	                </div>
	                <div class="weui-cell weui-cell_select">
	                    <div class="weui-cell__bd">
							<select class="weui-select" id="select-county" name="">
								@if($all_counties != null && $all_counties->count() != 0)
                    			@foreach($all_counties as $county)
                    				<option value="{{ $county->county }}" data-fa="{{ $county->city }}" class="{{ $county->city == $city->first()->city ? '' : 'sg-hide' }}">{{ $county->county }}</option>
                    			@endforeach
                    			@endif
							</select>
	                    </div>
	                </div>
	                <div class="weui-cell">
	                    <div class="weui-cell__bd">
							<input type="text" class="weui-input" placeholder="详细地址" name="detail_address" id="detail-address">
							<input type="text" class="form-control input-sm sg-hide" placeholder="详细地址" name="full_address" id="full-address">
	                    </div>
	                </div>
                </div>
                <div class="weui-cell">
                    <div class="weui-cell__bd">
                        <input type="text" class="weui-input" name="info" value="" placeholder="备注">
                    </div>
                </div>
            </div>
			<div class="weui-cells">
				<div class="weui-cell">
					<div class="weui-flex">
						<div class="weui-flex__item">
							已选餐：<span id="select-food-kind">男士餐</span>
						</div>
					</div>
				</div>
				<div class="weui-cell">
					<div class="weui-flex">
						<div class="weui-flex__item">
							配送份数：<span id="select-send-nums">0</span>
						</div>
					</div>
				</div>
				<div class="weui-cell">
					<div class="weui-flex">
						<div class="weui-flex__item">
							待支付&nbsp;&nbsp;<span id="total-money" class="sg-money">￥0</span>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="weui-btn-area">
			<div class="weui-btn weui-btn_primary" id="detail-btn">订单详情</div>
			<div class="weui-btn weui-btn_warn" id="pay-btn">立即支付</div>
			<div class="btn btn-info btn-block sg-hide" id="share-btn">分享订单</div>
		</div>
	</form>
</div>
</div>
<div class="modal fade" id="detail-modal" tabindex="-1" role="dialog" aria-labelledby="detail-modal-label" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="detail-modal-label">
					订单详情
				</h4>
			</div>
			<div class="modal-body" id="detail-modal-content">

			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="money-modal" tabindex="-1" role="dialog" aria-labelledby="money-modal-label" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">
					余额不足！请充值后再购买！
				</h4>
			</div>
			<div class="modal-body">

			</div>
		</div>
	</div>
</div>
<div id="send-num-modal" class="sg-hide">
	<div class="sg-modal-title">
		<div class="row">
			<div class="col-xs-3 col-sm-3">
				<i class="fa fa-arrow-left pull-left fa-lg hide-modal" aria-hidden="true"></i>
			</div>
			<div class="col-xs-6 col-sm-6">
				<h5></h5>
			</div>
			<div class="col-xs-3 col-sm-3">
				<i class="fa fa-arrow-down pull-right fa-lg hide-all" aria-hidden="true"></i>
			</div>
		</div>
	</div>
	<div class="sg-modal-body">
		@foreach($nums as $num)
			@if($loop->index % 6 == 0)
				<div class="sg-select-row">
			@endif
			<div class="sg-select-item" data-num="{{ $num }}">
				<div>{{ $num }}</div>
			</div>
			@if($loop->index % 6 == 5)
				</div>
			@endif
		@endforeach
	</div>
</div>
<div id="inline-calendar" class="sg-hide"></div>
@endsection

@section('footer')

<script type="text/javascript">
$(function() {

	@if($errors->has('pay_error'))
		$.toptip("余额不足！请充值后再购买！", 'error');
	@endif

	Date.prototype.Format = function (fmt) { //author: meizz 
	    var o = {
	        "M+": this.getMonth() + 1, //月份 
	        "d+": this.getDate(), //日 
	        "h+": this.getHours(), //小时 
	        "m+": this.getMinutes(), //分 
	        "s+": this.getSeconds(), //秒 
	        "q+": Math.floor((this.getMonth() + 3) / 3), //季度 
	        "S": this.getMilliseconds() //毫秒 
	    };
	    if (/(y+)/.test(fmt)) fmt = fmt.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));
	    for (var k in o)
	    if (new RegExp("(" + k + ")").test(fmt)) fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
	    return fmt;
	}

	$('#select-city').change(function() {
		// my_url = "{{ U('Backend/SendRange/queryCountiesFromName') }}" + '?city=' + $(this).val();
  //       $.ajax({
  //           headers: {
  //               'X-CSRF-TOKEN': "{{ csrf_token() }}"
  //           },
  //           type: 'POST',
  //           url: my_url,
  //           dataType: 'json',
  //           success: function (data) {
  //           	text = '';
  //           	// console.log(data);
  //               for(var i = 0; i < data.length; ++i) {
  //               	text += '<option value="' + data[i] + '">' + data[i] + '</option>';
  //               }
  //               // $('#select-county').empty();
  //               $('#select-county').html(text);
  //           },
  //           error: function (data) {
  //               console.log('Error:', data);
  //           }
  //       });
  		city = $(this).val();
  		$('#select-county option').addClass('sg-hide');
  		$('#select-county').find('option[data-fa=' + city + ']').removeClass('sg-hide');
  		$('#select-county').find('option[data-fa=' + city + ']').eq(0).prop('selected', true);
	});

	$('#detail-btn').on('click', function() {
		num = $('#select-send-nums').text();
		total_money = $('#total-money').text();
		detail = $('#send-info-input').val();
		food_kind = $('#select-food-kind').text();

		html = '<h4>' + food_kind + '：' + num + '份，共计' + total_money + '元' + '</h4>';
		items = detail.split(',');

		for(var i = 0; i < items.length; ++i) {
			if($.trim(items[i]) != '') {
				tmp = items[i].split(':');
				html += '<div>' + tmp[0] + '：' + tmp[1] + '份</div>';
			}
		}

		// $.alert("自定义的消息内容", "自定义的标题");

		$('#detail-modal-content').html(html);

		$('#detail-modal').modal('show');
	});

	function updateNumAndMoney()
	{
		text = $('#send-info-input').val();
		items = text.split(',');
		total_num = 0;
		for(var i = 0; i < items.length; ++i) {
			if(items[i] != '') {
				total_num += parseInt(items[i].split(':')[1]);
			}
		}
		$('#select-send-nums').text(total_num);
		$('#total_num_input').val(total_num);

		type = parseInt($('#food-select').val());
		man_price = parseInt("{{ $man_food_price }}");
		woman_price = parseInt("{{ $woman_food_price }}");
		woman_price = parseInt("{{ $woman_food_price }}");
		work_price = parseInt("{{ $work_food_price }}");
		man_remain_times = parseInt("{{ $man_remain_times }}");
		woman_remain_times = parseInt("{{ $woman_remain_times }}");
		work_remain_times = parseInt("{{ $work_remain_times }}");
		total_money = 0;

		// console.log('here');

		// if(type == 1) {
		// 	num = man_remain_times >= total_num ? 0 : total_num - man_remain_times;
		// 	total_money = man_price * num;
		// } else if(type == 2) {
		// 	num = woman_price >= total_num ? 0 : total_num - woman_price;
		// 	total_money = woman_price * total_num;
		// } else if(type == 3) {
		// 	num = work_remain_times >= total_num ? 0 : total_num - work_remain_times;
		// 	total_money = work_price * total_num;
		// }

		total_money = $('#food-set option:selected').val();
		send_money = parseInt("{{ $send_money }}");
		kind = $('#food-set option:selected').attr('data-kind');
		if(kind == 0) {
			total_money = parseFloat(total_money) + send_money * 5;
		} else if(kind == 1) {
			total_money = parseFloat(total_money) + $send_money * 20;
		}

		$('#total_money_input').val(total_money);
		$('#total-money').text('￥' + total_money);
	}

	function updateFoodInfo()
	{
		// select = parseInt($('#food-select').val());
		select = parseInt($('#food-select').val());
		food_kind = '';
		food_times = '';
		food_img_url = '';
		times = '';
		man_remain_times = "{{ $man_remain_times }}";
		woman_remain_times = "{{ $woman_remain_times }}";
		work_remain_times = "{{ $work_remain_times }}";
		if(select == 1) {
			food_kind = '男士餐';
			times = parseInt(man_remain_times);
			$('#food-set option').addClass('sg-hide');
			$('#food-set option[data-type=1]').removeClass('sg-hide');
			if(times != 0) {
				$('#food-set option[data-type=custom]').removeClass('sg-hide');
			}

			$options = $('#food-set option');
			
			for(var i = 0; i < $options.length; ++i) {
				$this = $($options[i]);
				if(! $this.hasClass('sg-hide')) {
					$this.prop('selected', true);
					break;
				}
			}
			// console.log($('#food-set option:first')[0]);
			// $('#food-set').get(0).selectedIndex = 0;
			// $('#share-btn').removeClass('sg-show').addClass('sg-hide');
		} else if(select == 2) {
			food_kind = '女士餐';
			times = parseInt(woman_remain_times);
			$('#food-set option').addClass('sg-hide');
			$('#food-set option[data-type=2]').removeClass('sg-hide');
			if(times != 0) {
				$('#food-set option[data-type=custom]').removeClass('sg-hide');
			}
			$options = $('#food-set option');

			for(var i = 0; i < $options.length; ++i) {
				$this = $($options[i]);
				if(! $this.hasClass('sg-hide')) {
					$this.prop('selected', true);
					break;
				}
			}
			// $('#food-set').get(0).selectedIndex = 0;
			// $('#share-btn').removeClass('sg-show').addClass('sg-hide');
		} else if(select == 3) {
			food_kind = '工作餐';
			times = parseInt(work_remain_times);
			$('#food-set option').addClass('sg-hide');
			$('#food-set option[data-type=3]').removeClass('sg-hide');
			if(times != 0) {
				$('#food-set option[data-type=custom]').removeClass('sg-hide');
			}
			$options = $('#food-set option');
			for(var i = 0; i < $options.length; ++i) {
				$this = $($options[i]);
				if(! $this.hasClass('sg-hide')) {
					$this.prop('selected', true);
					break;
				}
			}
			// $('#share-btn').removeClass('sg-hide').addClass('sg-show');
		}
		food_img_url = $('#food-select option:selected').attr('data-poster');
		$('#food-kind').text(food_kind);
		val = $('#food-set option:selected').text();
		$('#select-food-kind').text(food_kind + ' ' + val);
		$('#food-img').attr('src', food_img_url);
		$('#remain-times').text(times);
	}

	function handleDate(date)
	{
		items = date.split('-');
		return '' + parseInt(items[0]) + '-' + (parseInt(items[1]) - 1) + '-' + parseInt(items[2]);
	}

	function formatDate(date)
	{
		items = date.split('-');
		date = new Date(parseInt(items[0]), (parseInt(items[1])), parseInt(items[2]));
		return date.Format("yyyy-MM-dd");
	}

	function disabledDays()
	{
		$fa = $('.weui-picker-calendar').find('.picker-calendar-month-current');
		$rows = $fa.find('.picker-calendar-row');
		// console.log($rows[0]);
		for(var i = 0; i < $rows.length; ++i) {
			$($rows[i]).find('.picker-calendar-day:last').addClass('sg-day-disabled');
			$($rows[i]).find('.picker-calendar-day:last').prev().addClass('sg-day-disabled');
		}

		dates = $.trim("{{ $forbid_dates }}");
		if(dates != '') {
			dates = dates.split(',');
			for(var i = 0; i < dates.length; ++i) {
				date = handleDate(dates[i]);
				$('.weui-picker-container .picker-calendar-month-current [data-date=' + date + ']').addClass('sg-day-disabled');
			}
		}

		$items = $fa.find('.picker-calendar-day');

		now = $.trim("{{ $now }}");

		for(var i = 0; i < $items.length; ++i) {
			$this = $($items[i]);
			date = formatDate($this.attr('data-date'));

			// console.log(now, date);
			if(date < now) {
				$this.addClass('sg-day-disabled');
			}
		}
	}

	updateFoodInfo();

	$('#indexCarousel').carousel();

	// $('#date-picker').change(function() {

	// 	val = $.trim($(this).val());
	// 	if(val != '') {
	// 		$items = val.split(',');
	// 		tmp = '';
	// 		now = $.trim("{{ $now }}");
	// 		for(var i = 0; i < $items.length; ++i) {
	// 			if($items[i] < now) {
	// 				$items.splice(i, 1);
	// 			}
	// 		}
	// 		$(this).val(items.join(","));
	// 	}
	// });

	$('#date-picker').change(function() {
		$(this).val('');
	});


	$('#date-picker').calendar({
		multiple: true,
		value: [],
		closeOnSelect: false,
		container: "#inline-calendar",
		onChange: function (p, values, displayValues) {

			if(values.length != 0) {
				date = handleDate(values[values.length - 1]);
				has = $('.weui-picker-container [data-date=' + date + ']').hasClass('sg-day-disabled');
				if(has) {
					values.pop();
					p.value = values;
					p.updateValue();
					return;
				}
			}

			info = [];
			for(var i = 0; i < values.length; ++i) {
				info.push(values[i] + ':1');
			}
			$('#send-info-input').val(info.join(','));

			updateNumAndMoney();

			kind = $('#food-set option:selected').attr('data-kind');
			days = [];

			if(kind == 0) { //周餐
				if(values.length > 5) {
					$.toptip('周餐不能超过5天！', 1000, 'error');
					p.value = values.splice(0, 5);
					p.updateValue();
				}
			} else if(kind == 1) { // 月餐
				if(values.length > 20) {
					$.toptip('月餐不能超过20天！', 1000, 'error');
					p.value = values.splice(0, 20);
					p.updateValue();
				}
			} else if(kind == 'custom') {
				remain_times = parseInt($('#remain-times').text());
				if(values.length > remain_times) {
					values.pop();
					p.value = values;
					p.updateValue();
				}
			}
			// console.log(p);
			// console.log($('#send-info-input').val());
			// num = values.length;
			// last_num = $('#date-picker').attr('data-last-num') == undefined ? 0 : parseInt($('#date-picker').attr('data-last-num'));

			// if(num == 0) {
			// 	$('#send-info-input').val('');
			// } else if(num > last_num) {
			// 	date = handleDate(values[num - 1]);
			// 	has = $('.weui-picker-container [data-date=' + date + ']').hasClass('sg-day-disabled');

			// 	if(has) {

					// console.log('before: ', p.value());
					// p.setValue(values.splice(num - 1, 1));
					// console.log('after: ', p.value());

				// } else {
				// 	val = $('#send-info-input').val();
				// 	if(val == '') {
				// 		val = values[num - 1] + ':1';
				// 	} else {
				// 		val += ',' + values[num - 1] + ':1';
				// 	}
				// 	$('#send-info-input').val(val);
					// $('#date-picker').attr('data-date', values[num - 1]);
					// $('#send-num-modal .sg-modal-title h5').text('配送份数选择 ' + values[num - 1]);
					// $('#send-num-modal .sg-select-item').removeClass('sg-selected');
					// $('#send-num-modal').removeClass('sg-hide');
			// 	}

			// } else if(num < last_num) {
			// 	items = $('#send-info-input').val().split(',');
			// 	for(var i = 0; i < items.length; ++i) {
			// 		if($.inArray(items[i].split(':')[0], values) == -1) {
			// 			items.splice(i, 1);
			// 		}
			// 	}
			// 	$('#send-info-input').val(items.join(','));
			// }
			// $('#date-picker').attr('data-last-num', num);
			// updateNumAndMoney();
			// $('#date-picker').attr('data-last-num', num);
			// console.log($('#send-info-input').val(), values);

			// $('#date-picker').attr('data-last-val');
			// num = values.length;
			// last_num = $('#date-picker').attr('data-last-num');
			// val = $.trim($('#send-info-input').val());
			// new_val = '';

			// if(num > last_num) {
			// 	add_date = values[num - 1];
			// 	date_tmp = handleDate(add_date);
			// 	has = $('.weui-picker-container [data-date=' + date_tmp + ']').hasClass('sg-day-disabled');
			// 	if(has) {

			// 	} else {
			// 		add_num = '';
			// 		if(val == '') {
			// 			new_val = values[num - 1] + ':1';
			// 			add_num = 1;
			// 		} else {
			// 			items = val.split(',');
			// 			is_add_new = (num != items.length);
			// 			if(is_add_new) {
			// 				new_val = val + ',' + values[num - 1] + ':1';
			// 				add_num = 1;
			// 			} else {	
			// 				items = val.split(',');
			// 				for(var i = 0; i < items.length; ++i) {
			// 					arr = items[i].split(':');
			// 					if(arr[0] == add_date) {
			// 						tmp = arr[0] + ':' + (parseInt(arr[1]) + 1);
			// 						add_num = (parseInt(arr[1]) + 1);
			// 					} else {
			// 						tmp = items[i];
			// 					}
			// 					if(i == 0) {
			// 						new_val = tmp;
			// 					} else {
			// 						new_val = new_val + ',' + tmp;
			// 					}
			// 				}
			// 			}
			// 		}		
			// 		add_type = $('#food-select').val();
			// 		$('#send-info-input').val(new_val);
			// 		$('#date-picker').attr('data-last-num', num);
			// 		updateNumAndMoney();
			// 		type = (add_type == 1 ? '男士餐' : (add_type == 2 ? '女士餐' : '工作餐'));
			// 		text = add_date + '：' + add_num + '份' + type;
			// 		$.toptip(text, 800, 'success');
			// 	}
			// } else if(num < last_num) {
			// 	if(val != '') {
			// 		items = val.split(',');
			// 		remove_val = '';
			// 		for(var i = 0; i < items.length; ++i) {
			// 			if($.inArray(items[i].split(':')[0], values) == -1) {
			// 				remove_val = items[i].split(':')[0];
			// 				break;
			// 			}
			// 		}
			// 		if(remove_val != '') {
			// 			$('#date-picker').attr('data-last-num', num);
			// 			$('#date-picker').attr('data-last-val', remove_val);
			// 			p.addValue(remove_val);
			// 		}
			// 	}
			// }


		},
		onDayClick: function (p, dayContainer, year, month, day) {
			// event.stopImmediatePropagation();
			//console.log(event.isImmediatePropagationStopped());
		},
		onOpen: function (p) {
			disabledDays();
			change = $('#food-set').attr('data-change');
			if(change == 1) {
				$this = $('#food-set option:selected');
				kind = $this.attr('data-kind');
				if(kind == 0) {
					five_days = "{{ $five_days }}";
					five_days = five_days.split(',');
					p.value = five_days;
					p.updateValue();
					// $("#date-picker").val('');
					// for(var i = 0; i < five_days.length; ++i) {
					// 	p.addValue(five_days[i]);
					// }
				} else if(kind == 1) {
					twenty_days = "{{ $twenty_days }}";
					twenty_days = twenty_days.split(',');
					p.value = twenty_days;
					p.updateValue();
					// $("#date-picker").val('');
					// for(var i = 0; i < twenty_days.length; ++i) {
					// 	p.addValue(twenty_days[i]);
					// }
				} else {
					p.value = [];
					p.updateValue();
				}
			}
			// console.log('ok');

		},
		onMonthAdd: function (p, monthContainer) {
			disabledDays();
		}
	});
	
	$('#date-picker').on('click', function(event) {
		$('#inline-calendar').slideDown();
		event.stopImmediatePropagation();
	});
	$('#sg-container').on('click', function(event) {
		$('#inline-calendar').slideUp();
	});
	
	// console.log($("#date-picker").calendar());
	// $("#date-picker").calendar("setValue", ["2017-11-03"]);
	// $("#date-picker").calendar('open');
	// $("#date-picker").calendar('close');
	kind = $('#food-set option:selected').attr('data-kind');
	$('#food_set_input').val(kind);
	$('#food-set').change(function(event) {
		$(this).attr('data-change', 1);

		kind = $('#food-set option:selected').attr('data-kind');
		$('#food_set_input').val(kind);
		$("#date-picker").calendar('open');
		$("#date-picker").calendar('close');
		// $('#inline-calendar').slideDown();
		event.stopImmediatePropagation();

	// 	updateNumAndMoney();
	});

	$('#send-num-modal').on('click', '.hide-modal', function() {
		if($('#send-num-modal .sg-select-item.sg-selected').length != 0) {
			length = parseInt($('#date-picker').attr('data-last-num'));
			num = $('#send-num-modal .sg-select-item.sg-selected').attr('data-num');
			text = length == 1 ? $('#date-picker').attr('data-date') + ':' + num : $('#send-info-input').val() + ',' + $('#date-picker').attr('data-date') + ':' + num;
			$('#send-info-input').val(text);

			updateNumAndMoney();
		}

		$('#send-num-modal').addClass('sg-hide');
		event.stopImmediatePropagation();
		$("#date-picker").calendar("open");
	}).on('click', '.sg-select-item', function() {

		if($(this).hasClass('sg-selected')) {
			$('#send-num-modal .sg-select-item').removeClass('sg-selected');
		} else {
			$('#send-num-modal .sg-select-item').removeClass('sg-selected');
			$(this).addClass('sg-selected');
		}


		// num = parseInt($('#date-picker').attr('data-last-num'));
		// text = num == 1 ? $('#date-picker').attr('data-date') + ':' + $(this).attr('data-num') : $('#send-info-input').val() + ',' + $('#date-picker').attr('data-date') + ':' + $(this).attr('data-num');
		// $('#send-info-input').val(text);

		// $('#send-num-modal').addClass('sg-hide');
		// event.stopImmediatePropagation();
		// $("#date-picker").calendar("open");
	}).on('click', '.hide-all', function() {
		if($('#send-num-modal .sg-select-item.sg-selected').length != 0) {
			length = parseInt($('#date-picker').attr('data-last-num'));
			num = $('#send-num-modal .sg-select-item.sg-selected').attr('data-num');
			text = length == 1 ? $('#date-picker').attr('data-date') + ':' + num : $('#send-info-input').val() + ',' + $('#date-picker').attr('data-date') + ':' + num;
			$('#send-info-input').val(text);

			updateNumAndMoney();
		}

		$('#send-num-modal').addClass('sg-hide');
	});

	$('#food-select').change(function() {
		updateFoodInfo();
		$("#date-picker").calendar('open');
		$("#date-picker").calendar('close');
		updateNumAndMoney();
	});

	// $('#send-time').datetimepicker({
	// 	format: "yyyy-mm-dd hh:ii",
	// 	autoclose: true,
	// 	todayBtn: true,
	// 	language: 'zh-CN',
	// 	pickerPosition: "bottom-left"
	// });

	$('#pay-btn').on('click', function() {
		full_address = '';
		if($('#address-select').val() == 'new-address') {
			// province = $('#cmbProvince option:selected').text();
			// city = $('#cmbCity option:selected').text();
			// area = $('#select-city option:selected').text();
			city = $('#select-city').val();
			county = $('#select-county').val();
			full_address = city + county + $('#detail-address').val();
			$('#full-address').val(full_address);
		} else {
			full_address = $('#address-select').val();
			$('#full-address').val(full_address);
		}
		// console.log()
		user_name = $.trim($('#user-name').val());
		// $('#order-form').submit();
		if(user_name == '') {
			$.toptip('姓名不能为空！', 1000, 'error');
		} else {
			tel = $.trim($('#send-tel').val());
			if(tel == '') {
				$.toptip('手机不能为空！', 1000, 'error');
			} else {
				if($.trim(full_address) == '') {
					$.toptip('地址不能为空！', 1000, 'error');
				} else {
					num = parseInt($('#total_num_input').val());
					kind = $('#food_set_input').val();
					if(kind == 'custom' && num == 0) {
						$.toptip('至少购买一天！', 1000, 'error');
					} else {
						$('#order-form').submit();
					}
				}
			}
		}

		// if($.trim($('#full-address').val()) == '') {
		// 	$.toptip('地址不能为空！', 1000, 'error');
		// } else {}
		// num = parseInt($('#total_num_input').val());
		// if(num != 0) {
		// 	$('#order-form').submit();
		// }
		// console.log($('#full-address').val());
		// console.log($('#date-picker').val(), $('#send-info-input').val());
		// console.log($('#select-city option:selected').text());
	});

	$('#share-btn').on('click', function() {
		if($('#address-select').val() == 'new-address') {
			province = $('#cmbProvince option:selected').text();
			city = $('#cmbCity option:selected').text();
			area = $('#select-city option:selected').text();
			full_address = province + ' ' + city + ' ' + area + ' ' + $('#detail-address').val();
			$('#full-address').val(full_address);
		}
		num = parseInt($('#total_num_input').val());
		if(num != 0) {
			url = "{{ route('orders.generate') }}" + '?share=1';
			$('#order-form').attr('action', url).submit();
		}
		// console.log($('#select-city option:selected').text());
	});

	$('#address-select').change(function() {
		select = $(this).val();
		if(select == 'new-address') {
			$('.add-new-address').removeClass('sg-hide').addClass('sg-show');
		} else {
			$('.add-new-address').removeClass('sg-show').addClass('sg-hide');
		}
	});

	// $('#user-name').on('focus', function() {
	// 	event.preventDefault();
	// });

    // $('#order-form').bootstrapValidator({
    //     message: '填写的值无效！',
    //     feedbackIcons: {
    //         valid: 'glyphicon glyphicon-ok',
    //         invalid: 'glyphicon glyphicon-remove',
    //         validating: 'glyphicon glyphicon-refresh'
    //     },
    //     fields: {
    //         tel: {
    //             message: '手机不能为空！',
    //             validators: {
    //                 notEmpty: {
    //                     message: '手机不能为空！'
    //                 },
    //             }
    //         },
    //         real_name: {
    //             message: '姓名不能为空！',
    //             validators: {
    //                 notEmpty: {
    //                     message: '姓名不能为空！'
    //                 },
    //             }
    //         }
    //     }
    // });
});
</script>
@endsection