@extends('layouts.admin')
@section('content')
    <setting></setting>
    <template id="setting">
        <h1>985院校列表设置</h1>

        <ul>
            <li v-for="item in list" track-by="$index">
                <input v-model="list[$index]"> <button @click="remove($index)">删除</button>
            </li>
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
                }
            }
        })
    </script>
@endsection