<?php
    $galleries = $articles->map(function($article){
        return $article->toGallery();
    });
?>
<college-galleries :galleries='{!! json_encode($galleries) !!}'></college-galleries>

<template id="college-galleries">
    <div class="college-galleries">
        <college-gallery @click="showGallery($index)" v-for="gallery in galleries" :gallery="gallery"></college-gallery>
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
    <div class="gallery-view mask" v-show="show" style="z-index: 9999999;">
        <span class="close" @click="show=false">×</span>
        <span class="icon-wrap" @click="preImage()"></span>

        <header>
          <h1>@{{ gallerys[galleryIndex].name }}</h1>
        </header>

        <div class="image-viewer">
            <img v-bind:src="currentShowImage" />
        </div>
        <span class="icon-wrap-next" @click="nextImage()"></span>
    </div>
</template>

<script type="text/javascript">
    Vue.component('college-galleries', {
        template: "#college-galleries",
        props: ["galleries"],
        methods: {
            showGallery: function(galleryIndex){
                this.$broadcast('show-gallery', this.galleries,galleryIndex);
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
                gallerys: [{name: "", images: [""]}],
                showIndex: 0,
                galleryIndex: 0
            }
        },
        computed: {
            currentShowImage: function(){
                return this.gallerys[this.galleryIndex].images[this.showIndex];
            },
            totalImages: function(){
                return this.gallerys[this.galleryIndex].images.length;
            },
            totalGallery: function(){
                return this.gallerys.length;
            },
            hasNext: function(){
                return this.showIndex != this.totalImages -1 && this.galleryIndex != this.totalGallery -1;
            },
            hasPre: function(){
                return this.showIndex != 0 && this.galleryIndex != 0;
            }
        },
        methods: {
          preImage: function(){
            var preIndex = this.showIndex - 1;
            if(preIndex < 0){
                var preGallery = this.galleryIndex - 1;
                if(preGallery < 0){
                    preGallery = this.totalGallery -1;
                }
                this.galleryIndex = preGallery;
                preIndex = this.totalImages - 1;
            }
            this.showIndex = preIndex;
          },
          nextImage: function(){
            var nextIndex = this.showIndex + 1;
            if(nextIndex > this.totalImages - 1){
                var nextGallery = this.galleryIndex + 1;
                if(nextGallery > this.totalGallery - 1){
                    nextGallery = 0;
                }
                this.galleryIndex = nextGallery;
                nextIndex = 0;
            }
            this.showIndex = nextIndex;
          }
        },
        events: {
            'show-gallery': function(gallerys,galleryIndex){
                this.gallerys = gallerys;
                this.show = true;
                this.showIndex = 0;
                this.galleryIndex = galleryIndex;
            },
        }
    })
</script>
