@extends('admin.layout')

@section('header')
<style type="text/css">
    #sg-table-wrapper {
    	overflow: scroll;
    	overflow-y: hidden;
    }
    #inventory-table td > div {
    	/*min-width: 80px;*/
    }
    .datepicker {
    	z-index: 9999 !important;
    }
    .not-padding-right {
    	padding-right: 0px !important;
    }
</style>
<script type="text/javascript">
function changeThreeLevel(val)
{
    // value = $(event.target).children('option:selected').val();
    $(event.target).parent().next().find('option').addClass('sg-hide');
    $(event.target).parent().next().find('option[data-fa=' + val + ']').removeClass('sg-hide');
    $(event.target).parent().next().find('option[data-fa=' + val + ']')[0].selected = true;
    //console.log($(event.target).parent().next().find('option[data-fa=' + val + ']')[0]);
}
</script>
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
					<div class="btn-group">
						<div class="btn btn-success disabled btn-lg" id="manage-btn">库存管理</div>
						<div class="btn btn-info" id="enter-btn">库存——入库</div>
						<div class="btn btn-primary" id="out-btn">库存——出库</div>
					</div>
					<div class="ibox-tools">
						<a class="collapse-link"> <i class="fa fa-chevron-up"></i>
						</a>
					</div>
				</div>
				<div class="ibox-content">
					<div class="row">
						<form class="form-horizontal" role="form">
							<div class="form-group">
								<div class="col-sm-1">
									<div class="btn btn-sm btn-success" id="filter-select" style="margin-left: 20px">筛选</div>
								</div>
								<div class="col-sm-2">
									<select class="form-control input-sm" name="scene" style="font-size: 0.8em" id="select-scene">
										<option value="all" {{ $filter_scene == null ? 'selected' : '' }}>全部</option>
										@foreach($scenes as $scene)
											<option value="{{ $scene->name }}" {{ $filter_scene == $scene->name ? 'selected' : '' }}>{{ $scene->name }}</option>
										@endforeach
									</select>
								</div>
								<div class="col-sm-2">
									<select class="form-control input-sm" name="scene" style="font-size: 0.8em" id="select-buy-class">
										<option value="all" {{ $filter_class == null ? 'selected' : '' }}>全部</option>
										@foreach($buy_classes as $buy_class)
											<option value="{{ $buy_class->name }}" {{ $filter_class == $buy_class->name ? 'selected' : '' }}>{{ $buy_class->name }}</option>
										@endforeach
									</select>
								</div>
								<div class="col-sm-2">
									<input type="text" class="form-control input-sm" id="sort_begin_time" name="sort_begin_time" placeholder="开始时间" style="font-size: 0.8em" id="sort-begin-time" value="{{ $filter_begin_time }}">
								</div>
								<div class="col-sm-2">
									<input type="text" class="form-control input-sm" id="sort_end_time" name="sort_end_time" placeholder="结束时间" style="font-size: 0.8em" id="sort-end-time" value="{{ $filter_end_time }}">
								</div>
								<div class="col-sm-2 control-label pull-left">
									<a href="{{ U('Backend/Inventory/index', ['quick_time' => 'one_week']) }}" class="pull-left">一周</a>
									<a href="{{ U('Backend/Inventory/index', ['quick_time' => 'one_month']) }}" class="pull-left" style="margin-left: 10px; margin-right: 10px;">一月</a>
									<a href="{{ U('Backend/Inventory/index', ['quick_time' => 'one_year']) }}" class="pull-left">一年</a>
								</div>
							</div>
						</form>
					</div>
					<div class="row">
						<div class="col-sm-4 col-sm-offset-6">
							<div class="input-group">
								<input type="text" placeholder="产品名" name="keyword" class="form-control input-sm" id="search-name" value="{{ $search_name }}"> 
                                <span class="input-group-btn">
                                    <div class="btn btn-sm btn-primary" id="search-btn">搜索</div>
                                </span>
							</div>
						</div>
						<div class="col-sm-2">
							<div class="btn btn-sm btn-success pull-right" id="create_inventory">添加入库</div>
						</div>
					</div>
					<div id="sg-table-wrapper">
	                    <table class="table table-striped table-bordered table-hover dataTable" id="inventory-table">
	                        <thead>
	                            <tr>
	                                <th>使用场景</th>
	                                <th>二级分类</th>
	                                <th>三级分类</th>
	                                <th>直接成本</th>
	                                <th>产品名</th>
	                                <th>规格</th>
	                                <th>单位</th>
	                                <th>库存量</th>
	                                <th>单价</th>
	                                <th>总价</th>
	                                <th>状态</th>
	                                <th>折旧年</th>
	                                <th>折旧率</th>
	                                <th>折旧成本</th>
	                                <th>最近入库时间</th>
	                                <th>最近出库时间</th>
	                                <th>库存操作</th>
	                                <th>操作</th>
	                            </tr>
	                        </thead>
	                        <tbody>
	                            @if(count($inventories) == 0)
	                                <tr>
	                                    <td colspan="18" class="sg-centered">暂无库存信息！</td>
	                                </tr>
	                            @else
	                                @foreach($inventories as $inventory)
	                                    <tr>
	                                        <td><div>{{ $inventory->scene }}</div></td>
	                                        <td><div>{{ $inventory->two_level }}</div></td>
	                                        <td><div>{{ $inventory->name }}</div></td>
	                                        <td><div>{{ $inventory->is_direct_cost == '1' ? '是' : '否' }}</div></td>
	                                        <td><div>{{ $inventory->name }}</div></td>
	                                        <td><div>{{ $inventory->standard }}</div></td>
	                                        <td><div>{{ $inventory->unit }}</div></td>
	                                        <td><div>{{ $inventory->num }}</div></td>
	                                        <td><div>{{ $inventory->price }}</div></td>
	                                        <td><div>{{ $inventory->total_money }}</div></td>
	                                        <td><div>{{ $inventory->status }}</div></td>
	                                        <td><div>{{ $inventory->old_years }}</div></td>
	                                        <td><div>{{ $inventory->old_rate }}</div></td>
	                                        <td><div>{{ $inventory->old_cost }}</div></td>
	                                        <td><div>{{ $inventory->last_enter_time }}</div></td>
	                                        <td><div>{{ $inventory->last_out_time }}</div></td>
	                                        <td>
	                                        	<div class="btn-group">
	                                        		<div class="btn btn-sm btn-primary btn-out" data-id="{{ $inventory->id }}" data-name="{{ $inventory->name }}" data-num="{{ $inventory->num }}">出库</div>
	                                        	</div>
	                                        </td>
	                                        <td>
	                                        	<div class="btn-group">
	                                        		<div class="btn btn-sm btn-success btn-edit" data-id="{{ $inventory->id }}">编辑</div>
	                                        		<div class="btn btn-sm btn-danger btn-delete" data-id="{{ $inventory->id }}" data-name="{{ $inventory->name }}">删除</div>
	                                        	</div>
	                                        </td>
	                                    </tr>
	                                @endforeach
	                            @endif
	                        </tbody>
	                    </table>
					</div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="dataTables_info" id="DataTables_Table_0_info"
                                role="alert" aria-live="polite" aria-relevant="all">每页{{ $inventories->count() }}条，共{{ $inventories->lastPage() }}页，总{{ $inventories->total() }}条。</div>
                        </div>
                        <div class="col-sm-6">
                            <div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_0_paginate">
                                {!! $inventories->setPath('')->appends(Request::all())->render() !!}
                            </div>
                        </div>
                    </div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="delete-modal" tabindex="-1" role="dialog" aria-labelledby="delete-modal-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="delete-modal-label">
                    
                </h4>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">
                    取消
                </button>
                <button type="button" class="btn btn-danger" id="delete-confirm-btn">
                    确认
                </button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="out-modal" tabindex="-1" role="dialog" aria-labelledby="out-modal-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="out-modal-label">
                    出库
                </h4>
            </div>
            <div class="modal-body">
            	<form class="form-horizontal" method="POST" action="{{ U('Backend/Inventory/outFromIndex') }}" id="out-form">
            		
            		<div class="form-group" style="display: none">
            			{{ csrf_field() }}
            			<input type="text" name="id" class="form-control" id="out-id">
            		</div>
            		<div class="form-group">
            			<label class="col-sm-3 control-label">产品名</label>
            			<div class="col-sm-9">
            				<input type="text" name="name" class="form-control" id="out-name" readonly="">
            			</div>
            		</div>
            		<div class="form-group">
            			<label class="col-sm-3 control-label">出库量</label>
            			<div class="col-sm-9">
            				<input type="number" name="num" class="form-control">
            			</div>
            		</div>
            		<div class="form-group">
            			<label class="col-sm-3 control-label">出库人</label>
            			<div class="col-sm-9">
            				<input type="text" name="username" class="form-control">
            			</div>
            		</div>
            		<div class="form-group">
            			<label class="col-sm-3 control-label">出库时间</label>
            			<div class="col-sm-9">
            				<input type="text" class="form-control" name="time" id="out-time" value="{{ Carbon\Carbon::now()->toDateString() }}">
            			</div>
            		</div>
            	</form>
			</div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">
                    取消
                </button>
                <button type="button" class="btn btn-success" id="out-confirm-btn">
                    确认
                </button>
            </div>
        </div>
    </div>
</div>
<form method="POST" style="display: none;" id="delete-form">
    {{ csrf_field() }}

</form>
@endsection

@section('footer')
<script type="text/javascript">
$(function() {
	$('#sort_begin_time').datepicker({
        autoclose: true
    });
	$('#sort_end_time').datepicker({
        autoclose: true
    });
    $('#out-time').datepicker({
        autoclose: true
    });

    $('#out-confirm-btn').on('click', function() {
    	$('#out-form').submit();
    });

	$('#create_inventory').on('click', function() {
		url = "{{ U('Backend/Inventory/createEnter') }}";
		window.location = url;
	});

	$('#out-btn').on('click', function() {
		url = "{{ U('Backend/Inventory/outIndex') }}";
		window.location = url;
	});

	$('#enter-btn').on('click', function() {
		url = "{{ U('Backend/Inventory/enterIndex') }}";
		window.location = url;
	});

	$('#filter-select').on('click', function() {
		url = "{{ U('Backend/Inventory/index') }}" + '?scene=' + $('#select-scene').val() + '&buy_class=' + $('#select-buy-class').val() + '&begin_time=' + $('#sort_begin_time').val() + '&end_time=' + $('#sort_end_time').val();
		window.location = url;
	});

	$('#search-btn').on('click', function() {
		url = "{{ U('Backend/Inventory/index') }}" + '?name=' + $('#search-name').val();
		window.location = url;
	});

	$('#inventory-table').on('click', '.btn-edit', function() {
		window.location = "{{ U('Backend/Inventory/editEnter') }}" + '?id=' + $(this).attr('data-id');
	}).on('click', '.btn-delete', function() {
		$('#delete-modal').attr('data-id', $(this).attr('data-id'));
		$('#delete-modal-label').text('确定要删除  ' + $(this).attr('data-name') + '  吗?');
		$('#delete-modal').modal('show');
	}).on('click', '.btn-out', function() {
		$('#out-id').val($(this).attr('data-id'));
		$('#out-name').val($(this).attr('data-name'));
		$('#out-modal').attr('data-num', $(this).attr('data-num'));
		$('#out-modal').modal('show');
	});

	$('#delete-confirm-btn').on('click', function() {
		url = "{{ U('Backend/Inventory/deleteManage') }}" + '?id=' + $('#delete-modal').attr('data-id');
		$('#delete-form').attr('action', url).submit();
	});

	$('#out-form').bootstrapValidator({
        message: '填写的值无效！',
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            num: {
                message: '填写有误',
                validators: {
                	notEmpty: {
                        message: '出库量不能为空！'
                    },
                    callback: {
                        message: '出库量不能大于库存量且不能为零！',
                        callback: function(value, validator) {
                            $num = parseInt($('#out-modal').attr('data-num'));
                            value = parseInt(value);
                            if($num < value || value == 0) {
                            	return false;
                            }
                            return true;
                        }
                    }
                }
            }
        }
    });
});
</script>
@endsection