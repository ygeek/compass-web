@include('m.public.header')
<div class="clear"></div>
<form action="" method="get" id="search">
<div class="main02" id="main02">
    <div class="yuanxiao_cx_main">
        <div class="yx_chaxun" style="margin:2% 2%;float:left; width:78%;border-radius: 5px;">
            
                <input type="text" class="chax_input" name="college_name" value="{{$college_name}}" placeholder="院校查询">
                <input type="hidden" name="selected_country_id" value="-1"/>
                <input type="submit" class="chax_so" value="">
            
        </div>
        <div class="shaixuan_icon">
            <img src="/static/images/shaixuan.png" height="65" onclick="shaixuan('.shaixuan')" />
        </div>
        <div class="clear"></div>
    </div>
    <!--<div class="chaxun01"><a href="javascript:shaixuan('.shaixuan')"><span>•</span>条件筛选</a></div>-->
    <div class="chaxun02"><h1>您找到<span>{{ $colleges->total() }}</span>所相关学校</h1></div>
    <div class="grzy_wdsc_list">
         @foreach($colleges as $college)
            <div class="pinggu_xx50" >
                <div class="pinggu_xx_name50">
                    <a href="{{route('colleges.show', $college->key)}}" ><h2>
                        <img src="{{app('qiniu_uploader')->pathOfKey($college->badge_path)}}"><br />
                        <span style="display:block; float:left;">{{$college->chinese_name}}</span><span style="background:#23e6bb;display:block; float:left; color:#fff; border-radius:3px; padding:1% 2%; font-size:0.8em; margin:0 0 0 5px;">{{ ($college->type=="public")?'公立':'私立' }}</span><br />
                        <div class="clear"></div>
                    {{$college->english_name}}
                        </h2></a>
                        <h1>本国排名：{{$college->domestic_ranking}}<br><span style="background:url(/static/images/icon21.jpg) left no-repeat; background-size:20px; padding:0 0 0 20px;">{{$college->administrativeArea->name}}
                                            @if($college->administrativeArea->parent)
                                                , {{$college->administrativeArea->parent->name}}
                                                @if($college->administrativeArea->parent->parent)
                                                    , {{$college->administrativeArea->parent->parent->name}}
                                                @endif
                                            @endif</span><br>
                            <a href="/estimate/step-1?selected_country_id={{$college->country_id}}&college_id={{$college->id}}" style="font-size:1.2em; line-height: 40px;">测试录取率>></a><br>
                       
                            <img src="/static/images/xin<?php if(app('auth')->user()){ if(app('auth')->user()->isLikeCollege($college->id)){echo 1;} else {echo 2;}}else{echo 2;} ?>.png" width="30" style=" cursor: pointer;" likeid='<?php if(app('auth')->user()){ if(app('auth')->user()->isLikeCollege($college->id)){echo 1;} else {echo 2;}}else{echo 3;} ?>' onclick="setLike('{{ $college->id }}',$(this))" ><span id='shuzi{{ $college->id }}'>{{ $college->like_nums }}</span>
                       </h1>
                       <div class="clear"></div>
                </div>     
            </div>
        @endforeach
        <!--
        <ul>
           
            @foreach($colleges as $college)
            <a href="{{route('colleges.show', $college->key)}}" ><li>
                    <img src="{{app('qiniu_uploader')->pathOfKey($college->badge_path)}}" style="display: block; margin: 0 auto;">
                <h1>{{$college->chinese_name}}<br><font>{{$college->english_name}}</font></h1>
                </li></a>
           @endforeach

        </ul>-->
    </div>
    <div class="clear"></div>
    <div class='page'>
    {{ $colleges->appends(app('request')->except('page'))->render() }}
    </div>
</div>
<?php 

function getAreaName($areas,$aid)
{
    if($aid=="0") return '不限';
    foreach(objToArr($areas) as $k=>$v)
    {
        $arr[$v['id']] = $v; 
       
    }
    return $arr[$aid]['name'];
}
?>
<!--隐藏内容-->
<div class="shaixuan tiaojian">
    <div class="header">
        <a href="javascript:goBlack('.shaixuan')"><div class="header_l"><img src="/static/images/back.png" height="20" /></div></a>
        <div class="header_c">院校筛选</div>
        <div class="header_r"><a href='javascript:document.getElementById("search").submit();'>确认</a></div>
    </div>
    <div class="clear"></div>
    <div class="main02">
        <div class="grzy_wdzl">
            <ul>
                <li class="grzy_wdzl01" onclick="javascript:shaixuan('.didian')">
                    <span>国家地区</span>
                    <em><input type="hidden" number v-model="selected_country_id"  name="selected_country_id" value="{{$selected_country_id}}"/>
                        <input type="hidden" number v-model="selected_state_id"  name="selected_state_id" value="{{$selected_state_id}}"/>
                        <a href="javascript:void(0)" class="didiana"><?php echo getAreaName($areas,$selected_country_id); ?></a></em>
                    <div class="clear"></div>
                </li>
                <li class="grzy_wdzl01"  onclick="javascript:shaixuan('.zhuanye')">
                    <span>专业方向</span>
                    <em><input type="hidden" v-model="selected_speciality_cateogry_id"  number name="selected_speciality_cateogry_id" value="{{$selected_speciality_cateogry_id}}"/><a href="javascript:void(0)" class="zhuanyea">不限</a></em>
                    <div class="clear"></div>
                </li>
                <li class="grzy_wdzl01"  onclick="javascript:shaixuan('.leixing')">
                    <span>学校性质</span>
                    <em><input type="hidden" v-model="selected_property" name="selected_property" value="{{$selected_property}}" number/><a href="javascript:void(0)" class="leixinga">不限</a></em>
                    <div class="clear"></div>
                </li>
                <li class="grzy_wdzl01"  onclick="javascript:shaixuan('.paiming')">
                    <span>国内排名</span>
                    <em><input type="hidden" v-model="rank_start" class="search-input" name="rank_start" value="{{$rank_start}}"/>
                                
                        <input type="hidden" v-model="rank_end" class="search-input" name="rank_end" value="{{$rank_end}}"/>
                        
                        <a href="javascript:void(0)" class="paiminga"><?php if(!$rank_start&&!$rank_end){ ?>不限<?php }else{ ?>{{$rank_start}}-{{$rank_end}}<?php } ?></a></em>
                    <div class="clear"></div>
                </li>
            </ul>
        </div>
        <div class="clear"></div>
    </div>
</div>
</form>
<div class='didian tiaojian' selected_country_id='0' selected_country_id_name='不限' selected_state_id_name='' selected_state_id='0' >
    <div class="header">
        <a href="javascript:goBlack2('.shaixuan')"><div class="header_l"><img src="/static/images/back.png" height="20" /></div></a>
        <div class="header_c">国家地区</div>
        <div class="header_r"><a href="javascript:queding('didian','selected_country_id');" class="queren">确认</a></div>
    </div>
    <div class="clear"></div>
    <div class="main03"><!--id="main03_l_menu"-->
        <div class="main03_l">
            <!--<a href="javascript:void(0)" class="childpar" area_id='0' >不限</a>-->
            @foreach($areas as $key=>$val)
            <a  href="javascript:void(0)" class="childpar" area_id='{{ $val->id }}'>{{ $val->name }}</a>
            @endforeach
           
        </div>
        <div class="main03_r">
           <!--<span><a href="javascript:void(0)">全部</a></span>
            <em>
                <a href="javascript:void(0)">全部</a>
            </em>-->
            @foreach($areas as $key=>$val)
            <span class='area{{ $val->id }} areachild'><a href="javascript:void(0)">{{ $val->name }}</a></span>
            <em class='area{{ $val->id }} areachild '>
                <a href="javascript:void(0)" childarea_id='0' class="areachilds">全部</a>
                @foreach($val->children as $v)
                <a  href="javascript:void(0)" class="areachilds" childarea_id='{{ $v->id }}'>{{ $v->name }}</a>
                @endforeach
                
            </em>
            @endforeach
        </div>
        <div class="clear"></div>
    </div>
</div>

<div class="zhuanye tiaojian" selected_speciality_cateogry_id='0' selected_speciality_cateogry_id_name=''>
    <div class="header">
        <a href="javascript:goBlack2('.shaixuan')"><div class="header_l"><img src="/static/images/back.png" height="20" /></div></a>
        <div class="header_c">专业方向</div>
        <div class="header_r"><a href="javascript:queding('zhuanye','selected_speciality_cateogry_id');">确认</a></div>
    </div>
    <div class="clear"></div>
    <div class="main02">
        <div class="yuanxiao_sx"><!--yuanxiao_sx-->
            <a href="javascript:void(0)" class="zhuanyechilds" cateid='0'>不限</a>
            @foreach($speciality_categories as $key=>$val)
            <a id="" href="javascript:void(0)" class="zhuanyechilds" cateid='{{ $val->id }}'>{{ $val->chinese_name }}</a>
           @endforeach
        </div>
        <div class="clear"></div>
    </div>
</div>


<div class="leixing tiaojian" selected_property='0' selected_property_name=''>
    <div class="header">
        <a href="javascript:goBlack2('.shaixuan')"><div class="header_l"><img src="/static/images/back.png" height="20" /></div></a>
        <div class="header_c">学校性质</div>
        <div class="header_r"><a href="javascript:queding('leixing','selected_property');">确认</a></div>
    </div>
    <div class="clear"></div>
    <div class="main02">
        <div class="yuanxiao_sx">
            <a class="leixingchilds" leiid='0' href="javascript:void(0)">不限</a>
            <a class="leixingchilds" leiid='1' href="javascript:void(0)">公立</a>
            <a class="leixingchilds" leiid='2' href="javascript:void(0)">私立</a>
            
        </div>
        <div class="clear"></div>
    </div>
</div>

<div class="paiming tiaojian" rank_start='{{$rank_start}}' rank_end='{{$rank_end}}' paiminghtm='<?php if(!$rank_start&&!$rank_end){ ?>不限<?php }else{ ?>{{$rank_start}}-{{$rank_end}}<?php } ?>'>
    <div class="header">
        <a href="javascript:goBlack2('.shaixuan')"><div class="header_l"><img src="/static/images/back.png" height="20" /></div></a>
        <div class="header_c">学校排名</div>
        <div class="header_r"><a href="javascript:pmqueding();">确认</a></div>
    </div>
    <div class="clear"></div>
    <div class="main02">
        <div class="yuanxiao_sx">
            <?php if(!$selected_country_id){ $selected_country_id="1"; }?>
            <style>
                .yuanxiao_sx .paimingchilds{ display: none; }
                .yuanxiao_sx .country<?php echo $selected_country_id; ?> { display: block;}
            </style>
            <a href="javascript:void(0)" class="paimingchilds country1"  rank_start='0' rank_end='0'>不限</a>
            
            <a  href="javascript:void(0)" class="paimingchilds country1" rank_start='1'  rank_end='8'>1-8</a>
            <a href="javascript:void(0)" class="paimingchilds country1" rank_start='9'  rank_end='20'>9-20</a>
            <a href="javascript:void(0)" class="paimingchilds country1" rank_start='21'  rank_end='50'>21-50</a>
           
            <a href="javascript:void(0)" class="paimingchilds country31" rank_start='0' rank_end='0'>不限</a>
            <a  href="javascript:void(0)" class="paimingchilds country31" rank_start='1'  rank_end='20'>1-20</a>
            <a href="javascript:void(0)" class="paimingchilds country31" rank_start='21'  rank_end='30'>21-30</a>
            <a href="javascript:void(0)" class="paimingchilds country31" rank_start='31'  rank_end='60'>31-60</a>
            
            <a href="javascript:void(0)" class="paimingchilds country71" rank_start='0' rank_end='0'>不限</a>
            <a  href="javascript:void(0)" class="paimingchilds country71" rank_start='1'  rank_end='10'>1-10</a>
            <a href="javascript:void(0)" class="paimingchilds country71" rank_start='11'  rank_end='20'>11-20</a>
            <a href="javascript:void(0)" class="paimingchilds country71" rank_start='21'  rank_end='50'>21-50</a>
            
            <a href="javascript:void(0)" class="paimingchilds country146" rank_start='0' rank_end='0'>不限</a>
            <a  href="javascript:void(0)" class="paimingchilds country146" rank_start='1'  rank_end='10'>1-10</a>
            <a href="javascript:void(0)" class="paimingchilds country146" rank_start='11'  rank_end='20'>11-20</a>
            <a href="javascript:void(0)" class="paimingchilds country146" rank_start='21'  rank_end='50'>21-50</a>
        </div>
        <div class="yuanxiao_pm">
            <form action="" method="get">
                <div class="yuanxiao_pm_l">
                    <input name="start" value="{{$rank_start}}" class="paiming01 start" type="number"><span>~</span><input class="paiming01 end" value="{{$rank_end}}" name="end" type="number">
                </div>
                <div class="yuanxiao_pm_r">
                    <input type="button" class="paiming02" onclick="pmqueding2()" value="确定"></div>
                <div class="clear"></div>
        </div>
        </form>

    </div>
    <div class="clear"></div>
</div>

</body>
</html>
