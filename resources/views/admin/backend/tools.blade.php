                    <table class="table table-striped table-bordered table-hover dataTable" id="goods-table">
                        <thead>
                            <tr>
                                <th>商品名</th>
                                <th>图片</th>
                                <th>简介</th>
                                <th>兑换积分</th>
                                <th>添加时间</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($goods) == 0)
                                <tr>
                                    <td colspan="6" class="sg-centered">暂无商品！</td>
                                </tr>
                            @else
                                @foreach($goods as $good)
                                    <tr>
                                        <td>{{ $good->name }}</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                    <table class="table table-striped table-bordered table-hover dataTable" id="users-table">
                        <thead>
                            <tr>
                                <th class="sorting" data-sort="id">用户ID</th>
                                <th>微信昵称</th>
                                <th>真实姓名</th>
                                <th>性别</th>
                                <th>电话</th>
                                <th>收货地址</th>
                                <th class="sorting" data-sort="time">注册时间</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($users) == 0)
                                <tr>
                                    <td colspan="8" class="sg-centered">暂无用户！</td>
                                </tr>
                            @else
                                @foreach($users as $user)
                                    <tr>
                                        <td>{{ $user->id }}</td>
                                        <td>{{ $user->wechat_name }}</td>
                                        <td>{{ $user->real_name }}</td>
                                        <td>{{ $user->gender == 1 ? '男' : '女' }}</td>
                                        <td>{{ $user->tel }}</td>
                                        <td>{{ $user->address }}</td>
                                        <td>{{ $user->birthday }}</td>
                                        <td>
                                            <div class="btn-group">
                                                <div class="btn btn-sm btn-danger btn-coupon" data-id="{{ $user->id }}">优惠券</div>
                                                <div class="btn btn-sm btn-info">详情</div>
                                                <div class="btn btn-sm btn-success btn-edit" data-id="{{ $user->id }}">编辑</div>
                                                <div class="btn btn-sm btn-danger">删除</div>
                                                <div class="btn btn-sm btn-primary">订单</div>
                                                <div class="btn btn-sm btn-warning">配送</div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="dataTables_info" id="DataTables_Table_0_info"
                                role="alert" aria-live="polite" aria-relevant="all">每页{{ $users->count() }}条，共{{ $users->lastPage() }}页，总{{ $users->total() }}条。</div>
                        </div>
                        <div class="col-sm-6">
                            <div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_0_paginate">
                                {!! $users->setPath('')->appends(Request::all())->render() !!}
                            </div>
                        </div>
                    </div>
<div class="modal fade" id="-modal" tabindex="-1" role="dialog" aria-labelledby="-modal-label" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="-modal-label">
					
				</h4>
			</div>
			<div class="modal-body">

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default pull-left" data-dismiss="modal">
					取消
				</button>
				<button type="button" class="btn btn-success" id="-confirm-btn">
					确认
				</button>
			</div>
		</div>
	</div>
</div>
<div>
    <label class="radio-inline">
        <input type="radio" name="" value="" checked>是
    </label>
    <label class="radio-inline">
        <input type="radio" name="" value="">否
    </label>
</div>
<form method="POST" style="display: none;" id="delete-project-form">
    {{ csrf_field() }}
    {{ method_field('DELETE') }}
</form>
                    <div class="input-group date" id="birthday-input">
                        <input type="text" class="form-control input-sm">
                        <div class="input-group-addon">
                            <span class="glyphicon glyphicon-th"></span>
                        </div>
                    </div>
                                <div class="input-group date" id="begin_time">
                                    <input type="text" name="begin_time" class="form-control" value="{{ $coupon->begin_time }}">
                                    <div class="input-group-addon">
                                        <span class="glyphicon glyphicon-th"></span>
                                    </div>
                                </div>
<?php
        try {
             DB::transaction(function() use ($coupon) {
                // throw new Exception("Connect failed!");
             });
        } catch (\Exception $e) {
            return back()->withErrors(['存入数据时发生错误！', $e->getMessage()]);
        }
?>
<script type="text/javascript">
$(function() {
    $('#birthday-input').datepicker();
    $('#begin_time').datepicker({
        autoclose: true
    });

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            type: type,
            url: my_url,
            data: {
                
            },
            dataType: 'json',
            success: function (data) {
                
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    $('#defaultForm').bootstrapValidator({
        message: '填写的值无效！',
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            username: {
                message: 'The username is not valid',
                validators: {
                    notEmpty: {
                        message: 'The username is required and can\'t be empty'
                    },
                }
            }
        }
    });

    $('#defaultForm').bootstrapValidator({
        message: '填写的值无效！',
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            username: {
                message: 'The username is not valid',
                validators: {
                    notEmpty: {
                        message: 'The username is required and can\'t be empty'
                    },
                    stringLength: {
                        min: 6,
                        max: 30,
                        message: 'The username must be more than 6 and less than 30 characters long'
                    },
                    regexp: {
                        regexp: /^[a-zA-Z0-9_\.]+$/,
                        message: 'The username can only consist of alphabetical, number, dot and underscore'
                    },
                    emailAddress: {
                        message: 'The input is not a valid email address'
                    },
                    uri: {
                        message: 'The input is not a valid URL'
                    },
                    digits: {
                        message: 'The value can contain only digits'
                    },
                    hexColor: {
                        message: 'The input is not a valid hex color'
                    },
                    zipCode: {
                        country: 'US',
                        message: 'The input is not a valid US zip code'
                    },
                    identical: {
                        field: 'confirmPassword',
                        message: 'The password and its confirm are not the same'
                    },
                    lessThan: {
                        value: 100,
                        inclusive: true,
                        message: 'The ages has to be less than 100'
                    },
                    greaterThan: {
                        value: 10,
                        inclusive: false,
                        message: 'The ages has to be greater than or equals to 10'
                    },
                    remote: {
                        url: 'remote.php',
                        message: 'The username is not available'
                    },
                    different: {
                        field: 'password',
                        message: 'The username and password cannot be the same as each other'
                    },
                    date: {
                        format: 'YYYY/MM/DD',
                        message: 'The birthday is not valid'
                    },
                    choice: {
                        min: 2,
                        max: 4,
                        message: 'Please choose 2 - 4 programming languages you are good at'
                    },
                    callback: {
                        message: 'Wrong answer',
                        callback: function(value, validator) {
                            var items = $('#captchaOperation').html().split(' '), sum = parseInt(items[0]) + parseInt(items[2]);
                            return value == sum;
                        }
                    }
                }
            }
        }
    });
});
</script>