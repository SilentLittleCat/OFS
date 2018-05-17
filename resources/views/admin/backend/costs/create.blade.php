@extends('admin.layout')

@section('header')
<style type="text/css">
    #costs-table .form-control {
        padding: 2px !important;
        margin: 0px;
        font-size: 0.8em;
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
					<h5>添加运营成本</h5>
					<div class="ibox-tools">
						<a class="collapse-link"> <i class="fa fa-chevron-up"></i>
						</a>
					</div>
				</div>
                <form id="create-form" method="POST" action="{{ U('Backend/Cost/storeAll') }}" class="form-horizontal">
                	{{ csrf_field() }}
	                <div class="ibox-content">
	                    <div class="row">
                            <div class="form-group">
                                <div class="control-label col-sm-1">支出时间</div>
                                <div class="col-sm-2">
                                    <input type="text" class="form-control input-sm" name="add_date" id="add-date">
                                </div>
                                <div class="control-label col-sm-1">添加人</div>
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
		                    <table class="table table-striped table-bordered table-hover dataTable" id="costs-table">
		                        <thead>
		                            <tr>
		                            	<th>操作</th>
		                                <th>使用场景</th>
		                                <th>费用类型</th>
		                                <th>单位时间</th>
		                                <th>金额</th>
		                                <th>备注信息</th>
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
            <select class="form-control input-sm">
                @foreach($scenes as $scene)
                    <option value="{{ $scene->name }}">{{ $scene->name }}</option>
                @endforeach
            </select>
        </td>
        <td class="sg-td" data-name="type">
            <select class="form-control input-sm">
                @foreach($cost_classes as $cost_class)
                    <option value="{{ $cost_class->name }}">{{ $cost_class->name }}</option>
                @endforeach
            </select>
        </td>
        <td class="sg-td" data-name="time">
            <select class="form-control input-sm">
                @foreach(dict()->get('costs', 'times') as $key => $val)
                    <option value="{{ $val }}">{{ $val }}</option>
                @endforeach
            </select>
        </td>
        <td class="sg-td" data-name="money">
            <input type="text" class="form-control input-sm">
        </td>
        <td class="sg-td" data-name="remarks">
            <textarea rows="3" class="form-control input-sm"></textarea>
        </td>
    </tr>
</table>
</div>
@endsection

@section('footer')
<script type="text/javascript">
$(function() {
    $('#add-date').datepicker({
    	autoclose: true
    });

    $('#save_table').on('click', function() {
    	$('#create-form').submit();
    });

    $('#cancel_table').on('click', function() {
    	window.location = "{{ U('Backend/Cost/index') }}";
    });

    function addNewRow()
    {
    	rows = parseInt($('#sg-table-wrapper').attr('data-rows'));
    	$new_tds = $('.sg-td', '#table_row_template');

    	html = "<tr>";
    	
    	for(var i = 0; i < $new_tds.length; ++i) {
    		$item = $($new_tds[i]);
    		if($item.attr('data-name') == 'scene' || $item.attr('data-name') == 'type' || $item.attr('data-name') == 'time') {
    			$item.find('select').attr('name', 'data[' + rows + '][' + $item.attr('data-name') + ']');
    		} else if($item.attr('data-name') == 'remarks') {
    			$item.find('textarea').attr('name', 'data[' + rows + '][' + $item.attr('data-name') + ']');
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