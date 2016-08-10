<advertisement-create-form></advertisement-create-form>
<template id="create_form">
    <a class="btn btn-primary" href="{{route('admin.advertisements.index')}}">返回</a>
    <div class="block block-bordered">
        <div class="block-header bg-gray-lighter">
            <ul class="block-options">
                <li>
                    <button type="button" data-toggle="block-option" data-action="refresh_toggle" data-action-mode="demo"><i class="si si-refresh"></i></button>
                </li>
                <li>
                    <button type="button" data-toggle="block-option" data-action="content_toggle"><i class="si si-arrow-up"></i></button>
                </li>
            </ul>
            <h3 class="block-title">新增广告</h3>
        </div>
        <div class="block-content" id="new_college_form">
            @if( Route::getCurrentRoute()->getName() == 'admin.advertisements.edit')
                <form class="form-horizontal push-10-t push-10" action="{{ route('admin.advertisements.update', $advertisement->id) }}" method="POST" enctype="multipart/form-data">
                    <input name="_method" type="hidden" value="PATCH">
                    @else
                        <form class="form-horizontal push-10-t push-10" action="{{ route('admin.advertisements.store') }}" method="POST" enctype="multipart/form-data">
                            @endif
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <div class="col-xs-6">
                                            <label for="link">链接<span class="text-danger">*</span></label>
                                            <input class="form-control input-lg" type="text" value="{{ $advertisement->link or old('link') }}" id="link" name="link" placeholder="输入链接..">
                                        </div>
                                        <div class="col-xs-6">
                                            <label for="priority">权重</label>
                                            <input class="form-control input-lg" type="text" value="{{$advertisement->priority or old('priority')}}" id="priority" name="priority" placeholder="输入权重..">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <div class="col-xs-6">
                                            <label for="background_image_path">广告背景</label>
                                            <input @change="checkinfo" class="form-control input-lg" type="file" id="background_image_path" name="background_image_path" placeholder="Enter your location..">
                                        </div>
                                        @if($advertisement->background_image_path)
                                            <img src="{{app('qiniu_uploader')->pathOfKey($advertisement->background_image_path)}}" height="40px;"/>
                                        @endif
                                    </div>


                                    <div class="form-group">
                                        <label class="col-xs-3">院校查询页面</label>
                                        <div class="col-xs-3">
                                            <label class="css-input css-checkbox css-checkbox-success">
                                                <input name="page_colleges_index" type="checkbox" @if($advertisement->page_colleges_index) checked @endif><span></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-xs-3">院校详情页面</label>
                                        <div class="col-xs-3">
                                            <label class="css-input css-checkbox css-checkbox-success">
                                                <input name="page_colleges_show" type="checkbox" @if($advertisement->page_colleges_show) checked @endif><span></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-xs-3">院校排行榜页面</label>
                                        <div class="col-xs-3">
                                            <label class="css-input css-checkbox css-checkbox-success">
                                                <input name="page_colleges_rank" type="checkbox" @if($advertisement->page_colleges_rank) checked @endif><span></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-xs-3">评估结果页面</label>
                                        <div class="col-xs-3">
                                            <label class="css-input css-checkbox css-checkbox-success">
                                                <input name="page_estimate_index" type="checkbox" @if($advertisement->page_estimate_index) checked @endif><span></span>
                                            </label>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-xs-12">
                                    <button class="btn btn-warning" type="submit"><i class="fa fa-check push-5-r"></i>
                                        @if( Route::getCurrentRoute()->getName() == 'admin.advertisements.edit')
                                            修改广告
                                        @else
                                            新增广告
                                        @endif
                                    </button>
                                </div>
                            </div>
                        </form>
        </div>
    </div>
    </div>

</template>
<script>
    Vue.component('advertisement-create-form', {
        template: '#create_form',
        data: function(){
            return {
            }
        },
        methods: {
            checkinfo: function(e){
                var obj = e.target
                var len = obj.files.length;

                var text="";
                var empty = false;
                for (var i =0 ; i < len ; i++){
                    var file_size = (obj.files[i].size / 1024 / 1024).toFixed(2);
                    if(file_size * 100 > 200){
                        text += "文件:"+obj.files[i].name+" ,大小:"+ file_size +"M\n";
                        text += "图片大小请勿太大（2M以下）否则将导致用户加载困难"
                        empty = true;
                        alert(text);
                    }
                }

                if(empty){
                    e.target.value = "";
                }
            }
        }
    });
</script>
