@extends('default')
@section('contents')

    @include('_nav')
    @include('_errors')
    @include('_msg')
    <br>
    <br>
    <div class="container">

        <div class="col-md-1">
            <br>

            <a href="{{route('orders.index')}}" class="btn btn-default">订单管理</a>
            <a href="{{route('orders.sales')}}" class="btn btn-default">订单统计</a>
            <a href="{{route('orders.menussales')}}" class="btn btn-default">菜品统计</a>
                <br>
        </div>
        <div   class="col-md-11">
            <h1>订单管理</h1>
                <table class="table table-bordered table-hover" id="arttable">

                    <tr>
                        <th>ID</th>
                        <th>订单编号</th>
                        <th>收货人姓名</th>
                        <th>收货人电话</th>
                        <th>收货人详细地址</th>
                        <th>价格</th>
                        <th>状态</th>
                        <th>创建时间</th>
                        <th colspan="2">操作</th>
                    </tr>
                    @foreach($orders as $order)
                        <tr>
                            <td>{{$order->id}}</td>
                            <td>{{$order->sn}}</td>
                            <td>{{$order->name}}</td>
                            <td>{{$order->tel}}</td>
                            <td>{{$order->address}}</td>
                            <td>{{$order->total}}</td>
                            <td>{{$status}}</td>
                            <td>{{$order->created_at}}</td>
                            <td>
                                <a href="{{route('orders.show',[$order])}}" title="查看"
                                   class="btn btn-success btn-xs">查看详情</a>
                            </td>
                            <td>
                                <a href="{{route('orders.index',[$order])}}" title="查看"
                                   class="btn btn-success btn-xs">{{$order->status==0?"发货":"取消订单"}}</a>
                            </td>
                        </tr>
                    @endforeach
                </table>
                {{ $orders->links()}}
        </div>
            </div>
@endsection