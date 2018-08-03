@extends('default')
@section('contents')

    @include('_nav')
    @include('_errors')
    @include('_msg')
    <br>
    <br>
<div class="container">
    <table class="table table-striped table-bordered table-condensed table-hover">
        <h1>订单详情</h1>
        <tr>
            <td>订单编号</td>
            <td>
                {{ $order->sn }}
            </td>
        </tr>

        <tr>
            <td>下单用户账号</td>
            <td>
                {{ $order->Consumer->username }}
            </td>
        </tr>
        <tr>
            <td>所属商家</td>
            <td>
                {{$order->Shop->shop_name}}
            </td>
        </tr>
        <tr>
            <td>省</td>
            <td>
                {{ $order->province }}
            </td>
        </tr>
        <tr>
            <td>市</td>
            <td>
                {{  $order->city }}
            </td>
        </tr>
        <tr>
            <td>县</td>
            <td>
                {{ $order->county }}
            </td>
        </tr>
        <tr>
            <td>详细收货地址</td>
            <td>
                {{ $order->address }}
            </td>
        </tr>
        <tr>
            <td>收货人电话</td>
            <td>
                {{ $order->tel }}
            </td>
        </tr>
        <tr>
            <td>收货人姓名</td>
            <td>
                {{ $order->name }}
            </td>
        </tr>
        <tr>
            <td>总价格</td>
            <td>
                {{ $order->total }}
            </td>
        </tr>
        <tr>
            <td>状态</td>
            <td>
                {{ $order->status }}
            </td>
        </tr>
        <tr>
            <td>订单创建时间</td>
            <td>
                {{ $order->created_at }}
            </td>
        </tr>

        <tr>

    </table>
    <br>
    <br>
    <br>
    <h1>商品详情</h1>

    <table class="table table-striped table-bordered table-condensed table-hover">
        <tr style="background: darkgrey">
            <th>商品名称</th>
            <th>商品单价</th>
            <th>商品数量</th>
            <th>商品图片</th>
        </tr>
        @foreach($ordergoods as $ordergood)
        <tr>
            <td>
                {{ $ordergood->goods_name }}
            </td>
            <td>
                {{ $ordergood->goods_price}}
            </td>
            <td>
                {{$ordergood->amount}}
            </td>
            <td>
                <img src="{{$ordergood->goods_img}}" width="50">
            </td>
        </tr>
        @endforeach
    </table>

</div>
    @endsection