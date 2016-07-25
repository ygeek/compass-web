@extends('layouts.admin')

@section('title')
排行榜设置
@endsection

@section('content')
<?php
  if(isset($value)){
    $data = $value;
  }else{
    $data = [
      'categories' => [],
      'rankings' => []
    ];
  }

  $data = json_encode($data);
 ?>
<rankings :rankings="{{ $data }}"></rankings>
<template id="rankings">
  <button @click="save">保存</button>
<div class="row">
    <div class="col-lg-4">
        <!-- Simple Tree -->
        <div class="block">
            <div class="block-header bg-gray-lighter">
                <ul class="block-options">
                    <li>
                        <button type="button" @click="appendNode()"><i class="si si-settings"></i></button>
                    </li>
                </ul>
                <h3 class="block-title">分类管理</h3>
            </div>
            <div class="block-content">
              <ul>
                <node v-for="node in rankings.categories" :node="node"></node>
              </ul>
            </div>
        </div>
    </div>

    <category-rankings :rankings="currentShowRankings" :is_world_ranking="currentShowNode.world_ranking" v-if="!!currentShowNode"></category-rankings>
</div>
</template>

<template id="category_rankings">
  <div class="modal in" style="display: block;" v-if="showPop">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="block block-themed block-transparent remove-margin-b">
                  <div class="block-header bg-primary-dark">
                      <ul class="block-options">
                          <li>
                              <button @click="showPop=false" data-dismiss="modal" type="button"><i class="si si-close"></i></button>
                          </li>
                      </ul>
                      <h3 class="block-title">添加排行榜</h3>
                  </div>

                  <div class="block-content">
                    <rank-editor :rank="tmpRanking" :is_world_ranking="is_world_ranking"></rank-editor>
                  </div>
              </div>
              <div class="modal-footer">
                  <button class="btn btn-sm btn-primary" type="button" data-dismiss="modal" @click="showPop=false">关闭窗口</button>
              </div>
          </div>
      </div>
  </div>

  <div class="col-md-8">
    <div class="block">
        <ul class="nav nav-tabs" data-toggle="tabs">
            <li v-for="ranking in rankings" v-bind:class="{'active': $index == 0}">
                <a href="#ranking-@{{ranking._id}}" data-toggle="tab" aria-expanded="false">@{{ranking.name}}</a>
            </li>
            <li class="pull-right">
                <ul class="block-options push-10-t push-10-r">
                    <li>
                        <button type="button" data-toggle="block-option" data-action="fullscreen_toggle"><i class="si si-size-fullscreen"></i></button>
                    </li>

                    <li>
                        <button type="btn btn-primary" data-toggle="block-option" @click="showPop=true">添加排行</button>
                    </li>
                </ul>
            </li>
        </ul>
        <div class="block-content tab-content">
            <div class="tab-pane" id="ranking-@{{ranking._id}}" v-for="ranking in rankings" v-bind:class="{'active': $index == 0}">
              <rank-editor :rank="ranking" :is_world_ranking="is_world_ranking"></rank-editor>
            </div>
        </div>
    </div>
</div>
</template>

<template id="rank-editor">
    <div class="form-group">
      <label>排行榜名称</label><input type="text" v-model="rank.name" />
    </div>
    <div class="form-group">
      <label>排行榜排序权重</label><input type="text" v-model="rank.order" />
    </div>
    <div class="form-group">
      <label>排行榜标签</label><input type="text" v-model="rank.tag" />
    </div>

    <table class="table">
    <tr>
        <th>
          名次
        </th>
        <th>中文名</th>
        <th>英文名</th>
        <th v-if="!is_world_ranking">
          世界排名
        </th>
        <th>操作</th>
    </tr>

    <tr v-for="item in rank.rank" track-by="$index">
    <td>
      @{{$index+1}}
    </td>
    <td><input type="text" v-model="item.chinese_name"></td>
    <td><input type="text" v-model="item.english_name"></td>
    <td v-if="!is_world_ranking"><input style="width: 30px;" type="text" v-model="item.world_ranking"></td>
    <td>
        <button @click="remove($index)" class="btn btn-xs">删除</button>
    </td>
    </tr>
    </table>
    <button class="btn btn-xs" @click="add">增加项目</button>
    <button class="btn btn-xs" @click="save">保存</button>
</template>


<template id="node">
  <li>
    <div>
      <span @click="selectNode(node)">@{{node.name}}</span>
      <button @click="addChild" class="btn btn-xs btn-default">增加子分类</button>
    </div>
    <ul>
      <node
        v-for="item in node.children"
        :node="item">
      </node>
    </ul>
  </li>
</template>

<script type="text/javascript">
Vue.component('rank-editor', {
    template: '#rank-editor',
    props: ['rank', 'is_world_ranking'],
    methods: {
      add: function () {
          var obj = {chinese_name: null, english_name: null};
          if(!this.is_world_ranking){
            obj['world_ranking'] = null;
          }

          this.rank.rank.push(obj);
      },

      remove: function (index) {
          this.rank.rank.splice(index, 1);
      },

      save: function(){
        this.$dispatch('edit-rank', this.rank)
      }
    }
})

  Vue.component('node', {
    template: '#node',
    props: ['node'],
    methods: {
      addChild: function(){
        var node_name = prompt("请输入分类名称");
        if(node_name == null){
            return;
        }else{
          this.node.children.push({
            name: node_name,
            _id: guid(),
            children: [],
            world_ranking: this.node.world_ranking
          })
        }
      },
      selectNode: function(node){
        this.$dispatch('select-node', node)
      }
    }
  })

  Vue.component('category-rankings', {
    template: '#category_rankings',
    props: ['rankings', 'is_world_ranking'],
    data: function(){
      return {
          showPop: false,
          tmpRanking: {
            order: 0,
            name: null,
            tag: null,
            rank: []
          }
      }
    },
    methods: {
      starAddRanking: function(){

      }
    },
    events: {
      'close-pop': function(){
        this.showPop = false;
      }
    }
  })

  Vue.component('rankings', {
    template: '#rankings',
    props: ['rankings'],
    data: function(){
      return {
        selected_node_id: null,
        currentShowNode: null,
      }
    },
    methods: {
      appendNode: function(parent){
        var node_name = prompt("请输入分类名称");
        if(node_name == null){
          return;
        }else{
          var node = parent;
          if(!node){
            node = this.rankings.categories;
          }

          world_ranking = false;

          if(node_name.indexOf('世界') > -1){
            world_ranking = true;
          }

          node.push({
            name: node_name,
            _id: guid(),
            children: [],
            world_ranking: world_ranking
          });
        }
      },
      save: function(){
        var url = '{{ route('admin.setting.store', ['key' => $key]) }}';
        this.$http.post(url, {'value': {
          categories: this.rankings.categories,
          rankings: this.rankings.rankings
        }}).then(function(response){
            alert('修改成功');
        });
      }
    },
    computed: {
      currentShowRankings: function(){
        if(!this.currentShowNode){
          return []
        }else{
          var that = this;
          return this.rankings.rankings.filter(function(ranking){
            return ranking.category_id == that.currentShowNode._id;
          });
        }
      }
    },
    events: {
      'select-node': function(node){
        this.selected_node_id = node._id
        this.currentShowNode = node
      },
      'edit-rank': function(rank){
        if(rank._id){
          this.save()
        }else{
          //新建 要创建id 以及设置其 category_id
          rank._id = guid();
          rank.category_id = this.selected_node_id;
          this.rankings.rankings.push(rank)

          this.$broadcast('close-pop');
          this.save()
        }
      }
    }
  });
</script>
@endsection
