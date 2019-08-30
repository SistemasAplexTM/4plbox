<template>
  <div class="row">
      <div class="col-lg-2">
          <div class="ibox float-e-margins">
              <div class="mailbox-content">
                  <div class="">
                      <h5>Integraciones</h5>
                      <ul class="folder-list m-b-md" style="padding: 0">
                          <li v-for="item in list" @click="currentView=item.component" class="m5">
                            <a href="#">
                              <i :class="item.icon"></i> {{ item.desc }}
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
          <component v-bind:is="currentView" :key="currentView" :agency_id="agency_id"></component>
        </transition>
      </div>
  </div>
</template>

<script>
    import MailChimp from './MailChimp.vue'
    import PayPal from './PayPal.vue'
    import Zopim from './Zopim.vue'
    import Publicity from './Publicity.vue'
    export default {
      components: {
        MailChimp,
        PayPal,
        Zopim,
        Publicity
      },
      data(){
        return{
          currentView: 'MailChimp',
          list: [
            { component: 'MailChimp', desc: 'MailChimp', icon: 'fab fa-mailchimp' },
            { component: 'PayPal', desc: 'PayPal', icon: 'fab fa-paypal' },
            { component: 'Zopim', desc: 'Zopim', icon: 'fal fa-comments' },
            { component: 'Publicity', desc: 'Publicidad', icon: 'fal fa-shopping-cart' }
          ]
        }
      },
      props: ["agency_id"],
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
