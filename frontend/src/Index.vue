  <template>
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