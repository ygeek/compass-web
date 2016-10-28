@include('m.public.header')
<style>
#header{ display: none;}    
</style>
<div class="main02html">
    <div class="main02" style=" background: #fff;">
   @include('m.home.editUser')
    
    <div class="clear"></div>
</div>
<div class="clear"></div>
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
       
    }
    #content { min-height: 600px;}
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
.mm-hasnavbar-top-4 .mm-panels { top:300px;}
    .mm-listview > li:not(.mm-divider)::after { left: 0px;}
    .mm-listview .mm-next::before { border: none;}
</style>
<script>
    
function mmenuShow(conid)
{
    $('#menu').show();
    $("#header").hide();
    $('body,html').animate({ scrollTop: 0 }, 1);
}

</script>
<script type="text/javascript" src="/static/mmenu/js/jquery.mmenu.all.min.js?v=5.7.1"></script>
<script type="text/javascript">
$(function() {
    var headhtml = $('.main02html').html();
    $("#menu")
        .mmenu({
            offCanvas	: false,

            extensions	: ["theme-white"],

            navbar		: {
                title	: "个人中心"
            },

           navbars		: [{
						content 	: [ "prev", "title", "next" ]
					}, {
						content 	: headhtml,
						height 		: 3
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
                <em class="Counter" vid=''></em>
                <span>我的资料</span>

                <!-- subpanel -->
                <div id="locations" class="Panel">
                    <ul>
                        {!!Form::open(['route' => 'home.store_profile', 'enctype' => 'multipart/form-data']) !!}
                        <li id="setting-location">
                            <em class="Counter" vid=''></em>
                            <span>用户名</span>
                        </li>
                        <li class="searchresult">
                            <em class="Counter" vid=''></em>
                            <span>邮箱</span>
                        </li>
                        <li class="searchresult">
                            <em class="Counter" vid=''></em>
                            <span>手机号</span>
                        </li>
                      {!!Form::close() !!}
                        
                    </ul>
                </div>
            </li>
            <li id="setting-radius">
                <a href="{{ route('home.messages') }}"><em class="Counter" vid=''></em>
                    <span>我的消息</span></a>

                <!-- subpanel -->
                <div id="radius" class="Panel">
                    
                </div>
            </li>
            <li id="setting-shoucang">
                <a href="{{ route('home.like_colleges') }}"><em class="Counter" vid=''></em>
                <span>我的收藏</span></a>
                <div id="radius" class="Panel"></div>
                
            </li>
            @if(app('auth')->user()->estimate!=null)
            <li id="setting-jieguo">
               <a href="javascript:document.getElementById('estimate_form').submit();"> <em class="Counter" vid=''></em>
                   <span>评估结果</span></a>
                 <form action="{{ URL::route('estimate.store') }}" method="POST" style="display: none" id="estimate_form">
                    <input type="hidden" name="estimate_id" value="{{app('auth')->user()->estimate}}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                </form>
                <div id="radius" class="Panel"></div>
            </li>
            @endif
            <li id="setting-dan">
                <a href="/home/intentions"><em class="Counter" vid=''></em>
                    <span>我的意向单</span></a>
                    <div id="radius" class="Panel">
                    
                </div>
                
            </li>
            <li id="setting-tuichu">
                <a href="/auth/logout"><em class="Counter" vid=''></em>
                    <span>退出</span></a>
                <div id="radius" class="Panel"></div>
                
            </li>
            <!-- navbar info 
            <div class="Hidden" style="display:none;">
			
                <a class="Next" href="javascript:searSub();" >确定</a>
			</div>-->

        </ul>


    </div>
            
</nav>

