@extends('admin.layout')

@section('header')
<style type="text/css">
    #inventory-table .form-control {
        padding: 2px !important;
        margin: 0px;
        font-size: 0.8em;
    }
    #sg-table-wrapper {
    	overflow: scroll;
    	overflow-y: hidden;
    }
</style>
<script type="text/javascript">
function changeThreeLevel(val)
{
    // value = $(event.target).children('option:selected').val();
    $(event.target).parent().next().find('option').addClass('sg-hide');
    $(event.target).parent().next().find('option[data-fa=' + val + ']').removeClass('sg-hide');
    $(event.target).parent().next().find('option[data-fa=not_class]').removeClass('sg-hide');
    $(event.target).parent().next().find('option[data-fa=not_class]')[0].selected = true;
    //console.log($(event.target).parent().next().find('option[data-fa=' + val + ']')[0]);
}
function updateUnitAndStandard(val)
{
    $this = $(event.target);
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
        },
        type: 'POST',
        url: "{{ U('Backend/Inventory/ajaxName') }}",
        data: {
            name: val
        },
        dataType: 'json',
        success: function (data) {
            if(data.status == 1) {
                $this.parent().next().find('input').val(data.standard);
                $this.parent().next().next().find('input').val(data.unit);
                $this.parent().next().next().next().find('input').val(data.price);
            }
        },
        error: function (data) {
            console.log('Error:', data);
        }
    });
}

function updateName(val) {
    // names = "{{ $names }}";
    // names = names.split(',');
    $this = $(event.target).parent().next().find('input');
    // $this.autocomplete({
    //     minLength: 0,
    //     source: names
    // });
    // $this.focus();
    // $this.click(function() {
    //     $this.autocomplete('search', '');
    // });
    // $this.autocomplete('search', '');
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
        },
        type: 'POST',
        url: "{{ U('Backend/Inventory/getNamesFromThree') }}",
        data: {
            three: val
        },
        dataType: 'json',
        success: function (data) {
            if(data.status == 1) {
                $this.autocomplete({
                    minLength: 0,
                    source: data['data']
                });
                $this.focus();
                $this.click(function() {
                    $this.autocomplete('search', '');
                });
                $this.autocomplete('search', '');
            }
        },
        error: function (data) {
            console.log('Error:', data);
        }
    });
}

function computeMoneyWhenNum(val) {
    price = parseFloat($(event.target).parent().prev().find('input').val());
    num = parseInt(val);
    total_money = price * num;
    if(isNaN(total_money)) {
        total_money = 0;
    }
    $(event.target).parent().next().find('input').val(total_money);
}

function computeMoneyWhenPrice(val) {
    price = parseFloat(val);
    num = parseInt($(event.target).parent().next().find('input').val());
    total_money = price * num;
    if(isNaN(total_money)) {
        total_money = 0;
    }
    $(event.target).parent().next().next().find('input').val(total_money);
}
</script>
@endsection

@section('content')
<div class="wrapper wrapper-content animated fadeInRight" id="main-content">
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
                    <h5>添加出库详情</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link"> <i class="fa fa-chevron-up"></i>
                        </a>
                    </div>
                </div>
                <form id="inventory_form" method="POST" action="{{ U('Backend/Inventory/outSaveAll') }}" class="form-horizontal">
                	{{ csrf_field() }}
	                <div class="ibox-content">
	                    <div class="row">
                            <div class="form-group">
                                <div class="control-label col-sm-1">出库时间</div>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control input-sm" name="time" id="enter_time">
                                </div>
                                <div class="control-label col-sm-1">出库人</div>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control input-sm" name="username">
                                </div>
                                <div class="col-sm-4 col-sm-offset-1">
                                	<div class="btn-group pull-right">
                                		<div class="btn btn-sm btn-success" id="add_new_row">添加一行</div>
                                		<div class="btn btn-sm btn-primary btn-save" id="save_table">保存</div>
                                		<div class="btn btn-sm btn-default btn-cancel" id="cancel_table">取消</div>
                                	</div>
                                </div>
                            </div>
	                    </div>
	                    <div id="sg-table-wrapper" data-rows="0">
		                    <table class="table table-striped table-bordered table-hover dataTable" id="inventory-table">
		                        <thead>
		                            <tr>
		                            	<th>操作</th>
		                                <th>使用场景</th>
		                                <th>二级分类</th>
		                                <th>三级分类</th>
		                                <th>产品名</th>
		                                <th>规格</th>
		                                <th>单位</th>
                                        <th>单价</th>
		                                <th>出库量</th>
		                                <th>金额</th>
		                                <th>折旧年</th>
		                                <th>备注</th>
		                            </tr>
		                        </thead>
		                        <tbody></tbody>
		                    </table>
	                    </div>
	                </div>
	            </form>
            </div>
        </div>
    </div>
</div>
<div id="table_row_template" style="display: none">
<table>
    <tr>
    	<td class="sg-td" data-name="operation">
    		<div class="btn-group">
    			<div class="btn btn-sm btn-danger btn-delete">删除</div>
    		</div>
    	</td>
        <td class="sg-td" data-name="scene">
            <select class="form-control input-sm" style="width: 120px">
                @foreach($scenes as $scene)
                    <option value="{{ $scene->name }}">{{ $scene->name }}</option>
                @endforeach
            </select>
        </td>
        <td class="sg-td two-level" data-name="two_level">
            <select class="form-control input-sm" style="width: 120px">
                @foreach($two_levels as $two_level)
                    <option value="{{ $two_level->name }}">{{ $two_level->name }}</option>
                @endforeach
            </select>
        </td>
        <td class="sg-td three-level" data-name="three_level">
            <select class="form-control input-sm" style="width: 120px">
                <option value="未分类" data-fa="not_class" data-not="not_class">{{ '选择三级分类' }}</option>
                @foreach($three_levels as $three_level)
                    <option value="{{ $three_level->name }}" data-fa="{{ $three_level->fa_class }}" class="{{ $three_level->fa_class == $two_level->first()->name ? '' : 'sg-hide' }}">{{ $three_level->name }}</option>
                @endforeach
            </select>
        </td>
        <td class="sg-td" data-name="name">
            <input type="text" class="form-control input-sm" style="width: 120px">
        </td>
        <td class="sg-td" data-name="standard">
            <input type="text" class="form-control input-sm" style="width: 120px">
        </td>
        <td class="sg-td" data-name="unit">
            <input type="text" class="form-control input-sm" style="width: 120px">
        </td>
        <td class="sg-td" data-name="price">
            <input type="text" class="form-control input-sm" style="width: 120px">
        </td>
        <td class="sg-td" data-name="num">
            <input type="text" class="form-control input-sm" style="width: 120px">
        </td>
        <td class="sg-td" data-name="total_money">
            <input type="text" class="form-control input-sm" style="width: 120px">
        </td>
        <td class="sg-td" data-name="old_years">
            <select class="form-control input-sm" style="width: 120px">
                <option value="null">无</option>
                @foreach(dict()->get('inventory', 'old_years') as $key => $val)
                    <option value="{{ $key }}" >{{ $val }}</option>
                @endforeach
            </select>
        </td>
        <td class="sg-td" data-name="remarks">
            <textarea rows="3" class="form-control input-sm" style="width: 120px"></textarea>
        </td>
    </tr>
</table>
</div>
@endsection

@section('footer')
<script type="text/javascript">
$(function() {
    $('#enter_time').datepicker();

    $('#save_table').on('click', function() {
    	$('#inventory_form').submit();
    });

    $('#cancel_table').on('click', function() {
    	window.location = "{{ U('Backend/Inventory/outIndex') }}";
    });

    function addNewRow()
    {
    	rows = parseInt($('#sg-table-wrapper').attr('data-rows'));
    	$new_tds = $('.sg-td', '#table_row_template');

    	html = "<tr>";
    	
    	for(var i = 0; i < $new_tds.length; ++i) {
    		$item = $($new_tds[i]);
            if($item.attr('data-name') == 'scene' || $item.attr('data-name') == 'old_years') {
                $item.find('select').attr('name', 'data[' + rows + '][' + $item.attr('data-name') + ']');
            } else if($item.attr('data-name') == 'two_level') {
                $item.find('select').attr('name', 'data[' + rows + '][' + $item.attr('data-name') + ']');
                $item.find('select').attr('onchange', 'changeThreeLevel(this.value)');
            } else if($item.attr('data-name') == 'three_level') {
                $item.find('select').attr('name', 'data[' + rows + '][' + $item.attr('data-name') + ']');
                $item.find('select').attr('onchange', 'updateName(this.value)');
            } else if($item.attr('data-name') == 'remarks') {
                $item.find('textarea').attr('name', 'data[' + rows + '][' + $item.attr('data-name') + ']');
            } else if($item.attr('data-name') == 'name') {
                $item.find('input').attr('name', 'data[' + rows + '][' + $item.attr('data-name') + ']');
                $item.find('input').attr('onblur', 'updateUnitAndStandard(this.value)');
            } else if($item.attr('data-name') == 'num') {
                $item.find('input').attr('name', 'data[' + rows + '][' + $item.attr('data-name') + ']');
                $item.find('input').attr('onblur', 'computeMoneyWhenNum(this.value)');
            } else if($item.attr('data-name') == 'price') {
                $item.find('input').attr('name', 'data[' + rows + '][' + $item.attr('data-name') + ']');
                $item.find('input').attr('onblur', 'computeMoneyWhenPrice(this.value)');
            } else if($item.attr('data-name') != 'operation') {
                $item.find('input').attr('name', 'data[' + rows + '][' + $item.attr('data-name') + ']');
            }

    		html = html + '<td class=' + $item.attr('class')  +'>' + $item.html() + '</td>';
            // console.log('<td class=' + $item.attr('class')  +'>' + $item.html() + '</td>');
    	}
    	html = html + "</tr>";
    	// console.log(html);
    	$('#sg-table-wrapper tbody').append(html);
    	++rows;
    	$('#sg-table-wrapper').attr('data-rows', rows);
    }

    addNewRow();

    $('#add_new_row').on('click', function() {
    	addNewRow();
    });

    $('#sg-table-wrapper').on('click', '.btn-delete', function() {
    	$(this).closest('tr').remove();
    });
});
</script>
@endsection