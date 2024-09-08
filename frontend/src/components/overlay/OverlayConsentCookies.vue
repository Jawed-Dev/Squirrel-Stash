<template>
    <div class="fixed inset-0 bg-black bg-opacity-90 z-20 ">
        <div :class="`fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-20 text-white rounded-[3px]
                 shadow-main bg-main-gradient max-[850px]:w-full min-[850px]:min-w-[850px] font-main`">
    
            <ContainerSlotOverlay :hideCross="true" :bgMainBtn="'bg-gradient-blue'" width='w-full'
            :textBtn1="'Refuser'" :textBtn2="'Accepter'"  titleContainer="Cookies" @toggleMenu="toggleMenu">

            <div class="max-h-[calc(80vh-36px)] overflow-y-auto">
                <div class="px-5 sm:px-10 md:px-12 w-full flex justify-center">
    
                    <div class="max-h-[500px] 
                                flex flex-col w-full rounded-[3px] py-5 mt-10">
                        <div class="flex flex-col items-center gap-5">
                            <h2 class="text-custom-blue text-xl">Politique de Cookies</h2>
                        </div>
            
        
                        <div class="flex flex-col gap-5 w-full mt-14">
                            <p class="opacity-90 font-light">
                                Nous utilisons des cookies strictement nécessaires pour assurer le bon fonctionnement de notre application. <span class="block"></span>
                                Ces cookies sont essentiels pour le bon fonctionnement de l'application. <span class="block"></span>
                                Nous ne collectons ni n'utilisons de cookies à des fins publicitaires.
                            </p>
                        </div>
        
                        <div class="flex flex-col gap-5 w-full mt-8 mb-14">
                            <p class="opacity-90 font-light">
                                En utilisant notre service, vous acceptez l'emploi de ces cookies essentiels pour une expérience et une sécurité optimales. <span class="block"></span>
                                Pour plus d'informations sur la gestion de vos données, veuillez consulter notre 
                                <span :onclick="openPolicyOverlay" class="hover:text-blue-300 text-main-blue font-normal cursor-pointer">Politique de confidentialité</span>.
                            </p>
                        </div>
        
                    </div>
                </div>
            </div>
            </ContainerSlotOverlay>
        </div>
        <OverlayPrivacy v-if="OverlayPrivacyActive" v-model="OverlayPrivacyActive" />
    </div>
</template>

<script setup>
    import { ref, defineAsyncComponent } from 'vue';
    import { useRouter } from 'vue-router';
    import ContainerSlotOverlay from '@/components/containerSlot/ContainerSlotOverlay.vue';
    import { setLStorageCookieConsent, getLStorageAuthToken} from "@/composables/useLocalStorage";
    import { storeAuthTOken } from '@/storesPinia/useStoreDashboard';
    const OverlayPrivacy = defineAsyncComponent(() => import('@/components/overlay/OverlayPrivacy.vue'));
    
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
                    const authToken = storeAuthTOken();
                    authToken.token = '';
                }
                closeOverlay();
                router.push('/connexion');
                isOverlayActive.value = true;
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
        isOverlayActive.value = false;
    }

    function openPolicyOverlay() {
        OverlayPrivacyActive.value = true;
    }

</script>