<template>
    <div class="fixed inset-0 bg-black bg-opacity-90 z-50 ">
        <div :class="`bg-main-gradient flex flex-col fixed 
        shadow-black shadow-custom-main rounded-md top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 trigger-overlay-privacy
        z-30 text-white ${width}`">
    
            <MainContainerSlot :hideCross="true" :bgMainBtn="'bg-gradient-blue'" width='w-full'
            :textBtn1="'Refuser'" :textBtn2="'Accepter'"  titleContainer="Cookies" @toggleMenu="toggleMenu">
                <div class="max-h-[65vh] overflow-y-auto text-xl 
                flex flex-col items-center w-full rounded-[3px] my-[40px] gap-20">

                <div class="flex flex-col items-center gap-5">
                    <h2 class="text-[25px]">Politique de Cookies</h2>
                </div>
    

                <div class="flex flex-col gap-5 w-[90%]">
                    <p class="opacity-90 font-light">
                        Nous utilisons des cookies strictement nécessaires pour assurer le bon fonctionnement de notre application. <span class="block"></span>
                        Ces cookies sont essentiels pour vous authentifier et maintenir votre session active. <span class="block"></span>
                        Nous ne collectons ni n'utilisons de cookies à des fins publicitaires.
                    </p>
                </div>

                <div class="flex flex-col gap-5 w-[90%]">
                    <p class="opacity-90 font-light">
                        En utilisant notre service, vous acceptez l'emploi de ces cookies essentiels pour une expérience et une sécurité optimales. <span class="block"></span>
                        Pour plus d'informations sur la gestion de vos données, veuillez consulter notre 
                        <span :onclick="openPolicyOverlay" class="text-violet-500 font-normal cursor-pointer">Politique de confidentialité</span>.
                    </p>
                </div>
  
                </div>
            </MainContainerSlot>

        </div>
        <OverlayPrivacy v-if="OverlayPrivacyActive" v-model="OverlayPrivacyActive" />
    </div>
</template>

<script setup>
    import { ref, defineAsyncComponent } from 'vue';
    import { useRouter } from 'vue-router';
    import MainContainerSlot from '@/component/containerSlot/MainContainerSlot.vue';
    import TransitionOpacity from '@/component/transition/TransitionOpacity.vue';
    import TransitionAxeY from '@/component/transition/TransitionAxeY.vue';
    import { setLStorageCookieConsent, getLStorageAuthToken} from "@/composable/useLocalStorage";
    const OverlayPrivacy = defineAsyncComponent(() => import('@/component/overlay/OverlayPrivacy.vue'));

    // props, variables..
    const props = defineProps({
        width: {default: ''}
    });
    const router = useRouter();
    const isOverlayActive = defineModel();
    const OverlayPrivacyActive = ref(false);

    // life cycle, functions
    async function toggleMenu(request) {
        switch(request) {
            case 'cancel': {
                const isTokenValid = getLStorageAuthToken();
                if(isTokenValid) {
                    localStorage.removeItem('authToken');
                }
                router.push('/connexion');
                break;
            }
            case 'valid': {
                setLStorageCookieConsent();
                closeOverlay();
                break;
            }
        }
    }

    function closeOverlay() {
        isOverlayActive.value = true;
    }

    function openPolicyOverlay() {
        OverlayPrivacyActive.value = true;
    }

</script>