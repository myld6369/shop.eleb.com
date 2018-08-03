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
            <a href="{{route('orders.sales')}}" class="btn btn-default">订单销量</a>
            <a href="{{route('orders.menussales')}}" class="btn btn-default">菜品销量</a>
            <br>
        </div>
        <div   class="col-md-11">
            <h1>订单销量: {{$orders->count()}}</h1>
            <table class="table table-bordered table-hover" id="arttable">
                <form action="{{route('orders.sales')}}" method="get">
                    <button class="btn btn-default">全部</button>
                    {{csrf_field()}}
                </form>
                <br>
                <form action="{{route('orders.sales')}}" method="get">
                    指定某月: <input type="date" class="btn btn-default" name="month">&emsp;
                    <button class="btn btn-default">确定</button>
                    {{csrf_field()}}
                </form>
                <br>
                <form action="{{route('orders.sales')}}" method="get">
                    指定某天: <input type="date" class="btn btn-default" name="date">&emsp;
                    <button class="btn btn-default">确定</button>
                    {{csrf_field()}}
                </form>
                <tr>
                    <th>ID</th>
                    <th>订单编号</th>
                    <th>收货人姓名</th>
                    <th>收货人电话</th>
                    <th>收货人详细地址</th>
                    <th>价格</th>
                    <th>状态</th>
                    <th>创建时间</th>
                    <th>操作</th>
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
                    </tr>
                @endforeach
            </table>

        </div>
    </div>
@endsection