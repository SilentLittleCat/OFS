@extends('admin.layout')

@section('header')
<style type="text/css">
	.datepicker {
		z-index:9999 !important
	}
	.select2-container {
		z-index:8000 !important
	}
	.select2-search__field {
		z-index:9000 !important
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
					<h5>配送范围管理</h5>
					<div class="ibox-tools">
						<a class="collapse-link"> <i class="fa fa-chevron-up"></i>
						</a>
					</div>
				</div>
				<div class="ibox-content">
					<div class="row">
    					<div class="col-sm-4 pull-right">
    						<div class="btn btn-sm btn-primary pull-right" id="add-range">添加</div>
    					</div>
					</div>
					<table class="table table-striped table-bordered table-hover dataTable" id="send-range-table">
						<thead>
							<tr>
								<th>城市</th>
								<th>区县</th>
								<th>操作</th>
							</tr>
						</thead>
						<tbody>
							@if(count($records) == 0)
								<tr>
									<td colspan="3" class="sg-centered">暂无配送区域！</td>
								</tr>
							@else
								@foreach($records as $record)
									<tr>
										<td>{{ $record->city }}</td>
										<td>{{ $record->county }}</td>
										<td>
											<div class="btn-group">
												<div class="btn btn-sm btn-danger btn-delete" data-id="{{ $record->id }}">删除</div>
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
<div class="modal fade" id="create-modal" tabindex="-1" role="dialog" aria-labelledby="create-modal-label" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="create-modal-label">
					添加配送区域
				</h4>
			</div>
			<div class="modal-body">
				<form class="form-horizontal" method="POST" action="{{ U('Backend/SendRange/store') }}" id="create-form">
					
					{{ csrf_field() }}
					<div class="form-group">
						<label class="col-sm-3 control-label" for="city">城市</label>
						<div class="col-sm-9">
							<select class="form-control" name="city" id="select-city" style="width: 100%">
								@foreach($cities as $city)
									<option value="{{ $city->name }}" data-id="{{ $city->id }}" {{ $city->id == $default_city->id ? 'selected' : '' }}>{{ $city->name }}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-3 control-label" for="county">区县</label>
						<div class="col-sm-9">
							<select class="form-control" name="county" id="select-county">
								@foreach($counties as $county)
									<option value="{{ $county->name }}">{{ $county->name }}</option>
								@endforeach
							</select>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default pull-left" data-dismiss="modal">
					取消
				</button>
				<button type="button" class="btn btn-success" id="create-confirm-btn">
					确认
				</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="edit-modal" tabindex="-1" role="dialog" aria-labelledby="edit-modal-label" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="edit-modal-label">
					编辑配送区域
				</h4>
			</div>
			<div class="modal-body">
				<form class="form-horizontal" method="POST" action="{{ U('Backend/SendRange/update') }}" id="edit-form">
					
					{{ csrf_field() }}

					<input type="text" name="id" style="display: none" id="edit-range-id">

					<div class="form-group">
						<label class="col-sm-3 control-label" for="range">区域</label>
						<div class="col-sm-9">
							<select class="form-control" id="edit-range-name" name="county">
								@foreach($counties as $county)
									<option value="{{ $county->name }}">{{ $county->name }}</option>
								@endforeach
							</select>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default pull-left" data-dismiss="modal">
					取消
				</button>
				<button type="button" class="btn btn-success" id="edit-confirm-btn">
					确认
				</button>
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
					确定要删除该条记录吗？
				</h4>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default pull-left" data-dismiss="modal">
					取消
				</button>
				<button type="button" class="btn btn-danger" id="delete-confirm-btn">
					删除
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

	$('#add-range').on('click', function() {
		$('#create-modal').modal('show');
	});

	$('#create-confirm-btn').on('click', function() {
		$('#create-form').submit();
	});

	$.fn.modal.Constructor.prototype.enforceFocus = function () {};
	$('#select-city').select2();

	$('#select-city').change(function() {
		val = $(this).val();
		city_id = $(this).find(':selected').attr('data-id');
		my_url = "{{ U('Backend/SendRange/queryCounties') }}" + '?city_id=' + city_id;

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            type: 'POST',
            url: my_url,
            dataType: 'json',
            success: function (data) {
            	text = '';
                for(var i = 0; i < data.length; ++i) {
                	text += '<option value="' + data[i] + '">' + data[i] + '</option>';
                }
                // $('#select-county').empty();
                $('#select-county').html(text);
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
	});

	// $('#edit-confirm-btn').on('click', function() {
	// 	$('#edit-form').submit();
	// });

	$('#send-range-table').on('click', '.btn-edit', function() {
		// $('#edit-range-id').val($(this).attr('data-id'));
		// name = $(this).attr('data-name');
		// $('#edit-range-name').find('option[value=' + name + ']').prop('selected', true);

		// $('#edit-modal').modal('show');
	}).on('click', '.btn-delete', function() {
		url = "{{ U('Backend/SendRange/destroy') }}" + "?id=" + $(this).attr('data-id');
		$('#delete-form').attr('action', url);
		$('#delete-modal').modal('show');
	});

	$('#delete-confirm-btn').on('click', function() {
		$('#delete-form').submit();
	});
});
</script>
@endsection