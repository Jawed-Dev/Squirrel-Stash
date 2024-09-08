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
  import { getLStorageCookieConsent } from "@/composables/useLocalStorage";
  import ContainerHeader from '@/components/header/ContainerHeader.vue';
  import TransitionOpacity from '@/components/transition/TransitionOpacity.vue';
  const OverlayConsentCookies = defineAsyncComponent(() => import('@/components/overlay/OverlayConsentCookies.vue'));
  
  // variables, props ...
  const route = useRoute();
  const isConsentCookiesAllowed = ref(false);

  watch(() => route.path, () => {
    const dataCookie = getLStorageCookieConsent();
    if(!dataCookie || !dataCookie.consent) isConsentCookiesAllowed.value = true;
  }, { immediate: true });

</script>
