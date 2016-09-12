<estimate-panel v-bind:show-estimate-panel.sync="showEstimatePanel" v-bind:estimate-panel-path.sync="estimatePanelPath"></estimate-panel>
<template id="estimate-panel-template">
    <div v-show="showEstimatePanel">
        <div style="width: 100%;height: 100vh;position: fixed;top: 0;left: 0;background: rgba(0,0,0,0.5);z-index: 99999999;"></div>
        <div id="position_div" style="z-index: 99999999;position: fixed;top: calc(50% - 250px);right: calc(50% - 250px)">
            <div id="close_iframe" v-on:click="close" style="position: absolute;top: 10px;right: 10px;cursor: pointer;font-size: 30px">
                ×
            </div>
            <iframe id="estimate_iframe" runat="server" width="500px" height="500px"  frameborder="no" scrolling="no"  style="background-color: #f3f3f3;"></iframe>
        </div>
    </div>
</template>

<script>
    Vue.component('estimate-panel', {
        template: '#estimate-panel-template',
        props: ['showEstimatePanel', 'estimatePanelPath'],
        methods: {
            close: function (e) {
                this.showEstimatePanel = false;
            }
        },
        watch: {
            'showEstimatePanel': function (val, oldVal) {
                if (val && val!=oldVal){
                    var iframe = document.getElementById('estimate_iframe');
                    iframe.src = this.estimatePanelPath;
                }
            }
        }
    })
</script>

