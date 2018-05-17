@extends('web.layouts.master')

@section('header')
<style type="text/css">
	.sg-container {
		margin-top: 80px;
	}
	.form-group {
		margin-top: 20px;
	}
	#two-code {
		margin-top: 20px;
		margin-bottom: 40px;
	}
</style>
@endsection

@section('content')
<div class="main-content sg-container">
    <div class="ofs-header">
        <h3 class="ofs-title">账户余额：<span style="font-size: 0.9em" class="sg-money">{{ Auth::user()->remain_money . '元' }}</span></h3>
    </div>
    <div class="ofs-content-padded">
        <form class="m-t" role="form" accept-charset="UTF-8" method="post" action="{{ route('money.back') }}" id="back-money-form" enctype="multipart/form-data">
            {{ csrf_field() }}
            
            <div class="weui-cells weui-cells_form">
                <div class="weui-cells">
                    <div class="weui-cell">
                        <div class="weui-cell__bd">
                            <input type="number" class="weui-input" placeholder="输入提现金额" name="back_money" id="back-money" required>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="weui-cells weui-cells_form">
                <div class="weui-cells">
                    <div class="weui-cell">
                        <div class="weui-cell__bd">
                            <input type="file" class="weui-input" name="wechat_code_img" required accept="image/*">
                        </div>
                    </div>
                </div>
            </div>
            <div class="weui-cells__tips">上传提现二维码（限微信），图片不能超过2M</div>

            <div class="weui-btn-area" id="confirm-btn">
				<button class="weui-btn weui-btn_primary" type="submit">确认</button>
			</div>
        </form>
    </div>
</div>
@endsection

@section('footer')
<script type="text/javascript">
$(function() {
    // $('#back-money-form').bootstrapValidator({
    //     message: '填写的值无效！',
    //     feedbackIcons: {
    //         valid: 'glyphicon glyphicon-ok',
    //         invalid: 'glyphicon glyphicon-remove',
    //         validating: 'glyphicon glyphicon-refresh'
    //     },
    //     fields: {
    //         back_money: {
    //             validators: {
    //             	callback: {
    //                     message: '提现金额超过账户余额或者无效！',
    //                     callback: function(value, validator) {
    //                        	max_num = parseFloat("{{ Auth::user()->remain_money }}");
    //                        	value = parseFloat(value);
    //                        	if(value > max_num) {
    //                        		return false;
    //                        	}
    //                         return /^[1-9]{1}\d*(.\d{1,2})?$|^0.\d{1,2}$/.test(value);
    //                     }
    //                 }
    //             }
    //         }
    //     }
    // });
});
</script>
@endsection