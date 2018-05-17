@extends('admin.layout')

@section('header')
<style type="text/css">
	
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
					<h5>商品列表</h5>
					<div class="ibox-tools">
						<a class="collapse-link"> <i class="fa fa-chevron-up"></i>
						</a>
					</div>
				</div>
				<div class="ibox-content">
					<div class="row">
    					<div class="col-sm-4">
    						<form method="GET" action="{{ U('Backend/Score/index') }}" accept-charset="UTF-8">
        						<div class="input-group">
                                    <input type="text" placeholder="" name="keyword" class="input-sm form-control" value="{{ $keyword }}"> 
                                    <span class="input-group-btn">
                                    	<button type="submit" class="btn btn-sm btn-primary">搜索</button>
                                    </span>
        						</div>
    						</form>
    					</div>
    					<div class="col-sm-4 pull-right">
    						<div class="btn btn-sm btn-primary pull-right" id="add_new_good">新增</div>
    					</div>
					</div>
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
                            			@if($good->poster == null || $good->poster == '')
											<td>无</td>
										@else
											<td>
												<img src="{{ $good->poster }}" class="img-thumbnail" width="100px">
											</td>
										@endif
										<td>{{ $good->info }}</td>
										<td>{{ $good->score }}</td>
										<td>{{ $good->created_at }}</td>
										<td>
											<div class="btn-group">
												<div class="btn btn-sm btn-success btn-edit" data-id="{{ $good->id }}">编辑</div>
												<div class="btn btn-sm btn-danger btn-delete" data-id="{{ $good->id }}" data-name="{{ $good->name }}">删除</div>
											</div>
										</td>
                            		</tr>
                            	@endforeach
							@endif
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="delete-good-modal" tabindex="-1" role="dialog" aria-labelledby="delete-good-modal-label" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="delete-good-modal-label">
					
				</h4>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default pull-left" data-dismiss="modal">
					取消
				</button>
				<button type="button" class="btn btn-success" id="delete-good-confirm-btn">
					确认
				</button>
			</div>
		</div>
	</div>
</div>
<form method="POST" style="display: none;" id="delete-good-form">
    {{ csrf_field() }}
</form>
@endsection

@section('footer')
<script type="text/javascript">
$(function() {
	$('#add_new_good').on('click', function() {
		url = "{{ U('Backend/Score/create') }}";
		window.location = url;
	});

	$('#goods-table').on('click', '.btn-edit', function() {
		url = "{{ U('Backend/Score/edit') }}" + '?id=' + $(this).attr('data-id');
		window.location = url;
	}).on('click', '.btn-delete', function() {
		$('#delete-good-modal').attr('data-id', $(this).attr('data-id'));
		label = '确定要删除  ' + $(this).attr('data-name') + '  吗？';
		$('#delete-good-modal-label').text(label);
		$('#delete-good-modal').modal('show');
	});

	$('#delete-good-confirm-btn').on('click', function() {
		url = "{{ U('Backend/Score/delete') }}" + '?id=' + $('#delete-good-modal').attr('data-id');
		$('#delete-good-form').attr('action', url).submit();
	});
});
</script>
@endsection