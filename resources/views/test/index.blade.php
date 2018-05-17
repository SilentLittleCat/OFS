@extends('admin.layout')

@section('content')

<form method="POST" action="{{ route('test.submit') }}">
	{{ csrf_field() }}
	<input type="checkbox" name="test" value="1">
	<input type="checkbox" name="test" value="2">

	<button type="submit">提交</button>
</form>

@endsection

@section('footer')
<script type="text/javascript">
$(function() {
	items = ['1', '2', '3'];
	console.log(items.splice(2, 1));
});
</script>
@endsection