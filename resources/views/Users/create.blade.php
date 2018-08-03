@extends('default')
@section('css_files')
    <link rel="stylesheet" type="text/css" href="/webuploader/webuploader.css">
@stop
@section('js_files')
    <script type="text/javascript" src="/webuploader/webuploader.js"></script>
@stop
@section('contents')
@include('_nav')
@include('_errors')
@include('_msg')
<div class="container">

    <form action="{{route('users.store')}}" method="post" enctype="multipart/form-data">
        <h1>注册账号</h1>
        <div class="form-group">
            <label>用户名</label>
            <input type="text" name="name" class="form-control" value="{{old('name')}}">
        </div>
        <div class="form-group">
            <label>邮箱</label>
            <input type="text" name="email" class="form-control" value="{{old('email')}}">
        </div>
        <div class="form-group">
            <label>密码</label>
            <input type="password" name="password" class="form-control" value="{{old('password')}}">
        </div>
        <div class="form-group">
            <label>确认密码</label>
            <input type="password" name="repassword" class="form-control">
        </div>
        <hr>
        <hr>
        <h1>注册店铺</h1>
        <div class="form-group">
            <label>店铺名称</label>
            <input type="text" name="shop_name" class="form-control" value="{{old('shop_name')}}">
        </div>
        <div class="form-group">
            <label>起送金额</label>
            <input type="text" name="start_send" class="form-control" value="{{old('start_send')}}">
        </div>
        <div class="form-group">
            <label>配送费</label>
            <input type="text" name="send_cost" class="form-control" value="{{old('send_cost')}}">
        </div>
        <div class="form-group">
            <label>店公告</label>
            <input type="text" name="notice" class="form-control" value="{{old('notice')}}" >
        </div>
        <div class="form-group">
            <label>优惠信息</label>
            <input type="text" name="discount" class="form-control" value="{{old('discount')}}">
        </div>
        <div class="form-group">
            <label>店铺分类</label>
            <select name="shop_category_id" id="" class="form-control">
                @foreach($categories as $category)
                <option value="{{$category->id}}">{{$category->name}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>菜品图片</label>
            <input type="hidden" id="img_url" name="shop_img">
            <div id="uploader-demo">
                <!--用来存放item-->
                <div id="fileList" class="uploader-list"></div>
                <div id="filePicker">选择图片</div>

            </div>
            <img src="" id="img">
        </div>

        <div class="form-group">
            <label>是否是品牌 &emsp;是:<input type="radio" name="brand" value="1" checked>&emsp;否:<input type="radio" name="brand" value="0" ></label>
        </div>
        <div class="form-group">
            <label>是否准时送达 &emsp;是:<input type="radio" name="on_time" value="1" checked>&emsp;否:<input type="radio" name="on_time" value="0"></label>
        </div>
        <div class="form-group">
            <label>是否蜂鸟配送  &emsp;是:<input type="radio" name="fengniao" value="1" checked>&emsp;否:<input type="radio" name="fengniao" value="0"></label>
        </div>
        <div class="form-group">
            <label>是否保标记 &emsp;是:<input type="radio" name="bao" value="1" checked>&emsp;否:<input type="radio" name="bao" value="1"></label>
        </div>
        <div class="form-group">
            <label>是否票标记 &emsp;是:<input type="radio" name="piao" value="1" checked>&emsp;否:<input type="radio" name="piao" value="0"></label>
        </div>
        <div class="form-group">
            <label>是否准标记 &emsp;是:<input type="radio" name="zhun" value="1" checked>&emsp;否:<input type="radio" name="zhun" value="0"></label>
        </div>

        <div class="form-group">
            <label>验证码</label>
            <input id="captcha2" class="form-control" name="captcha" >
            <img class="thumbnail captcha" src="{{ captcha_src('flat') }}" onclick="this.src='/captcha/flat?'+Math.random()" title="点击图片重新获取验证码">
        </div>
        {{ csrf_field() }}
        <button class="btn btn-primary form-control" >提交</button>
    </form>
</div>
@endsection
@section('js')
    <script>
        // 初始化Web Uploader
        var uploader = WebUploader.create({

            // 选完文件后，是否自动上传。
            auto: true,

            // swf文件路径
//            swf: BASE_URL + '/js/Uploader.swf',

            // 文件接收服务端。
            server: '{{route('upload')}}',

            // 选择文件的按钮。可选。
            // 内部根据当前运行是创建，可能是input元素，也可能是flash.
            pick: '#filePicker',

            // 只允许选择图片文件。
            accept: {
                title: 'Images',
                extensions: 'gif,jpg,jpeg,bmp,png',
                mimeTypes: 'image/*'
            },
            formData:{
                _token:'{{csrf_token()}}'
            }
        });
        uploader.on('uploadSuccess',function(file,response){
            $('#img').attr('src',response.fileName);
            $('#img_url').val(response.fileName);
        });
    </script>
@stop