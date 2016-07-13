<?php 
    $galleries = $articles->map(function($article){
        return $article->toGallery();
    });
?>
<college-galleries :galleries='{!! json_encode($galleries) !!}'></college-galleries>

<template id="college-galleries">
    <div class="college-galleries">
        <college-gallery @click="showGallery(gallery)" v-for="gallery in galleries" :gallery="gallery"></college-gallery>
    </div>

    <gallery-view></gallery-view>
</template>

<template id="college-gallery">
    <div class="college-gallery">
        <div class="img-container">
            <img v-bind:src="gallery.images[0]"/>
        </div>

        <div class="name">@{{ gallery.name }}</div>
    </div>
</template>

<template id="gallery-view">
    <div class="gallery-view mask" v-show="show">
        <span class="close" @click="show=false">X</span>
        <span class="icon-wrap" @click="showIndex--" v-show="hasPre"></span>

        <div class="image-viewer">
            <img v-bind:src="currentShowImage" />
        </div>
        <span class="icon-wrap-next" @click="showIndex++" v-show="hasNext"></span>
    </div>
</template>

<script type="text/javascript">
    Vue.component('college-galleries', {
        template: "#college-galleries",
        props: ["galleries"],
        methods: {
            showGallery: function(gallery){
                this.$broadcast('show-gallery', gallery);
            }
        }
    });

    Vue.component('college-gallery', {
        template: "#college-gallery",
        props: ["gallery"]
    });

    Vue.component('gallery-view', {
        template: "#gallery-view",
        data: function(){
            return {
                show: false,
                gallery: {name: null, images: []},
                showIndex: 0,
            }
        },
        computed: {
            currentShowImage: function(){
                return this.gallery.images[this.showIndex];
            },
            totalImages: function(){
                return this.gallery.images.length;
            },
            hasNext: function(){
                return this.showIndex != this.totalImages -1;
            },
            hasPre: function(){
                return this.showIndex != 0;
            }
        },
        events: {
            'show-gallery': function(gallery){
                this.gallery = gallery;
                this.show = true;
                this.showIndex = 0;
            },
        }
    })
</script>