@extends('layouts.admin')
@section('content')
    <setting></setting>
    <template id="setting">
        <h1>首页“更多”链接设置</h1>

        <ul>
            <ol>
                <span>语言学习</span>
                <input v-model="list[0]">
                <br/>
                <span>留学攻略</span>
                <input v-model="list[1]">
                <br/>
                <span>移民攻略</span>
                <input v-model="list[2]">
            </ol>
        </ul>
        <button @click="save">保存</button>
    </template>
    <script>
        Vue.component('setting', {
            template: '#setting',
            data: function () {
                return {
                    list: function () {
                        @if(isset($value))
                                return {!! json_encode($value) !!};
                        @else
                                return ['#','#','#'];
                        @endif
                    }()
                }
            },
            methods: {
                save: function(){
                    var url = '{{ route('admin.setting.store', ['key' => $key]) }}';
                    this.$http.post(url, {'value': this.list}).then(function(response){
                        alert('修改成功');
                    });
                }
            },
        })
    </script>
@endsection