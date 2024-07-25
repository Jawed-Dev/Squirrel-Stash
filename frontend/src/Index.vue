  <template>
    <!-- <div class="grid-container h-[100vh] fixed w-full opacity-10 px-5 gap-5 z-50">
      <div class="grid-item col-span-1 row-span-12 bg-orange-500">1</div>
      <div class="grid-item col-span-1 row-span-12 bg-orange-500">2</div>
      <div class="grid-item col-span-1 row-span-12 bg-orange-500">3</div>
      <div class="grid-item col-span-1 row-span-12 bg-orange-500">4</div>
      <div class="grid-item col-span-1 row-span-12 bg-orange-500">5</div>
      <div class="grid-item col-span-1 row-span-12 bg-orange-500">6</div>
      <div class="grid-item col-span-1 row-span-12 bg-orange-500">7</div>
      <div class="grid-item col-span-1 row-span-12 bg-orange-500">8</div>
      <div class="grid-item col-span-1 row-span-12 bg-orange-500">9</div>
      <div class="grid-item col-span-1 row-span-12 bg-orange-500">10</div>
      <div class="grid-item col-span-1 row-span-12 bg-orange-500">11</div>
      <div class="grid-item col-span-1 row-span-12 bg-orange-500">12</div>
      <div class="grid-item col-span-1 row-span-12 bg-orange-500">13</div>
      <div class="grid-item col-span-1 row-span-12 bg-orange-500">14</div>
      <div class="grid-item col-span-1 row-span-12 bg-orange-500">15</div>
      <div class="grid-item col-span-1 row-span-12 bg-orange-500">16</div>
    </div> -->
    <router-view/>
    <ContainerHeader/>
    <TransitionOpacity durationIn="duration-300" durationOut="duration-200">
      <OverlayConsentCookies v-if="!isConsentCookiesAllowed" v-model="isConsentCookiesAllowed" />
    </TransitionOpacity>
    
  </template>

<script setup>
  import { useRoute } from 'vue-router';
  import { ref, defineAsyncComponent, watch } from 'vue';
  import { getLStorageCookieConsent } from "@/composable/useLocalStorage";
  import ContainerHeader from '@/component/container/ContainerHeader.vue';
  import TransitionOpacity from './component/transition/TransitionOpacity.vue';
  const OverlayConsentCookies = defineAsyncComponent(() => import('@/component/overlay/OverlayConsentCookies.vue'));
  // variables, props ...
  const isConsentCookiesAllowed = ref(true);
  const route = useRoute();

  watch(route, () => {
    const dataCookie = getLStorageCookieConsent();
    console.log(dataCookie);
    if(dataCookie) {
        const isValidConsent = dataCookie.consent;
        console.log('datecookie',dataCookie);
        if(!isValidConsent) isConsentCookiesAllowed.value = false;
    }
    else isConsentCookiesAllowed.value = false;
}, { immediate: true });
</script>

<style>
  .grid-container {
    display: grid;
    grid-template-columns: repeat(16, minmax(100px, 1fr));
  }
  .grid-item {
    border: 1px solid #ffffff; 
  }
</style>


<!-- <transition
        enter-active-class="transition-opacity duration-500"
        enter-class="opacity-0"
        enter-to-class="opacity-100"
        leave-active-class="transition-opacity duration-500"
        leave-class="opacity-100"
        leave-to-class="opacity-0"
      >
        <router-view></router-view>
      </transition> -->


      <!-- <transition
  mode="out-in"
  @after-leave="handleAfterLeave"
  @after-enter="handleAfterEnter"
  ...>
  <router-view></router-view>
</transition> -->