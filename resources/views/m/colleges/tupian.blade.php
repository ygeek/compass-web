<div id="college-page-nav" style="margin-bottom: 60px;"></div>
<?php
    $galleries = $articles->map(function($article){
        return $article->toGallery();
    });
?>
<style>
.demo-gallery > ul {
		margin-bottom: 0;
	}
	.demo-gallery > ul > li {
		float: left;
		margin-bottom: 15px;
		margin-right: 20px;
		width: 200px;
	}
	.demo-gallery > ul > li a {
		border: 3px solid #FFF;
		border-radius: 3px;
		display: block;
		overflow: hidden;
		position: relative;
		float: left;
	}
	.demo-gallery > ul > li a > img {
		-webkit-transition: -webkit-transform 0.15s ease 0s;
		-moz-transition: -moz-transform 0.15s ease 0s;
		-o-transition: -o-transform 0.15s ease 0s;
		transition: transform 0.15s ease 0s;
		-webkit-transform: scale3d(1, 1, 1);
		transform: scale3d(1, 1, 1);
		height: 100%;
		width: 100%;
	}
	.demo-gallery > ul > li a:hover > img {
		-webkit-transform: scale3d(1.1, 1.1, 1.1);
		transform: scale3d(1.1, 1.1, 1.1);
	}
	.demo-gallery > ul > li a:hover .demo-gallery-poster > img {
		opacity: 1;
	}
	.demo-gallery > ul > li a .demo-gallery-poster {
		background-color: rgba(0, 0, 0, 0.1);
		bottom: 0;
		left: 0;
		position: absolute;
		right: 0;
		top: 0;
		-webkit-transition: background-color 0.15s ease 0s;
		-o-transition: background-color 0.15s ease 0s;
		transition: background-color 0.15s ease 0s;
	}
	.demo-gallery > ul > li a .demo-gallery-poster > img {
		left: 50%;
		margin-left: -10px;
		margin-top: -10px;
		opacity: 0;
		position: absolute;
		top: 50%;
		-webkit-transition: opacity 0.3s ease 0s;
		-o-transition: opacity 0.3s ease 0s;
		transition: opacity 0.3s ease 0s;
	}
	.demo-gallery > ul > li a:hover .demo-gallery-poster {
		background-color: rgba(0, 0, 0, 0.5);
	}
	.demo-gallery .justified-gallery > a > img {
		-webkit-transition: -webkit-transform 0.15s ease 0s;
		-moz-transition: -moz-transform 0.15s ease 0s;
		-o-transition: -o-transform 0.15s ease 0s;
		transition: transform 0.15s ease 0s;
		-webkit-transform: scale3d(1, 1, 1);
		transform: scale3d(1, 1, 1);
		height: 100%;
		width: 100%;
	}
	.demo-gallery .justified-gallery > a:hover > img {
		-webkit-transform: scale3d(1.1, 1.1, 1.1);
		transform: scale3d(1.1, 1.1, 1.1);
	}
	.demo-gallery .justified-gallery > a:hover .demo-gallery-poster > img {
		opacity: 1;
	}
	.demo-gallery .justified-gallery > a .demo-gallery-poster {
		background-color: rgba(0, 0, 0, 0.1);
		bottom: 0;
		left: 0;
		position: absolute;
		right: 0;
		top: 0;
		-webkit-transition: background-color 0.15s ease 0s;
		-o-transition: background-color 0.15s ease 0s;
		transition: background-color 0.15s ease 0s;
	}
	.demo-gallery .justified-gallery > a .demo-gallery-poster > img {
		left: 50%;
		margin-left: -10px;
		margin-top: -10px;
		opacity: 0;
		position: absolute;
		top: 50%;
		-webkit-transition: opacity 0.3s ease 0s;
		-o-transition: opacity 0.3s ease 0s;
		transition: opacity 0.3s ease 0s;
	}
	.demo-gallery .justified-gallery > a:hover .demo-gallery-poster {
		background-color: rgba(0, 0, 0, 0.5);
	}
	.demo-gallery .video .demo-gallery-poster img {
		height: 48px;
		margin-left: -24px;
		margin-top: -24px;
		opacity: 0.8;
		width: 48px;
	}
	.demo-gallery.dark > ul > li a {
		border: 3px solid #04070a;
	}
	.home .demo-gallery {
		padding-bottom: 80px;
	}
</style>
<link rel="stylesheet" type="text/css" href="/static/lunbo/dist/css/lightgallery.min.css">
<div class="main04">
   
    
    <div class="yuanxiao_pic ">
        @foreach($galleries as $key=>$gallery)
       <?php $gallery = objToArr($gallery); $i=0;  ?>
        <ul id="lightgallery{{$key}}" class="list-unstyled row">
            @foreach($galleries as $ke=>$galleryss)
            @foreach($galleryss['images'] as $k=>$val)
            <li @if($i!=$key) style="display:none" @endif class="col-xs-6 col-sm-4 col-md-3"  data-src="{{$val}}" data-sub-html="<h4>{{ $galleryss['name'] }}</h4>" >
                <a href="">
                    <img class="img-responsive" src="{{$val}}" alt="Thumb-1">
                    <h1>{{ $gallery['name'] }}</h1>
                </a>
            </li>
            <?php $i++;?>
            @endforeach
            @endforeach
        </ul>
      
        @endforeach
        <div class="clear"></div>
    </div>
    <div class="clear"></div>
</div>
@include('m.colleges.yuanxiao')
<script src="/static/lunbo/js/picturefill.min.js"></script>
<script src="/static/lunbo/dist/js/lightgallery.js"></script>
<script src="/static/lunbo/js/lg-pager.js"></script>
<script src="/static/lunbo/js/lg-autoplay.js"></script>
<script src="/static/lunbo/js/lg-fullscreen.js"></script>
<script src="/static/lunbo/js/lg-zoom.js"></script>
<script src="/static/lunbo/js/lg-share.js"></script>
@foreach($galleries as $key=>$gallery)
<script>
	lightGallery(document.getElementById('lightgallery{{$key}}'));
	
</script>
@endforeach