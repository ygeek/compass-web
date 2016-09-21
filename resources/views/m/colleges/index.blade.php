@include('m.public.header')
<div class="clear"></div>
<form action="" method="get" id="search">
<div class="main02" id="main02">
    <div class="yuanxiao_cx_main">
        <div class="yx_chaxun" style="margin:2% auto;">
            
                <input type="text" class="chax_input" name="college_name" value="{{$college_name}}" placeholder="院校查询">
                <input type="hidden" name="selected_country_id" value="-1"/>
                <input type="submit" class="chax_so" value="">
            
        </div>

    </div>
    <div class="chaxun01"><a href="javascript:shaixuan('.shaixuan')"><span>•</span>条件筛选</a></div>
    <div class="chaxun02"><h1>您找到<span>{{ $colleges->total() }}</span>所相关学校</h1><h2><a href="#">排序</a></h2></div>
    <div class="grzy_wdsc_list">
        <ul>
            @foreach($colleges as $college)
            <a href="{{route('colleges.show', $college->key)}}" ><li>
                    <img src="{{app('qiniu_uploader')->pathOfKey($college->badge_path)}}" style="display: block; margin: 0 auto;">
                <h1>{{$college->chinese_name}}<br><font>{{$college->english_name}}</font></h1>
                </li></a>
           @endforeach

        </ul>
    </div>
    <div class="clear"></div>
    <div class='page'>
    {{ $colleges->appends(app('request')->except('page'))->render() }}
    </div>
</div>

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
                    <span>地点</span>
                    <em><input type="hidden" number v-model="selected_country_id"  name="selected_country_id" value="{{$selected_country_id }}"/>
                        <input type="hidden" number v-model="selected_state_id"  name="selected_state_id" value="{{$selected_state_id}}"/>
                        <a href="javascript:void(0)" class="didiana">不限</a></em>
                    <div class="clear"></div>
                </li>
                <li class="grzy_wdzl01"  onclick="javascript:shaixuan('.zhuanye')">
                    <span>选择专业</span>
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
                        
                        <a href="javascript:void(0)" class="paiminga">不限</a></em>
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
        <div class="header_c">选择地点</div>
        <div class="header_r"><a href="javascript:queding('didian','selected_country_id');" class="queren">确认</a></div>
    </div>
    <div class="clear"></div>
    <div class="main03"><!--id="main03_l_menu"-->
        <div class="main03_l">
            <a href="javascript:void(0)" class="childpar" area_id='0' >不限</a>
            @foreach($areas as $key=>$val)
            <a  href="javascript:void(0)" class="childpar" area_id='{{ $val->id }}'>{{ $val->name }}</a>
            @endforeach
           
        </div>
        <div class="main03_r">
            <span><a href="javascript:void(0)">全部</a></span>
            <em>
                <a href="javascript:void(0)">全部</a>
            </em>
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
        <div class="header_c">选择专业</div>
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

<div class="paiming tiaojian" rank_start='' rank_end='' paiminghtm=''>
    <div class="header">
        <a href="javascript:goBlack2('.shaixuan')"><div class="header_l"><img src="/static/images/back.png" height="20" /></div></a>
        <div class="header_c">学校排名</div>
        <div class="header_r"><a href="javascript:pmqueding();">确认</a></div>
    </div>
    <div class="clear"></div>
    <div class="main02">
        <div class="yuanxiao_sx">
            <a href="javascript:void(0)" class="paimingchilds" rank_start='0' rank_end='0'>不限</a>
            <a  href="javascript:void(0)" class="paimingchilds" rank_start='1'  rank_end='8'>1-8</a>
            <a href="javascript:void(0)" class="paimingchilds" rank_start='9'  rank_end='20'>9-20</a>
            <a href="javascript:void(0)" class="paimingchilds" rank_start='21'  rank_end='50'>21-50</a>

        </div>
        <div class="yuanxiao_pm">
            <form action="" method="get">
                <div class="yuanxiao_pm_l">
                    <input name="start" class="paiming01 start" type="text"><span>~</span><input class="paiming01 end" name="end" type="text">
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
