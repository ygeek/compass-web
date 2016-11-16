<script type="text/javascript">

    var link = document.createElement( "link" );
    link.href = '/summernote/dist/summernote.css';
    link.type = "text/css";
    link.rel = "stylesheet";
    link.media = "screen,print";

    document.getElementsByTagName( "head" )[0].appendChild( link );
</script>

@if(isset($college_id))<a class="btn btn-primary" href="{{ route('admin.articles.index', [ 'college_id' => $college_id ]) }}">返回</a>@else<a class="btn btn-primary" href="{{route('admin.articles.index')}}">返回</a>@endif
<div class="form-group">
    <label class="col-xs-12" for="contact1-category">文章分类</label>
    <div class="col-xs-12">
        <select class="form-control" id="contact1-category" name="category_id">
            @foreach($article_categories as $category)
                <option value="{{$category->id}}" @if($article->category_id == $category->id) selected @endif>{{$category->name}} </option>
            @endforeach
        </select>
    </div>
</div>
<div class="form-group">
    <label class="col-xs-12" for="register1-name">文章标题</label>
    <div class="col-xs-12">
        <input class="form-control" type="text" id="register1-name" name="title" placeholder="输入文章标题" value="{{$article->title}}">
    </div>
</div>


<div class="form-group">
    <label class="col-xs-12" for="example-textarea-input">文章内容</label>
    <div class="col-xs-12">
      <script id="ueditor" name="content" type="text/plain">{!! $article->content !!}</script>
    </div>
</div>

<div class="form-group">
    <label class="col-xs-12" for="register2-name">排序权重</label>
    <div class="col-xs-12">
        <input class="form-control" type="text" id="register2-name" name="order_weight" placeholder="输入排序权重 默认为0" value="{{$article->order_weight}}">
    </div>
</div>

<script src="/ueditor/ueditor.config.js"></script>
<script src="/ueditor/ueditor.all.min.js"></script>
<script src="/ueditor/lang/zh-cn/zh-cn.js"></script>
<script>
    $(document).ready(function() {
        var ue = UE.getEditor('ueditor', {
          initialFrameHeight: 400
        });
        ue.ready(function() {
           ue.execCommand('serverparam', '_token', '{{ csrf_token() }}');
        });

        errorHandler = function(err) {
          console.log(err);
        };

        // $('#summernote').summernote({
        //     height: 350,
        //     minHeight: null,
        //     maxHeight: null,
        //     callbacks: {
        //         onImageUpload: function(files) {
        //             for (var i = files.length - 1; i >= 0; i--) {
        //                 data = new FormData();
        //                 data.append("image", files[i]);
        //                 $.ajax({
        //                     data: data,
        //                     type: "POST",
        //                     url: "{{route("picture_upload")}}",
        //                     cache: false,
        //                     contentType: false,
        //                     processData: false,
        //                     success: function(filename) {
        //                         $('#summernote').summernote("insertImage", filename.data.path);
        //                     }
        //                 });
        //             }
        //
        //         }
        //     }
        //
        // });
    });

</script>
