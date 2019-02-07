<template>
  <div class="row">
      <div class="col-lg-2">
          <div class="ibox float-e-margins">
              <div class="mailbox-content">
                  <div class="">
                      <h5>Configuración</h5>
                      <ul class="folder-list m-b-md" style="padding: 0">
                          <li v-for="item in list" @click="currentView=item.component" class="m5">
                            <a href="#">
                              <i :class="'fal fa-'+item.icon"></i> {{ item.desc }}
                            </a>
                          </li>
                      </ul>
                      <div class="clearfix"></div>
                  </div>
              </div>
          </div>
      </div>
      <div class="col-lg-10">
        <transition name="fade" mode="out-in">
          <component v-bind:is="currentView" :key="currentView"></component>
        </transition>
      </div>
  </div>
</template>

<script>
    import Document from './Document.vue'
    import FormatNumber from './FormatNumber.vue'
    export default {
      components: {
        Document,
        FormatNumber,
        Localization: {
          template: "<p>About</p>"
        },
        Money: {
          template: "<p>Contact</p>"
        }
      },
      data(){
        return{
          currentView: 'Document',
          list: [
            {component: 'Document', desc: 'Documento', icon: 'file'},
            {component: 'FormatNumber', desc: 'Formatos de número', icon: 'funnel-dollar'},
            {component: 'Localization', desc: 'Localización', icon: 'map-marker'}
          ]
        }
      }
    }
</script>
<style>
.fade-enter{
  opacity: 0;
}
.fade-enter-active{
  transition: opacity 0.3s;
}
.fade-leave-to{
  opacity: 0;
}
.fade-leave-active{
  transition: opacity 0.3s;
}

</style>
