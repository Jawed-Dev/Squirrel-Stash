<template>
    <div class="font-main flex flex-col bg-main-bg w-full min-h-screen pb-50px">

        <div class="mx-1 flex flex-col my-5
            md:ml-[calc(20px+65px+20px)] xl:ml-[calc(30px+75px+30px)] 
            md:mr-[20px] xl:mr-[30px]">
            
            <h1 class="text-2xl font-light text-white text-center">Espace utilisateur</h1>

            <div class="rounded-[3px] overflow-hidden mt-5">
                <div :class="`text-lg overflow-hidden text-white`">
                    <div class="flex justify-center">
                        <div class="flex gap-6 md:gap-8 justify-center flex-wrap my-3 w-[70%]">
                            <div v-for="(page, index) of dataPages"
                                :key="index"
                                @click="handlePages(page.page)"
                                class="flex flex-col items-center justify-center gap-1 w-[calc(33.333333%-20px)] 
                                min-w-[200px]
                                md:hover:shadow-custom-lower
                                 lg:cursor-pointer 
                                 bg-main-gradient rounded-md overflow-hidden shadow-main">
                                 
                                <div class="p-2">
                                    <component :is="page.Component" :svg="styleIcons"/>
                                </div>
                                <p class="text-center text-sm bg-gradient-x-blue opacity-90 w-full py-[1px]">{{page.text}}</p>
                            </div>
                        </div>
                    </div> 
                </div>
            </div>
        </div>
        <TransitionOpacity durationIn="duration-300" durationOut="duration-200" >
            <OverlayPrivacy v-if="isOverlayActive.privacy" v-model="isOverlayActive.privacy"/>
            <OverlayUserRules v-if="isOverlayActive.userRules" v-model="isOverlayActive.userRules"/>
            <OverlayContactUs v-if="isOverlayActive.contactUs" v-model="isOverlayActive.contactUs"/>
        </TransitionOpacity>       
    </div>
</template>


<script setup>
    import { defineAsyncComponent, reactive } from 'vue';
    import { useRouter } from 'vue-router';
    import { storeAuthTOken } from '@/storesPinia/useStoreDashboard';
    //import { disconnectUser } from '@/composables/useBackendActionData';
    import { setSvgConfig } from '@/svgUtils/svgConfig';
    import TransitionOpacity from '@/components/transition/TransitionOpacity.vue';

    const IconUser = defineAsyncComponent(() => import('@/components/svg/IconUser.vue'));
    const IconPrivacy = defineAsyncComponent(() => import('@/components/svg/IconPrivacy.vue'));
    const IconUserRules = defineAsyncComponent(() => import('@/components/svg/IconUserRules.vue'));
    const IconSupport = defineAsyncComponent(() => import('@/components/svg/IconSupport.vue'));
    const IconLogOut = defineAsyncComponent(() => import('@/components/svg/IconLogOut.vue'));
    const IconInfo = defineAsyncComponent(() => import('@/components/svg/IconInfo.vue'));
    const OverlayPrivacy = defineAsyncComponent(() => import('@/components/overlay/OverlayPrivacy.vue'));
    const OverlayUserRules = defineAsyncComponent(() => import('@/components/overlay/OverlayUserRules.vue'));
    const OverlayContactUs = defineAsyncComponent(() => import('@/components/overlay/OverlayContactUs.vue'));
    
    const router = useRouter();
    const styleIcons = setSvgConfig({width:'30px', fill:'white'});
    const isOverlayActive = reactive({
        privacy: false,
        userRules: false,
        contactUs: false
    });

    const dataPages = [
        { Component: IconUser, link:'/mon-compte', page: 'mon-compte', text: 'Mon compte' },
        { Component: IconSupport, link:'', page: 'support et aide', text: 'Support et aide' },
        { Component: IconPrivacy, link:'',page: 'confidentialité', text: 'Confidentialité' },
        { Component: IconUserRules, link:'',page: "règles d'utilisation", text: "Règles d'utilisation" },
        { Component: IconInfo, link:'',page: "", text: "Version 1.0" },
        { Component: IconLogOut, link:'', page: 'déconnexion', text: 'Déconnexion' },
    ];

    async function handlePages(page) {
        switch(page) {
            case 'mon-compte' : {
                router.push('/mon-compte');
                break;
            }
            case 'support et aide' : {
                isOverlayActive.contactUs = true;
                break;
            }
            case 'confidentialité' : {
                isOverlayActive.privacy = true;
                break;
            }
            case "règles d'utilisation" : {
                isOverlayActive.userRules = true;
                break;
            }
            case 'déconnexion' : {
                const authToken = storeAuthTOken();
                authToken.token = '';
                localStorage.removeItem('authToken');
                router.push('/connexion');
                break;
            }
        }
    }
</script>