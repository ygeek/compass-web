<div class="alert-success" id="alert-{{ $template_name }}">成功了</div>

<script type="text/javascript">
    Vue.component('{{ $template_name }}', {
        template: '#{{ $template_name }}',
        props: ['college_id', 'liked', 'like_nums'],
        methods: {
            likeCollege: function(){
                var that = this;
                this.$http.post("{{ route('like.store') }}", {
                    college_id: this.college_id
                }).then(function(){
                    //alert('收藏成功');
                    that.liked = true;
                    this.$dispatch('changeLikeDispatch', this.college_id, that.liked);
                    document.getElementById('alert-{{ $template_name }}').innerHTML='收藏成功';
                    document.getElementById('alert-{{ $template_name }}').style.display='block';
                    setTimeout("document.getElementById('alert-{{ $template_name }}').style.display='none'",2000);
                }, function(response){
                    if(response.status == 401){
                        alert('请登陆后收藏院校');
                    };
                });
            },
            dislikeCollege: function(){
                var that = this;
                this.$http.post("{{ route('like.destroy') }}", {
                    college_id: this.college_id
                }).then(function(){
                    //alert('取消收藏成功');
                    that.liked = false;
                    this.$dispatch('changeLikeDispatch', this.college_id, that.liked);
                    document.getElementById('alert-{{ $template_name }}').innerHTML='取消收藏成功';
                    document.getElementById('alert-{{ $template_name }}').style.display='block';
                    setTimeout("document.getElementById('alert-{{ $template_name }}').style.display='none'",1000);
                }, function(response){

                });
            }
        },
        events: {
            'changeCollegeLike': function (collegeid, like) {
                if (collegeid==this.college_id){
                    this.liked = like;
                    if (like){
                        this.like_nums = parseInt(this.like_nums)+1;
                    }
                    else {
                        this.like_nums = parseInt(this.like_nums)-1;
                    }
                }
            }
        }
    });
</script>