<script type="text/javascript">

    var link = document.createElement( "link" );
    link.href = '/summernote/dist/summernote.css';
    link.type = "text/css";
    link.rel = "stylesheet";
    link.media = "screen,print";

    document.getElementsByTagName( "head" )[0].appendChild( link );
</script>
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
        <textarea id="summernote" name="content">{{$article->content}}</textarea>
        {{--<textarea class="form-control" id="example-textarea-input" name="content" rows="6" placeholder="Content.."></textarea>--}}
    </div>
</div>

<div class="form-group">
    <label class="col-xs-12" for="register2-name">排序权重</label>
    <div class="col-xs-12">
        <input class="form-control" type="text" id="register2-name" name="order_weight" placeholder="输入排序权重 默认为0" value="{{$article->order_weight}}">
    </div>
</div>

<script src="/summernote/dist/summernote.min.js"></script>
<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: { 'X-CSRF-Token' :  "{{csrf_token()}}"    }
        });

        $('#summernote').summernote({
            height: 350,
            minHeight: null,
            maxHeight: null,
            callbacks: {
                onImageUpload: function(files) {
                    data = new FormData();
                    data.append("image", files[0]);
                    $.ajax({
                        data: data,
                        type: "POST",
                        url: "{{route("picture_upload")}}",
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function(filename) {
                            $('#summernote').summernote("insertImage", filename.data.path);
                        }
                    });
                }
            }

        });
    });

</script>