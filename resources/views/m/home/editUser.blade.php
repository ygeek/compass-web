
<div class="grzy_wdxx">
    <img src="{{ app('auth')->user()->getAvatarPath() }}" width="100" height="100" style="border-radius:100px;">
    <br>{{ app('auth')->user()->username }}<a href="#"><img src="/static/images/icon13.png" style="margin:0 0 0 5px;"></a>
</div>
    
<div class="clear"></div>
<div class="main02 editUser" style="display: none;">
    <div class="grzy_wdzl">
        <ul>
            <li style="padding:7% 5%; line-height:90px; height:90px; ">
                <span>头像</span>
                <a href="#"><img src="/static/images/banner.jpg" width="90" height="90"></a>
                <div class="clear"></div>
            </li>
            <li class="grzy_wdzl01">
                <span>用户名</span>
                <em><a href="#">小野妹子</a></em>
                <div class="clear"></div>
            </li>
            <li class="grzy_wdzl01">
                <span>密码</span>
                <em><a href="#">********</a></em>
                <div class="clear"></div>
            </li>
            <li class="grzy_wdzl01">
                <span>手机号</span>
                <em><a href="#">0824-13390783621</a></em>
                <div class="clear"></div>
            </li>
            <li class="grzy_wdzl01">
                <span>邮箱</span>
                <em><a href="#">是您找回密码的方式之一，建t议您设置并验证邮箱</a></em>
                <div class="clear"></div>
            </li>
        </ul>
    </div>
    <div class="clear"></div>
</div>
</body>
</html>
