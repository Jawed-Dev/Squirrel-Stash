<template>
    <div v-show="isMobile" class="h-20 w-full fixed bottom-[50px]">
        <OverlayMenuHeaderUser class="absolute right-[190px] bottom-2" v-model="isMenuUserActive" />
    </div>
    <nav v-show="isValidPage"
        ref="headerRef" 
        :class="`font-main-font flex md:flex-col items-center
        w-full md:w-header-width md:h-[calc(100vh-(40px))] h-[50px]
        md:top-top-Header bottom-0 md:left-top-Header fixed 
        bg-header-gradient md:rounded-md 
        shadow-black shadow-custom-main
        ${extendHeader} z-10 md:max-h-[calc(100vh-(40px))] max-h-none overflow-y-auto`"
            @mouseenter="isHovered = true"
            @mouseleave="isHovered = false"
        >
        
        <div v-show="!isMobile" class="w-[80%] border-[1px] mt-20"></div>

        

        <div class="md:mt-3 w-full justify-center flex md:flex-col">
            <div 
                v-for="(icon, index) of dataIcons" 
                class="md:w-full items-center justify-center w-[100px] md:items-stretch h-[50px] md:h-fit flex flex-col relative" 
            >
                
                <div 
                    :key="index" @click="handleClickHeader(icon.page)" 
                    :class="`flex relative ${classTranslateY} py-[30px] md:py-2 cursor-pointer ${bordergetCurrentPage(icon.page)}`"
                >
                    
                    <div class="flex flex-col items-center relative" @click="openMenuUser(icon.page)">
                        <div v-if="icon.page === 'utilisateur' && isMobile" class="text-white absolute bottom-7">
                            <p>&#9650;</p>
                  
                        </div>
                        <component :is="icon.Component" :svg="svgConfig(icon.page)" class="w-header-width"/>
                    </div>
                    
                    <TransitionOpacity v-if="!isMobile" :durationIn="'duration-300'" :durationOut="'duration-0'">
                        <router-link :to="icon.link" v-show="isHovered && isTextIconsVisible" 
                        class="absolute w-[150px] right-[0px] top-[50%] transform -translate-y-1/2 pl-3 flex 
                        items-center text-[14px] text-white">{{ icon.text }}</router-link>
                    </TransitionOpacity>
                </div>
            </div>
        </div>

        <div v-show="!isMobile" class="w-[80%] border-[1px] mt-3"></div>

        <div v-show="!isMobile" class="w-full flex md:flex-col mt-5">
            <div class="w-full flex flex-col relative" v-for="(icon, index) of dateIcons2">
                <div 
                    :key="index" @click="handleClickHeader(icon.page)" 
                    :class="`flex relative ${classTranslateY} py-2 cursor-pointer ${bordergetCurrentPage(icon.page)}`">
                    <component :is="icon.Component" :svg="svgConfig(icon.page)" class="w-header-width "/>

                    <TransitionOpacity v-if="!isMobile" :durationIn="'duration-300'" :durationOut="'duration-0'">
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
    import { useRouter } from 'vue-router';
    import { storeAuthTOken } from '@/storePinia/useStoreDashboard';
    import {ref, onMounted, onUnmounted, computed, defineAsyncComponent, reactive} from 'vue';
    import TransitionOpacity from '@/component/transition/TransitionOpacity.vue';  
    import { classTransitionHover } from '@/composable/useClassTransitionHover';
    import { disconnectUser } from '@/composable/useBackendActionData';
    import OverlayMenuHeaderUser from '@/component/overlay/OverlayMenuHeaderUser.vue';
    
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

    
    //import OverlayPrivacy from '@/component/overlay/OverlayPrivacy.vue';
    //import OverlayUserRules from '@/component/overlay/OverlayUserRules.vue';
    // variables, props, ...

    // data icons
    const dataIcons = [
    { Component: IconDashboard, link:'/tableau-de-bord', page: 'tableau-de-bord', text: 'Tableau de bord' },
    { Component: IconPurchases, link:'/historique-transactions',page: 'historique-transactions', text: 'Historique' },
    { Component: IconGraph, link:'',page: '', text: 'Graphiques' },
    { Component: IconUser, link:'/utilisateur', page: 'utilisateur', text: 'Utilisateur' },
    ];

    const dateIcons2 = [    
    // { Component: IconPrivacy, link:'',page: 'confidentialité', text: 'Confidentialité' },
    // { Component: IconUserRules, link:'',page: 'règles', text: "Règles d'utilisateur" },
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
    
    const router = useRouter();
    const width = ref(window.innerWidth);
    const headerRef = ref(null);
    const isMenuUserActive = ref(false);

    const notAllowedPages = [
        'connexion',
        'inscription',
        'mot-de-passe-oublie',
        'reinitialiser-mot-de-passe',
    ];

        
    // life cycle, functions ...
    const isMobile = computed(() => width.value < 768);

    const extendHeader = computed(() => {
        return (!isMobile.value) ? classTranslateWidth : '';
    });
    
    let observer;
    onMounted( () => {
        const observer = new ResizeObserver( () => updateTextVisibility());
        if (headerRef.value) observer.observe(headerRef.value);
        window.addEventListener('resize', handleResize);
    });

    onUnmounted(() => {
        if (observer) observer.disconnect();
        window.removeEventListener('resize', handleResize);
    });

    const isValidPage = computed(() => {
        const currentPath = router.currentRoute.value.path.substring(1);
        return !notAllowedPages.includes(currentPath) && currentPath;
    });

    const bordergetCurrentPage = computed(() => {
        return (page) => {
            return  page === router.currentRoute.value.path.substring(1) ? 'bg-[#121422A0]' : '';
        }
    });

    function handleResize() {
        width.value = window.innerWidth;
    }
    const updateTextVisibility = () => {
        if(headerRef.value) isTextIconsVisible.value = headerRef.value.clientWidth > 160;
    };


    function svgConfig() {
        const sizeSvg = '30px';
        return {
            width: sizeSvg,
            height: sizeSvg,
            fill: 'white',
        }
    }

    function openMenuUser(page) {
        if(isMobile.value && page === 'utilisateur') {
            isMenuUserActive.value = true;//!isMenuUserActive.value;
            alert('test');
        }
    }

    async function handleClickHeader(page) {
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
                if(!isMobile.value) router.push('/utilisateur');
                break;
            }
            // case 'confidentialité' : {
            //     isOverlayActive.privacy = true;
            //     break;
            // }
            // case 'règles' : {
            //     isOverlayActive.userRules = true;
            //     break;
            // }
            case 'contact' : {
                isOverlayActive.contactUs = true;
                break;
            }
            case 'disconnect' : {
                const authToken = storeAuthTOken();
                authToken.token = '';
                await disconnectUser();
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


