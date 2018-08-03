@extends('default')
@section('contents')

    @include('_nav')
    @include('_errors')
    @include('_msg')
    <div class="container">
        <div class="row">
            <div class="col-md-1">
                <br>
                <br>
                <br>
                <br>
                <a href="{{route('menus.index')}}" class="btn btn-default">所有菜品</a>
                        @foreach($menucategories as $menucategory)
                    <a href="{{route('menus.index',['category_id'=>$menucategory->id])}}" class="btn btn-default">{{$menucategory->name}}</a>
                    <br>
                        @endforeach
            </div>
            <div class="col-md-11">
                <h1>菜品表</h1>

                <form class="form-inline" method="get" action="{{route('menus.index')}}">
                    <div class="form-group">
                        <label>菜名</label>
                        <input type="text" class="form-control" name="name"placeholder="菜名">
                    </div>
                    &emsp;
                    <div class="form-group">
                        <label >最低价格</label>
                        <input type="text" class="form-control" name="di"  placeholder="0">
                    </div>
                    -
                    <div class="form-group">
                        <label>最高价格</label>
                        <input type="text" class="form-control" name="gao"  placeholder="">
                    </div>
                    <button type="submit" class="btn btn-default">搜索</button>
                </form>
                <table class="table table-bordered table-hover" id="arttable">

                    <tr>
                        <th>ID</th>
                        <th>名字</th>
                        <th>评分</th>
                        <th>所属商家</th>
                        <th>所属分类</th>
                        <th>价格</th>
                        <th>描述</th>
                        <th>月销量</th>
                        <th>评分数量</th>
                        <th>提示信息</th>
                        <th>满意度数量</th>
                        <th>满意度评分</th>
                        <th>商品图片</th>
                        <th>操作</th>
                    </tr>
                    @foreach($menus as $menu)
                        <tr>
                            <td>{{$menu->id}}</td>
                            <td>{{$menu->goods_name}}</td>
                            <td>{{$menu->rating}}</td>
                            <td>{{$user}}</td>
                            <td>{{$menu->Menucategories->name}}</td>
                            <td>{{$menu->goods_price}}</td>
                            <td>{{$menu->description}}</td>
                            <td>{{$menu->month_sales}}</td>
                            <td>{{$menu->rating_count}}</td>
                            <td>{{$menu->tips}}</td>
                            <td>{{$menu->satisfy_count}}</td>
                            <td>{{$menu->satisfy_rate}}</td>
                            <td><img src="{{$menu->goods_img}}" width="50"></td>
                            <td>

                                <a href="{{route('menus.show',[$menu])}}" title="查看"
                                   class="btn btn-success btn-xs">查看</a>
                                <a href="{{route('menus.edit',[$menu])}}" title="修改"
                                   class="btn btn-success btn-xs">修改</a>


                                <form class="form-inline"
                                      action="{{route('menus.destroy',['menu'=>$menu])}}" method="post">
                                    {{ method_field('DELETE') }}
                                    {{ csrf_field() }}
                                    <button title="删除" class="btn btn-danger btn-xs">删除</span>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="14">
                            <a href="{{route('menus.create')}}" title="添加" class="btn btn-info btn-block">添加</a>
                        </td>
                    </tr>
                </table>
                {{ $menus->appends($data)->links()}}
            </div>
            </div>


    </div>
@endsection