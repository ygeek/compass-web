@extends('layouts.admin')
@section('content')
    <setting></setting>
    <template id="setting">
        <h1>211院校列表设置</h1>

        <ul>
            <ol v-for="item in list" track-by="$index" v-bind:class='{"red": !unique(item) }'>
                <span>@{{ $index+1 }}</span>
                <input v-model="list[$index]"> <button @click="remove($index)">删除</button>
            </ol>
        </ul>
        <button @click="add">增加项目</button>
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
                                return [''];
                        @endif
                    }()

                }
            },
            methods: {
                add: function () {
                    this.list.push('');
                },
                remove: function (index) {
                    this.list.splice(index, 1);
                },
                save: function(){
                    var url = '{{ route('admin.setting.store', ['key' => $key]) }}';
                    this.$http.post(url, {'value': this.list}).then(function(response){
                        alert('修改成功');
                    });
                },
                unique: function(item){
                    return this.list.filter(function(name){ return name == item }).length  < 2;
                }
            },
        })
    </script>
@endsection