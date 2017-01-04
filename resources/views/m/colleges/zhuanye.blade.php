<div id="college-page-nav" style="margin-bottom: 60px;"></div>
<div class="main04">
   
    
    <div class="yuanxiao_cx_main" style="padding: 0px;">
        <div class="yx_chaxun" style="margin:2% 2%;float:left; width:78%;border-radius: 5px;">
            
            <form action="" id="sear" method="get">
                <input type="hidden" name="article_type" value="specialities" />
                <input type="hidden" name="selected_degree_id" id='selected_degree_id' value="" />
                <input type="hidden" name="selected_category_id" id='selected_category_id' value="" />
                <input type="text" class="chax_input" name="speciality_name" placeholder="专业"  value="<?php if(isset($_GET['speciality_name'])){ echo $_GET['speciality_name']; } ?>">
                <input type="submit" class="chax_so" value="">
            </form>
            
        </div>
        <div class="shaixuan_icon" style="height:50px; margin-top: 3%;">
            <img src="/static/images/shaixuan.png" height="50" onclick="mmenuShow('mmenu')" />
        </div>
        <div class="clear"></div>
    </div>
    <div class="chaxun10" style="height:40px; line-height: 40px; font-size: 1.2em;"><h1>为您找到<span> {{ $articles->total() }} </span>个相关专业</h1></div>

    <div class="yuanxiao_gzy">
        <ul class="zhuanyemore">
            @foreach($articles as $speciality)
                <?php
                $tmp = $college->administrativeArea->id;
                if ($college->administrativeArea->parent){
                    $tmp = $college->administrativeArea->parent->id;
                    if ($college->administrativeArea->parent->parent){
                        $tmp = $college->administrativeArea->parent->parent->id;
                    }
                }
                $estimate_url = route('estimate.step_second', ['selected_country_id' => $tmp, 'selected_degree_id' => $speciality->degree->id, 'speciality_category_id' => $speciality->category->id, 'speciality_name' => $speciality->name, 'cpm' => true, 'college_id' => $college->id]);
                ?>
            <li>
                <h1>{{ $speciality->name }}</h1>
                <p>学术类型：{{ $speciality->degree->name }}<br>专业方向：{{ $speciality->category->chinese_name }}</p>
                <a href="{{$estimate_url}}">测试录取率</a>
            </li>
            @endforeach
           
        </ul>
    </div>
    <div class="clear"></div>
    <?php if($articles->lastPage()>1){ ?>
    <div class="more page" onclick="getMore()" page="1" style="height:30px; line-height: 30px; width: 100%; margin: 0 auto;">
        加载更多...
    </div>
    <div class="over page"  style="height:30px; line-height: 30px; width: 100%; display: none; margin: 0 auto;">
        加载完成
    </div>
    <div class="moregif page" style="height:30px; line-height: 30px; display: none; ">
        <img src="/static/images/more.gif" width="30" height="30" style="display:inline;" />
    </div>
    <?php } ?>
</div>

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
    .mm-navbar.mm-hasbtns {
        padding: 0 40px; background-color: #1ddab0;
    }
    .mm-menu.mm-theme-white .mm-navbar a, .mm-menu.mm-theme-white .mm-navbar > *, .mm-menu.mm-theme-white em.mm-counter {
    color: #fff;
    }
    .mm-listview em.mm-counter + .mm-next.mm-fullsubopen + a, .mm-listview em.mm-counter + .mm-next.mm-fullsubopen + span, em.mm-counter + a.mm-fullsubopen + a, em.mm-counter + a.mm-fullsubopen + span {
        padding-right: 90px;
        font-size: 1.3em;
        color: #666;
    }
    .mm-listview > li > a, .mm-listview > li > span {
        color: #666;
        display: block;
        padding: 10px 10px 10px 20px;
        margin: 0;font-size: 1.3em;
    }
</style>
<script>
    
function mmenuShow(conid)
{
    $('#menu').show();
    $("#header").hide();
    $('body,html').animate({ scrollTop: 0 }, 1);
    $('.footer01').hide();
}
function getMore()
{
    var page = $(".more").attr("page");
    var pagenum = Number(page)+1;
    var params = $("#sear").serialize();
    $.ajax({
        type:'GET',
        url:'/colleges/northwestern-university?article_type=specialities&ajax=true&page='+pagenum+"&"+params+"#college-page-nav",
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
                 $(".over").show();
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
                title	: "专业筛选"
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
            
            <li id="setting-location">
                <em class="Counter" vid=''>不限</em>
                <span>学位类型</span>

                <!-- subpanel -->
                <div id="locations" class="Panel">
                    <ul>
                       
                        <li class="searchresult"><span vid=''>不限</span></li>
                        <li class="searchresult"><span vid='2'>本科</span></li>
                        <li class="searchresult"><span vid='3'>硕士</span></li>
                        
                    </ul>
                </div>
            </li>
            <li id="setting-radius">
                <em class="Counter" vid=''>不限</em>
                <span>专业方向</span>

                <!-- subpanel -->
                <div id="radius" class="Panel">
                    <ul>
                        <li><span vid=''>不限</span></li>
                        <li><span vid='1'>艺术与设计</span></li>
                        <li><span vid='2'>经济与工商管理</span></li>
                        <li><span vid='3'>工程与信息技术</span></li>
                        <li><span vid='4'>人文社会科学</span></li>
                        <li><span vid='5'>教育学</span></li>
                        <li><span vid='6'>医学</span></li>
                        <li><span vid='7'>农学</span></li>
                        <li><span vid='8'>理学</span></li>
                        <li><span vid='9'>法学</span></li>
                       
                    </ul>
                </div>
            </li>
            <!-- navbar info -->
            <div class="Hidden" style="display:none;">
			
                <a class="Next" href="javascript:searSub();" >确定</a>
			</div>

        </ul>


    </div>
            
</nav>
