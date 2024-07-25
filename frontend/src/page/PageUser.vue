<template>
    <div class="font-main-font flex flex-col bg-main-bg w-full min-h-screen pb-[calc(50px)]">

        <div class="mx-1 md:ml-[calc(20px+65px+20px)] md:mr-custom-margin-main flex flex-col mt-[20px]">
            <h1 class="text-[25px] font-light text-white text-center">Espace utilisateur</h1>

            <div class="rounded-[3px] overflow-hidden mt-5">
                <div :class="`text-[17px] overflow-hidden text-white`">
                    <div class="flex justify-center">
                        <div class="flex gap-5 flex-wrap my-3">
                            <div v-for="(page, index) of dataPages"
                                :key="index"
                                @click="handlePages(page.page)"
                                class="flex flex-col items-center justify-center gap-1 w-[calc(33.333333%-20px)] md:hover:shadow-main-blue md:hover:shadow-custom-main
                                md:w-full md:cursor-pointer shadow-black shadow-custom-main
                                 bg-main-gradient rounded-md p-2">
                                <component :is="page.Component" :svg="styleIcons"/>
                                <p class="text-sm">{{page.text}}</p>
                            </div>
                        </div>
                    </div> 
                </div>
            </div>
        </div>
        <TransitionOpacity durationIn="duration-300" durationOut="duration-200" >
            <OverlayPrivacy v-if="isOverlayActive.privacy" v-model="isOverlayActive.privacy" width="w-[50%]" />
            <OverlayUserRules v-if="isOverlayActive.userRules" v-model="isOverlayActive.userRules" width="w-[50%]" />
            <OverlayContactUs v-if="isOverlayActive.contactUs" v-model="isOverlayActive.contactUs" width="w-[50%]" />
        </TransitionOpacity>       
    </div>
</template>


<script setup>
    import { defineAsyncComponent, reactive } from 'vue';
    import { useRouter } from 'vue-router';
    import { storeAuthTOken } from '@/storePinia/useStoreDashboard';
    import { disconnectUser } from '@/composable/useBackendActionData';
    import { setSvgConfig } from '@/svg/svgConfig';
    import TransitionOpacity from '@/component/transition/TransitionOpacity.vue';

    const IconUser = defineAsyncComponent(() => import('@/component/svgs/IconUser.vue'));
    const IconPrivacy = defineAsyncComponent(() => import('@/component/svgs/IconPrivacy.vue'));
    const IconUserRules = defineAsyncComponent(() => import('@/component/svgs/IconUserRules.vue'));
    const IconSupport = defineAsyncComponent(() => import('@/component/svgs/IconSupport.vue'));
    const IconLogOut = defineAsyncComponent(() => import('@/component/svgs/IconLogOut.vue'));
    const IconInfo = defineAsyncComponent(() => import('@/component/svgs/IconInfo.vue'));
    const OverlayPrivacy = defineAsyncComponent(() => import('@/component/overlay/OverlayPrivacy.vue'));
    const OverlayUserRules = defineAsyncComponent(() => import('@/component/overlay/OverlayUserRules.vue'));
    const OverlayContactUs = defineAsyncComponent(() => import('@/component/overlay/OverlayContactUs.vue'));
    
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
        { Component: IconUserRules, link:'',page: "règles d'utilisateur", text: "Règles d'utilisateur" },
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
            case "règles d'utilisateur" : {
                isOverlayActive.userRules = true;
                break;
            }
            case 'déconnexion' : {
                const authToken = storeAuthTOken();
                authToken.token = '';
                await disconnectUser();
                router.push('/connexion');
                break;
            }
        }
    }
</script>