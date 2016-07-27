@extends('layouts.admin')

@section('content')
<?php
  if(isset($value)){
    $data = $value;
  }else{
    $data = 0;
  }
 ?>

    <setting value='{{$data}}'></setting>
    <template id="setting">
        <h1>使用人数设置</h1>

        <input v-model="value">
        <button @click="save">保存</button>
    </template>
    <script>
        Vue.component('setting', {
            template: '#setting',
            props: ['value'],
            methods: {
                save: function(){
                    var url = '{{ route('admin.setting.store', ['key' => $key]) }}';
                    this.$http.post(url, {'value': this.value}).then(function(response){
                        alert('修改成功');
                    });
                }
            }
        })
    </script>
@endsection
