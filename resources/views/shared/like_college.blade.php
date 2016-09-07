<div class="alert-success" id="alert-{{ $template_name }}"></div>

<script type="text/javascript">
    Vue.component('{{ $template_name }}', {
        template: '#{{ $template_name }}',
        props: ['college_id', 'liked', 'like_nums', 'showLoginAndRegisterPanel'],
        data: function () {
            return {
                posting: 0
            }
        },
        methods: {
            likeCollege: function(){
                if (this.posting == 1) {
                    return ;
                }
                this.posting = 1;
                var that = this;
                this.$http.post("{{ route('like.store') }}", {
                    college_id: this.college_id
                }).then(function(){
                    //alert('收藏成功');
                    that.liked = true;
                    this.$dispatch('changeLikeDispatch', this.college_id, that.liked);

                    $('#alert-{{ $template_name }}').html('收藏成功');
                    $('#alert-{{ $template_name }}').fadeIn();
                    setTimeout("$('#alert-{{ $template_name }}').fadeOut();",2000);
                    that.posting = 0;
                }, function(response){
                    if(response.status == 401){
                        //alert('请登陆后收藏院校');
                        //this.showLoginAndRegisterPanel = true;
                        this.$dispatch('toShowLoginAndRegisterPanel');
                    }
                    that.posting = 0;
                });
            },
            dislikeCollege: function(){
                if (this.posting == 1) {
                    return ;
                }
                this.posting = 1;
                var that = this;
                this.$http.post("{{ route('like.destroy') }}", {
                    college_id: this.college_id
                }).then(function(){
                    //alert('取消收藏成功');
                    that.liked = false;
                    this.$dispatch('changeLikeDispatch', this.college_id, that.liked);
                    $('#alert-{{ $template_name }}').html('取消收藏成功');
                    $('#alert-{{ $template_name }}').fadeIn();
                    setTimeout("$('#alert-{{ $template_name }}').fadeOut();",2000);
                    that.posting = 0;
                }, function(response){
                    that.posting = 0;
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