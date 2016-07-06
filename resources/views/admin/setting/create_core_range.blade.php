@extends('layouts.admin')
@section('content')
    <setting></setting>
    <template id="setting">
        <table class="table">
            <thead>
            <tr>
                <th>项目名称</th>
                <th>核心院校设置</th>
                <th>冲刺院校设置</th>
                <th>核心院校数量</th>
                <th>冲刺院校数量</th>
            </tr>
            </thead>
            <tbody>
                <template v-for='country_core in core_range'>
                    <template v-for='degree_core in country_core.degrees'>
                        <tr>
                           <td>
                               @{{ country_core.country_name }}@{{degree_core.degree_name}} 
                           </td>
                           <td>
                               <input type="text" v-model="degree_core['core']['range']" placeholder="1-100">%
                           </td><td>
                               <input type="text" v-model="degree_core['sprint']['range']" placeholder="1-100">%
                           </td>
                           <td>
                               <input type="text" v-model="degree_core['core']['count']" placeholder="@{{ country_core.country_name }}@{{degree_core.degree_name}} 核心院校数量">
                           </td><td>
                               <input type="text" v-model="degree_core['sprint']['count']" placeholder="@{{ country_core.country_name }}@{{degree_core.degree_name}} 冲刺院校数量">
                           </td>
                        </tr>
                    </template>
                </template>
            </tbody>
        </table>
        <button @click="save">保存</button>
    </template>
    <script>
        Vue.component('setting', {
            template: '#setting',
            data: function () {
                return {
                    core_range: {!! json_encode((new \App\CoreRangeSetting)->getSetting()) !!}
                }
            },
            methods: {
                save: function(){
                    var url = '{{ route('admin.setting.store', ['key' => $key]) }}';
                    this.$http.post(url, {'value': this.core_range}).then(function(response){
                        alert('修改成功');
                    });
                }
            }
        })
    </script>
@endsection