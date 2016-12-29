
@include('m.public.header')
<style>
#header{ display: none;}
</style>
<?php
  function flatten_categories($categories) {
    $res = [];
    foreach($categories as $category){
      $res[$category['_id']] = $category['name'];
      if(count($category['children']) > 0) {
        $res = array_merge(flatten_categories($category['children']), $res);
      }
    }

    return $res;
  };

  $flatten_categories = flatten_categories($ranking_categories);
?>
<div class="header paimingheader">
        <a href="/index.php"><div class="header_l"><img src="/static/images/back.png" height="20" /></div></a>
        @foreach($rankings_for_show as $ranking)
        <div class="header_c"> {{$flatten_categories[$ranking['category_id']]}} {{$ranking['name']}}</div>
        @endforeach
        <div class="header_r"><img src="/static/images/shaixuan_bai.png" style="margin-top:15px;" height="30" onclick="mmenuShow('mmenu')" /></div>
</div>
<div class="clear"></div>

<div class="main06" style="min-height:600px;">
    <div class="paiming50">
          <h1>排名</h1>
          <h2>院校名称</h2>
          <h3>&nbsp;</h3>
          <div class="clear"></div>
    </div>
    <div class="yuanxiao_pm6">
        <ul class="zhuanyemore">
            @foreach($colleges as $key=>$college)
            <li @if($key%2==1)class="yuanxiao_white"@endif><h1>{{ $college['rank'] }}</h1><h2>{{ $college['chinese_name'] }}<br/>{{ $college['english_name'] }}</h2><h3>&nbsp;</h3><span><a href="{{route('colleges.show', ['key' => \App\College::generateKey($college['key']) ])}}">排名</a></span><div class="clear"></div></li>
            @endforeach
        </ul>
        <?php if($colleges->lastPage()>1){ ?>
        <div class="more page" onclick="getMore()" page="1" style="height:30px; line-height: 30px; width: 30%; margin: 0 auto; margin-bottom: 80px;">
            加载更多...
        </div>

        <div class="moregif page" style="height:30px; line-height: 30px; display: none;  margin-bottom: 80px;">
            <img src="/static/images/more.gif" width="30" height="30" style="display:inline;" />
        </div>
        <?php } ?>
    </div>


</div>
<div class="mianfeipinggu"><a href="/estimate/step-1">开启免费评估</a></div>



<link rel="stylesheet" href="/static/mmenu/demo.css?v=5.7.1" />
<link rel="stylesheet" href="/static/mmenu/css/jquery.mmenu.all.css?v=5.7.1" />
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" />

<style type="text/css">
    #menu {
        min-width: none;
        max-width: none;
        width: 100%;
        height: 100%;
        display: none;
    }
    .mm-menu.mm-theme-white .mm-btn::after{border:none; text-align: left;}
    .mm-navbar .mm-btn:last-child { text-align: left; width: 60px;;}
    .mm-navbar { height: 60px; line-height: 40px; font-size: 1.3em;}
    .mm-hasnavbar-top-1 .mm-panels { top:60px;}
    .mm-listview { line-height: 30px;}
    .mm-navbar .mm-btn:first-child { height:60px;}
    .mm-menu > .mm-navbar { background:#1ddab0;  color: #fff;}
    .mm-menu.mm-theme-white .mm-navbar a, .mm-menu.mm-theme-white .mm-navbar > * { color: #fff;}
    .mm-menu.mm-theme-white .mm-btn::before { boder-color:rgba(255,255,255,255);}
    .mm-menu.mm-theme-white .mm-btn::after, .mm-menu.mm-theme-white .mm-btn::before {
    border-color: rgba(255,255,255,1);
}
</style>
<script>

function mmenuShow(conid)
{
    $('#menu').show();
    $(".header").hide();
    $('body,html').animate({ scrollTop: 0 }, 1);
}

function mmenuClose()
{
    $('#menu').hide();
    $(".paimingheader").show();

}

function getMore()
{
    var page = $(".more").attr("page");
    var pagenum = Number(page)+1;
    //var params = $("#search").serialize();
    $.ajax({
        type:'GET',
        url:'/colleges_rank?category_id={{$selected_category_id}}&ajax=true&page='+pagenum,
        data:'',
        async:false,
        headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')},
        dataType:'json',
        beforeSend:function(r){
            $(".more").hide();
            $(".moregif").show();
        },
        success:function(e){
            var htm = '';

            if(e.status=="ok")
            {
                console.log(e);
                htm = e.data;

                console.log(htm);
                $(".zhuanyemore").append(htm);
                $(".more").show();
                $(".more").attr("page",pagenum);
                $(".moregif").hide();
            }
            else
            {
                $(".more").attr("page",pagenum);
                $(".moregif").hide();
            }
        }
    });
}
</script>
<script type="text/javascript" src="/static/mmenu/js/jquery.mmenu.all.min.js?v=5.7.1"></script>
<script type="text/javascript">
$(function() {
    $("#menu")
        .mmenu({
            offCanvas	: false,

            extensions	: ["theme-white"],

            navbar		: {
                title	: "查看排名"
            },

            navbars		: [{
						content 	: [ "prev", "title", "next" ]
					}],


            onClick		: {
                setSelected	: false
            }},{})
        .on(
            'click',
            'a[href^="#/"]',
            function() {
                alert( "Thank you for clicking, but that's a demo link." );
                    return false;
            }
        );
});
function searSub()
{
    $("form:last").submit();
}
</script>
<script type="text/javascript">
    $(function() {

        var $settings = $("#settings");

        var api = $("#menu").data( "mmenu" );

        //	Choose location
        var $set_location = $("#setting-location .mm-counter");
        $("#locations").find( "li span" ).click(function() {
            $set_location.text( $(this).text() );
            $("#selected_degree_id").val($(this).attr('vid') );
            api.openPanel( $settings );
        });

        //	Choose radius
        var $set_radius = $("#setting-radius .mm-counter");
        $("#radius").find( "li span" ).click(function() {
            $set_radius.text( $(this).text() );
            $("#selected_category_id").val($(this).attr('vid') );
            api.openPanel( $settings );
        });

        //	Show/hide searchresults
        var $results = $(".searchresult");
        $("#locations input").keyup(function() {
            $results[ ( $(this).val() == "" ) ? "hide" : "show" ]();
        });

        //	Choose pricerange
        var $set_range = $("#setting-pricerange .mm-counter"),
            $range_from = $("#price-from"),
            $range_till = $("#price-till");

        $("#pricerange").find( ".button" ).click(function() {
            $set_range.text( $range_from.val() + " - " + $range_till.val() );
        });
    });
</script>

<nav id="menu">



    <!-- subpanel -->
    <div id="settings" class="Panel">
        <ul>
            @foreach($ranking_categories as $category)
            <li >
                <em class="Counter" vid=''>不限</em>
                <span>{{ $category['name'] }}</span>

                <!-- subpanel -->
                <div  class="Panel">
                    <ul>

                       @foreach($category['children'] as $val)
                       <li class="searchresult" >


                            <em class="Counter" vid=''>不限</em>
                            <span>{{ $val['name'] }}</span>

                            <!-- subpanel -->
                            <div  class="Panel2">
                                <ul>
                                    <?php
                                        $rankings_for_show2 = [];
                                        foreach ($rankings['rankings'] as $ranking) {
                                            if($ranking['category_id'] == $val['_id']){
                                                $rankings_for_show2[] = $ranking;
                                             }

                                        }

                                        foreach ($rankings_for_show2 as $ranking) {
                                            if($ranking['name']){
                                     ?>
                                     <li class="searchresult"><a href="{{ route('colleges.rank', ['category_id' => $val['_id'], 'ranking_id' => $ranking['_id']]) }}">{{ $ranking['name'] }}</a></li>
                                    <?php }else{  ?>
                                     <li class="searchresult"><a href="{{route('colleges.rank', ['category_id' => $val['_id']])}}">{{ $val['name'] }}</a></li>
                                    <?php } }?>
                                </ul>
                            </div>

                       </li>
                        @endforeach


                    </ul>
                </div>
            </li>
            @endforeach

            <!-- navbar info -->
            <div class="Hidden" style="display:none;">
                <a class="Prev" href="javascript:mmenuClose();"></a>
                <a class="Next" href="javascript:searSub();" >确定</a>
			</div>

        </ul>


    </div>

</nav>
