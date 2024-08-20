  <template>
    <router-view />
    <ContainerHeader/>
    <!-- cookie overlay -->
    <TransitionOpacity durationIn="duration-300" durationOut="duration-200">
      <OverlayConsentCookies v-if="isConsentCookiesAllowed" v-model="isConsentCookiesAllowed" />
    </TransitionOpacity>    

   
  </template>

<script setup>
  import { useRoute } from 'vue-router';
  import { ref, defineAsyncComponent, watch, onMounted } from 'vue';
  import { getLStorageCookieConsent } from "@/composable/useLocalStorage";
  import ContainerHeader from '@/component/container/ContainerHeader.vue';
  import TransitionOpacity from '@/component/transition/TransitionOpacity.vue';
  const OverlayConsentCookies = defineAsyncComponent(() => import('@/component/overlay/OverlayConsentCookies.vue'));
  
  // variables, props ...
  const route = useRoute();
  const isConsentCookiesAllowed = ref(false);

  watch(() => route.path, () => {
    const dataCookie = getLStorageCookieConsent();
    if(!dataCookie || !dataCookie.consent) isConsentCookiesAllowed.value = true;
  }, { immediate: true });

</script>
