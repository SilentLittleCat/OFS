@extends('web.layouts.master')

@section('header')
<style type="text/css">
	.user-info {
		margin-top: 30px;
	}
</style>
@endsection

@section('content')
<div class="container sg-container">
	<div class="row">
		<div class="col-xs-4 col-sm-4 col-xs-offset-4 col-sm-offset-4 sg-centered">
			<i class="fa fa-user fa-4x" aria-hidden="true"></i>
			<div>微信昵称</div>
		</div>
	</div>
	<div class="user-info">
		<form class="form-horizontal" role="form" method="POST" id="user-info-form" action="{{ Route('users.update') }}">
			{{ csrf_field() }}

            <div class="weui-cells weui-cells_form">
                <div class="weui-cells">
                    <div class="weui-cell">
                        <div class="weui-cell__bd">
                            <input type="text" name="real_name" class="weui-input" placeholder="姓名" value="{{ Auth::user()->real_name }}">
                        </div>
                    </div>
                </div>
                <div class="weui-cells weui-cells_radio">
			      	<label class="weui-cell weui-check__label" for="x11">
				        <div class="weui-cell__bd">
				          	<p>男</p>
				        </div>
				        <div class="weui-cell__ft">
				          	<input type="radio" class="weui-check sg-hide" value="1" name="gender" id="x11">
				          	<span class="weui-icon-checked"></span>
				        </div>
			      	</label>
			      	<label class="weui-cell weui-check__label" for="x12">
				        <div class="weui-cell__bd">
				          	<p>女</p>
				        </div>
				        <div class="weui-cell__ft">
				          	<input type="radio" class="weui-check sg-hide" value="2" name="gender" id="x12">
				          	<span class="weui-icon-checked"></span>
				        </div>
			      	</label>
                </div>
                <div class="weui-cells">
                    <div class="weui-cell">
                        <div class="weui-cell__bd">
                            <input type="text" name="birthday" class="weui-input" placeholder="生日" value="{{ Auth::user()->birthday }}" id="birthday">
                        </div>
                    </div>
                </div>
                <div class="weui-cells">
                    <div class="weui-cell">
                        <div class="weui-cell__bd">
                            <input type="text" name="weight" class="weui-input" placeholder="体重，默认单位KG" value="{{ Auth::user()->weight }}">
                        </div>
                    </div>
                </div>
                <div class="weui-cells">
                    <div class="weui-cell">
                        <div class="weui-cell__bd">
                            <input name="height" class="weui-input" placeholder="身高，默认单位CM" value="{{ Auth::user()->height }}">
                        </div>
                    </div>
                </div>
                <div class="weui-cells">
                    <div class="weui-cell">
                        <div class="weui-cell__bd">
                            <input name="tel" class="weui-input" placeholder="手机" value="{{ Auth::user()->tel }}">
                        </div>
                    </div>
                </div>
                <div class="weui-cells">
                    <div class="weui-cell">
                        <div class="weui-cell__bd">
                            <button type="submit" class="weui-btn weui-btn_primary" id="edit-user-info">保存</button>
                        </div>
                    </div>
                </div>
            </div>
		</form>
	</div>
</div>
@endsection

@section('footer')
<script type="text/javascript">
$(function() {
	// $('#birthday-input').datepicker({
	// 	autoclose: true
	// });

	$("#birthday").calendar();

	$('#edit-user-info').on('click', function() {
		$('#user-info-form').submit();
	});

	gender = "{{ Auth::user()->gender }}";
	if(gender == '1') {
		$('#x11').prop('checked', true);
	} else if(gender == '2') {
		$('#x12').prop('checked', true);
	}
});
</script>
@endsection