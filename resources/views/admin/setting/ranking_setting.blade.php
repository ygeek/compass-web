
    <setting></setting>
    <template id="setting">
        <h1>{{ $name }} 排行设置</h1>

        <table class="table">
        <tr>
            <th>名次</th>
            <th>中文名</th>
            <th>英文名</th>
            <th>操作</th>
        </tr>

        <tr v-for="item in list" track-by="$index">
        <td><input type="text" v-model="item.rank"></td>
        <td><input type="text" v-model="item.chinese_name"></td>
        <td><input type="text" v-model="item.english_name"></td>
        <td>
            <button @click="remove($index)">删除</button>
        </td>
        </tr>
        </table>
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
                                return [
                                    {rank: null, chinese_name: null, english_name: null}
                                ];
                        @endif
                    }()

                }
            },
            methods: {
                add: function () {
                    this.list.push({rank: null, chinese_name: null, english_name: null});
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
