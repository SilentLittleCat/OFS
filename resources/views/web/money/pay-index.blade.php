@extends('web.layouts.master')

@section('header')
<style type="text/css">
	.sg-container {
		margin-top: 70px;
	}
	.sg-hint {
		margin-bottom: 20px;
	}
</style>
@endsection

@section('content')
<div class="main-content sg-container">
    <div class="ofs-header">
        <h3 class="ofs-title">请输入您的充值金额</h3>
    </div>
    <div class="ofs-content-padded">
        <form class="m-t" role="form" accept-charset="UTF-8" method="post" action="{{ route('money.pay.confirm') }}" id="pay-money-form">
            {{ csrf_field() }}
            
            <div class="weui-cells weui-cells_form">
                <div class="weui-cells">
                    <div class="weui-cell">
                        <div class="weui-cell__bd">
                            <input type="number" class="weui-input" placeholder="输入充值金额（元）" name="money" required autocomplete="off">
                        </div>
                    </div>
                </div>
                <div class="weui-btn-area">
					<button class="weui-btn weui-btn_primary" type="submit">确认</button>
				</div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('footer')
<script type="text/javascript">
$(function() {
    // $('#pay-money-form').bootstrapValidator({
    //     message: '填写的值无效！',
    //     feedbackIcons: {
    //         valid: 'glyphicon glyphicon-ok',
    //         invalid: 'glyphicon glyphicon-remove',
    //         validating: 'glyphicon glyphicon-refresh'
    //     },
    //     fields: {
    //         money: {
    //             validators: {
    //             	callback: {
    //                     message: '充值金额无效！',
    //                     callback: function(value, validator) {
    //                        	value = parseFloat(value);
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