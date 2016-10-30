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
    .mm-listview .mm-counter
        {
            right: 85px;
        }
        a.remove
        {
            background: #f99;
            border-radius: 20px;
            color: #fff;
            text-align: center;
            vertical-align: middle;
            line-height: 21px;
            display: inline-block;
            width: 20px;
            height: 20px;
            margin: 0 30px 0 -50px;

            -webkit-transition: margin .5s ease;
            transition: margin .5s ease;
        }
        .removing a.remove
        {
            margin: 0 10px 0 0;
        }
       
        .mm-navbar-top-2 p
        {
            color: #333 !important;
            padding: 30px;
            margin: 0;
        }
        .mm-navbar a
        {
            color: #fff !important;
        }

        a.mm-prev,
        a.mm-next
        {
            width: auto;
        }
        a.mm-prev:before,
        a.mm-next:after
        {
            
        }
        a.mm-prev
        {
            padding-left: 15px;
            text-align: left;
        }
        a.mm-next
        {
            padding-right: 15px;
            text-align: right;
        }

        .mm-toggle + span
        {
            padding-right: 135px !important;
        }
        label
        {
            display: block;
            margin-bottom: 10px;
        }
        select
        {
            width: 45%;
            float: left;
        }
        select + select
        {
            float: right;
        }
        a.button {
            width: auto;
            position: absolute;
            bottom: 20px;
            left: 20px;
            right: 20px;
        }
        .mm-listview > li > a, .mm-listview > li > span {
            color: inherit;
            display: block;
            padding: 10px 10px 10px 20px;
            margin: 0;
            font-size: 0.9em;
            white-space: normal;
            width: 90%;
            line-height: 15px;
        }
    #menu {
        min-width: none;
        max-width: none;
        width: 100%;
        height: 100%;
       
    }
    #inp-name{
        box-sizing: border-box;
        border-radius: 5px;
        text-transform: none;
        text-indent: 0;
       
        vertical-align: middle;
        line-height: 20px;
        display: block;
        height: 40px;
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        float: right;
    }
    #inp-edit{
        top:30%;
    }
    #content { min-height: 600px;}
    .mm-menu.mm-theme-white .mm-btn::after{border:none; text-align: right;}
    .mm-navbar .mm-btn:last-child { text-align: right; width: 60px;;}
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
    #edit { line-height: 30px;width: 30%;background-color: #0e2d60;color: #fff;text-align: center;padding-top: 0px;top: 40%; cursor: pointer;}
    #locations .edit::after { border: none;} 
    #editbase .edit::after { border: none;} 
    #editpwd .edit::after { border: none;}
    #editmobile .edit::after { border: none;} 
    #upload { 
       
        border-radius: 5px;
        text-transform: none;
        text-indent: 0;
       
        vertical-align: middle;
        line-height: 20px;
        display: block;
        height: 40px;
        width: 60%;
        padding: 10px;
      
        float: right;
    }
    .mm-listview .mm-next.mm-fullsubopen {
        width: 80%;
    }
    
   
    
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



<style type="text/css">


        
</style>
		

	
<script type="text/javascript">
    $(function() {
        var $menu = $("#menu").mmenu({
            offCanvas	: false,
            dividers 	: {
                add   : false,
                fixed : true
            },
            extensions	: ["theme-white"],
            navbar		: {
                title 		: "我的消息"
            },
            navbars		: [{
                content 	: [ "prev", "title", "next" ]
            }]
        });

        var API = $menu.data( "mmenu" );

        var $app		= $("#app"),
            $alarms		= $("#alarms"),
            $inpname 	= $("#inp-name"),
            $inphours	= $("#inp-hours"),
            $inpminutes	= $("#inp-minutes");

        var appPanel = true;
        API.bind( "openPanel", function( $panel ) {
            if ( $panel.is( '#new') )
            {
                $inpname.val( '' );
                $inphours.val( 12 );
                $inpminutes.val( '00' );
            }
            $menu.removeClass( 'removing' );
        });

        $('#menu')
            .find( '.mm-navbar .mm-next' )
            .on( 'click',
                function( e )
                {
                    if ( $menu.find( '.mm-panel.mm-current' ).is( '#app' ) )
                    {
                        $menu.toggleClass( 'removing' );
                        $(this).html( $menu.hasClass( 'removing' ) ? 'done' : '&times;' );
                    }
                }
            );

        $alarms
            .on( 'click',
                '.remove',
                function( e )
                {
                    e.preventDefault();
                    $(this).closest( 'li' ).fadeOut(function() { 
                        $(this).css({
                            display: "block",
                            opacity: 0
                        }).slideUp(function() {
                            var _id = $(this).attr('vid');
                            console.log(_id);
                            $(this).remove();
                            $.ajax({
                                 type:'POST',
                                 url:'/home/messages/'+_id,
                                 data:'_token='+$('meta[name="_token"]').attr('content'),
                                 async:false,
                                 headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')},
                                 dataType:'json',
                                 success:function(e){
                                     console.log(e);
                                 }
                             }); 
                        });
                    });
                }
            );

        var id = 0;
        
    });
</script>
</head>
<body>
<nav id="menu">
    <div id="app" class="wrapper">

        <ul id="alarms">
            @foreach($messages['data'] as $message)
            <li vid="{{$message['id']}}">
                <em class="Counter"  vid=''></em>
                <span ><a href="#" class="remove" >&times;</a>{{$message['title']}} {{$message['created_at']}}</span>
                <div id="content" class="Panelsss">
                    {{$message['content']}}
                    <div class="Hidden" style="display:none;">
                        <a class="Prev" href="#app"></a>
                        <a class="Title" >详细内容</a>
                    </div>
                </div>
                
            </li>
            @endforeach
           
        </ul>

        <!-- navbar info -->
        <div class="Hidden" style="display:none;">
            <a class="Prev" href="/home"><</a>
            <a class="Next" href="#remove">&times;</a>
        </div>
    </div>
    
</nav>
