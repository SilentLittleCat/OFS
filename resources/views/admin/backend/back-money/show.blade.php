@extends('admin.layout')

@section('header')
<style type="text/css">
    .user-info-item {
        margin-top: 15px;
        margin-bottom: 15px;
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
                    <h5>订单详情</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link"> <i class="fa fa-chevron-up"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <table class="table table-striped table-bordered table-hover dataTable" id="order-table">
                        <thead>
                            <tr>
                                <th>订单号</th>
                                <th>配送产品</th>
                                <th>退款金额</th>
                                <th>收货地址</th>
                                <th>下单人</th>
                                <th>下单人手机</th>
                                <th>下单时间</th>
                                <th>状态</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ sprintf("%013d", $order->order_id) }}</td>
                                <td>
                                    @if($order->type == 1)
                                        {{ '男士餐：' }}
                                    @endif
                                    @if($order->type == 2)
                                        {{ '女士餐：' }}
                                    @endif
                                    @if($order->type == 3)
                                        {{ '工作餐：' }}
                                    @endif
                                </td>
                                <td>{{ $order->price }}</td>
                                <td>{{ $order->address }}</td>
                                <td>{{ $order->name }}</td>
                                <td>{{ $order->tel }}</td>
                                <td>{{ $order->time }}</td>
                                @if($order->status == 0)
                                    <td class="warning-color">待审核</td>
                                @elseif($order->status == 1)
                                    <td class="success-color">审核通过</td>
                                @else
                                    <td class="danger-color">审核不过</td>
                                @endif
                                <td>
                                    <div class="btn-group">
                                        <div class="btn btn-sm btn-success order-look" data-id="{{ $order->id }}">审核</div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer')
<script type="text/javascript">
$(function() {

});
</script>
@endsection