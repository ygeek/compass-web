<div class="main04">
    <div class="yxpaiming01"><a href="/estimate/step-1">测试录取率</a></div>
     <?php $index = 0; ?>
        @foreach($articles as $article)
        
    <div class="yuanxiao_jj01">
        <div class="yuanxiao_jj_name01">{{ $article->title }}</div>
        <div class="yuanxiao_jj_m01">
            <?php echo  html_entity_decode($article->content); ?>
        </div>
    </div>
        @endforeach
   
    <div class="clear"></div>
</div>