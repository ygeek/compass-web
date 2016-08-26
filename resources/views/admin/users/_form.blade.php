<college-create-form></college-create-form>
<template id="create_form">
<a class="btn btn-primary" href="{{route('admin.users.index')}}">返回</a>
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
        <h3 class="block-title">修改用户</h3>
    </div>
    <div class="block-content" id="new_college_form">
        <form class="form-horizontal push-10-t push-10" action="{{ route('admin.users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
        <input name="_method" type="hidden" value="PATCH">
          {{ csrf_field() }}
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <div class="col-xs-4">
                            <label for="phone_number">手机号<span class="text-danger">*</span></label>
                            <input class="form-control input-lg" type="text" value="{{ $user->phone_number or old('phone_number') }}" id="phone_number" name="phone_number">
                        </div>
                        <div class="col-xs-4">
                            <label for="email">email<span class="text-danger">*</span></label>
                            <input class="form-control input-lg" type="text" value="{{$user->email or old('email')}}" id="email" name="email">
                        </div>
                        <div class="col-xs-4">
                            <label for="username">用户名<span class="text-danger">*</span></label>
                            <input class="form-control input-lg" type="text" value="{{$user->username or old('username')}}" id="username" name="username">
                        </div>
                        <div class="col-xs-4">
                            <label for="name">姓名<span class="text-danger">*</span></label>
                            <input class="form-control input-lg" type="text" value="{{$user->name or old('name')}}" id="name" name="name">
                        </div>
                    </div>
                </div>

            </div>
                <div class="form-group">
                    <div class="col-xs-12">
                        <button class="btn btn-warning" type="submit"><i class="fa fa-check push-5-r"></i>
                          修改用户
                        </button>
                    </div>
                </div>
              </form>
            </div>
    </div>
</div>

</template>
<script>

Vue.component('college-create-form', {
  template: '#create_form',
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
