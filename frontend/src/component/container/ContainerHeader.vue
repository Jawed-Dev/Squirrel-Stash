<template>
    <nav v-show="isValidPage"
        ref="headerRef" 
        :class="`font-main-font flex flex-col items-center 
        w-header-width top-top-Header left-top-Header fixed pt-10 h-[calc(100vh-40px)] 
        bg-header-gradient rounded-md
        shadow-black shadow-custom-main
        ${classTranslateWidth} z-10`"
            @mouseenter="isHovered = true"
            @mouseleave="isHovered = false"
        >
        
        <div class="w-[70%] border-[1px] border-white mt-[40px]"></div>

        <div class="mt-2 w-full">
            <div class="w-full flex flex-col gap-5 relative" v-for="(icon, index) of dataIcons">
                <div :key="index" @click="handleClickHeader(icon.page)" 
                    :class="`flex relative ${classTranslateY} py-[6px] cursor-pointer ${bordergetCurrentPage(icon.page)}`">
                    <component :is="icon.Component" :svg="svgConfig(icon.page)" class="w-header-width "/>
                    <TransitionOpacity :durationIn="'duration-300'" :durationOut="'duration-0'">
                        <router-link :to="icon.link" v-show="isHovered && isTextIconsVisible" 
                        class="absolute w-[150px] right-[0px] top-[50%] transform -translate-y-1/2 pl-3 flex 
                        items-center text-[14px] text-white">{{ icon.text }}</router-link>
                    </TransitionOpacity>
                </div>
            </div>
        </div>
    </nav>

    <TransitionOpacity durationIn="duration-300" durationOut="duration-200" >
        <OverlayPrivacy v-if="isOverlayActive.privacy" v-model="isOverlayActive.privacy" width="w-[50%]" />
        <OverlayUserRules v-if="isOverlayActive.userRules" v-model="isOverlayActive.userRules" width="w-[50%]" />
        <OverlayContactUs v-if="isOverlayActive.contactUs" v-model="isOverlayActive.contactUs" width="w-[50%]" />
    </TransitionOpacity>        
</template>


<script setup>

    import {ref, onMounted, onUnmounted, computed, defineAsyncComponent, reactive} from 'vue';
    import { useRouter } from 'vue-router';

    // import async icons & dynamic
    const IconDashboard = defineAsyncComponent(() => import('@/component/svgs/IconDashboard.vue'));
    const IconPurchases = defineAsyncComponent(() => import('@/component/svgs/IconPurchase.vue'));
    const IconGraph = defineAsyncComponent(() => import('@/component/svgs/IconGraph.vue'));
    const IconUser = defineAsyncComponent(() => import('@/component/svgs/IconUser.vue'));
    const IconLogOut = defineAsyncComponent(() => import('@/component/svgs/IconLogOut.vue'));
    const IconPrivacy = defineAsyncComponent(() => import('@/component/svgs/IconPrivacy.vue'));
    const IconUserRules = defineAsyncComponent(() => import('@/component/svgs/IconUserRules.vue'));
    const IconSupport = defineAsyncComponent(() => import('@/component/svgs/IconSupport.vue'));
    const IconInfo = defineAsyncComponent(() => import('@/component/svgs/IconInfo.vue'));

    const OverlayPrivacy = defineAsyncComponent(() => import('@/component/overlay/OverlayPrivacy.vue'));
    const OverlayUserRules = defineAsyncComponent(() => import('@/component/overlay/OverlayUserRules.vue'));
    const OverlayContactUs = defineAsyncComponent(() => import('@/component/overlay/OverlayContactUs.vue'));
    import TransitionOpacity from '@/component/transition/TransitionOpacity.vue';  

    import { classTransitionHover } from '@/composable/useClassTransitionHover';
    //import OverlayPrivacy from '@/component/overlay/OverlayPrivacy.vue';
    //import OverlayUserRules from '@/component/overlay/OverlayUserRules.vue';


    // variables, props, ...

    // data icons
    const dataIcons = [
    { Component: IconDashboard, link:'/tableau-de-bord', page: 'tableau-de-bord', text: 'Tableau de bord' },
    { Component: IconPurchases, link:'/historique-transactions',page: 'historique-transactions', text: 'Historique' },
    { Component: IconUser, link:'/utilisateur', page: 'utilisateur', text: 'Utilisateur' },
    { Component: IconGraph, link:'',page: '', text: 'Graphiques' },
    
    { Component: IconPrivacy, link:'',page: 'confidentialité', text: 'Confidentialité' },
    { Component: IconUserRules, link:'',page: 'règles', text: "Règles d'utilisateur" },
    { Component: IconSupport, link:'',page: 'contact', text: 'Support et aide' },
    { Component: IconInfo, link:'',page: '', text: 'Version 1.0' },
    { Component: IconLogOut, link:'',page: 'disconnect', text: 'Déconnexion' },
    ];

    // class 
    const classTranslateY = classTransitionHover('translateY');
    const classTranslateWidth = classTransitionHover('extendHeader');

    // conditions / bool
    const isHovered = ref(false);
    const isTextIconsVisible = ref(false);
    const isOverlayActive = reactive({
        privacy: false,
        userRules: false,
        contactUs: false
    });

    //
    const router = useRouter();
    const headerRef = ref(null);

    // 
    const notAllowedPages = [
        'connexion',
        'inscription',
        'mot-de-passe-oublie',
        'reinitialiser-mot-de-passe',
    ];


    const updateTextVisibility = () => {
        if(headerRef.value) isTextIconsVisible.value = headerRef.value.clientWidth > 160;
    };

    // life cycle, functions ...
    let observer;
    onMounted( () => {
        const observer = new ResizeObserver( () => updateTextVisibility());
        if (headerRef.value) observer.observe(headerRef.value);
    });

    onUnmounted(() => {
        if (observer) observer.disconnect();
    });

    const isValidPage = computed(() => {
        const currentPath = router.currentRoute.value.path.substring(1);
        return !notAllowedPages.includes(currentPath) && currentPath;
    });

    const bordergetCurrentPage = computed(() => {
        return (page) => {
            return  page === router.currentRoute.value.path.substring(1) ? 'bg-second-bg' : '';
        }
    });

    function svgConfig() {
        const sizeSvg = '30px';
        return {
            width: sizeSvg,
            height: sizeSvg,
            fill: 'white',
        }
    }

    function handleClickHeader(page) {
        switch (page) {
            case 'tableau-de-bord': {
                router.push('/tableau-de-bord');
                break;
            }
            case 'historique-transactions' : {
                router.push('/historique-transactions');
                break;
            }
            case 'historique-transactions' : {
                router.push('/historique-transactions');
                break;
            }
            case 'utilisateur' : {
                router.push('/utilisateur');
                break;
            }
            case 'confidentialité' : {
                isOverlayActive.privacy = true;
                break;
            }
            case 'règles' : {
                isOverlayActive.userRules = true;
                break;
            }
            case 'contact' : {
                isOverlayActive.contactUs = true;
                break;
            }
            case 'disconnect' : {
                localStorage.removeItem('authToken');
                router.push('/connexion');
                break;
            }
            default: {
                router.push('/connexion');
                break;
            }
        }
    }    
</script>


