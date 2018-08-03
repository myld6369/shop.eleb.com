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
        <h1>修改菜品</h1>
        <form action="{{route('menus.update',['menu'=>$menu])}}" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label>名称</label>
                <input type="text" name="goods_name" class="form-control" value="{{$menu->goods_name}}">
            </div>
            <div class="form-group">
                <label>描述</label>
                <input type="text" name="description" class="form-control" value="{{$menu->description}}">
            </div>
            <div class="form-group">
                <label>价格</label>
                <input type="text" name="goods_price" class="form-control" value="{{$menu->goods_price}}">
            </div>
            <div class="form-group">
                <label>提示信息</label>
                <input type="text" name="tips" class="form-control" value="{{$menu->tips}}">
            </div>
            <div class="form-group">
                <label>菜品图片</label>
                <input type="hidden" id="img_url" name="goods_img">
                <div id="uploader-demo">
                    <!--用来存放item-->
                    <div id="fileList" class="uploader-list"></div>
                    <div id="filePicker">选择图片</div>

                </div>
                <img src="{{$menu->goods_img}}" width="50" id="img">
            </div>

            <div class="form-group">
                <label>所属分类</label>
                <select name="category_id" id="" class="form-control">
                    @foreach($menucategories as $menucategory)
                    <option value="{{$menucategory->id}}" {{$menu->category_id==$menucategory->id?"checked":""}}>{{$menucategory->name}}</option>
                    @endforeach
                </select>
            </div>
            {{ csrf_field() }}
            {{ method_field("PATCH") }}
            <button class="btn btn-primary">提交</button>
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