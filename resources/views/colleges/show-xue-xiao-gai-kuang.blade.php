<template id="college-overview">
    <div class="college-overview">
        <ul class="tabs">
            <li v-for="article in articles" 
                v-bind:class="{'active': this.active_article.id == article.id}"
                @click="changeActiveArticle(article)"
            >
                @{{ article.title }}
            </li>
        </ul>

        <div class="content-areas">
            @{{{ active_article.content }}}
        </div>
    </div>
</template>

<script type="text/javascript">
    Vue.component('college-overview', {
        template: '#college-overview',
        data: function(){
            return {
                articles: {!! json_encode($articles->toArray()) !!},
                active_article: null
            } 
        },
        created: function(){
            this.active_article = this.articles[0];
        },
        methods: {
            changeActiveArticle: function(article){
                this.active_article = article;
            }
        }
    })
</script>

<college-overview></college-overview>
