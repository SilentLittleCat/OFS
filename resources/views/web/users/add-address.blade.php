@extends('web.layouts.master')

@section('header')
<style type="text/css">
	.add-address-form {
		margin-top: 30px;
	}
	.sg-hide {
		display: none;
	}
</style>
@endsection

@section('content')
<div class="container sg-container">
	<div class="add-address-form">
		<form role="form" method="POST" action="{{ Route('users.address.store') }}" id="address-form">
			{{ csrf_field() }}

			<div class="weui-cells weui-cells_form">
				<div class="weui-cells">
					<div class="weui-cell">
						<div class="weui-cell__bd">
							<input type="text" class="weui-input" id="user-name" name="real_name" value="{{ Auth::user()->real_name }}" placeholder="姓名">
							<input type="text" class="form-control input-sm sg-hide" id="full-address" name="full_address" value="">
						</div>
					</div>
					<div class="weui-cell">
						<div class="weui-cell__bd">
							<input type="text" class="weui-input" name="tel" value="{{ Auth::user()->tel }}" required placeholder="手机" id="tel">
						</div>
					</div>
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
							<input type="text" class="weui-input" placeholder="详细地址" id="detail-address">
						</div>
					</div>
				</div>
			</div>
			<div class="weui-btn-area">
				<div class="weui-btn weui-btn_primary" id="save-address">保存</div>
			</div>
		</form>
	</div>
</div>
@endsection

@section('footer')
<script type="text/javascript">
$(function() {
	$('#save-address').on('click', function() {
		// province = $('#cmbProvince option:selected').text();
		// city = $('#cmbCity option:selected').text();
		username = $.trim($('#user-name').val());
		if(username == '') {
			$.toptip('姓名不能为空！', 1000, 'error');
		} else {
			tel = $.trim($('#tel').val());
			if(tel == '') {
				$.toptip('手机不能为空！', 1000, 'error');
			} else {
				city = $('#select-city').val();
				county = $('#select-county').val();
				full_address = $.trim(city + county + $('#detail-address').val());
				$('#full-address').val(full_address);
				if(full_address == '') {
					$.toptip('地址不能为空！', 1000, 'error');
				} else {
					$('#address-form').submit();
				}
			}
		}
		// console.log($('#cmbArea option:selected').text());
	});

	$('#select-city').on('click', function() {
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

	// $('#address-form').bootstrapValidator({
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
	//         username: {
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