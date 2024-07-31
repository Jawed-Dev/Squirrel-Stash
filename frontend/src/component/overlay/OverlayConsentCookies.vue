<template>
    <div class="fixed inset-0 bg-black bg-opacity-90 z-20 ">
        <div :class="`fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-20 text-white rounded-[3px]
                shadow-black shadow-custom-main bg-main-gradient
                max-[850px]:w-full min-[850px]:min-w-[850px] font-main-font`">
    
            <MainContainerSlot :hideCross="true" :bgMainBtn="'bg-gradient-blue'" width='w-full'
            :textBtn1="'Refuser'" :textBtn2="'Accepter'"  titleContainer="Cookies" @toggleMenu="toggleMenu">

            <div class="max-h-[75vh] overflow-y-auto">
                <div class="px-5 md:px-12 w-full flex justify-center">
    
                    <div class="max-h-[500px] overflow-y-auto
                                flex flex-col w-full rounded-[3px] py-5 gap-20">
        
                        <div class="flex flex-col items-center gap-5">
                            <h2 class="text-xl">Politique de Cookies</h2>
                        </div>
            
        
                        <div class="flex flex-col gap-5 w-full">
                            <p class="opacity-90 font-light">
                                Nous utilisons des cookies strictement nécessaires pour assurer le bon fonctionnement de notre application. <span class="block"></span>
                                Ces cookies sont essentiels pour vous authentifier et maintenir votre session active. <span class="block"></span>
                                Nous ne collectons ni n'utilisons de cookies à des fins publicitaires.
                            </p>
                        </div>
        
                        <div class="flex flex-col gap-5 w-full">
                            <p class="opacity-90 font-light">
                                En utilisant notre service, vous acceptez l'emploi de ces cookies essentiels pour une expérience et une sécurité optimales. <span class="block"></span>
                                Pour plus d'informations sur la gestion de vos données, veuillez consulter notre 
                                <span :onclick="openPolicyOverlay" class="text-violet-500 font-normal cursor-pointer">Politique de confidentialité</span>.
                            </p>
                        </div>
        
                    </div>
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