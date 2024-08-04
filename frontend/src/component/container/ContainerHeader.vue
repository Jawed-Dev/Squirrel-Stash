<template>
    <nav v-show="isValidPage"
        ref="headerRef" 
        :class="`font-main-font flex md:flex-col items-center
        w-full md:w-header-width md:h-[calc(100vh-(40px))] h-[50px]
        md:top-top-Header bottom-0 md:left-[20px] xl:left-[30px] fixed 
        bg-header-gradient md:rounded-md 
        shadow-black shadow-custom-main
        ${extendHeader} z-10 md:max-h-[calc(100vh-(40px))] max-h-none md:overflow-y-auto`"
            @mouseenter="isHovered = true"
            @mouseleave="isHovered = false"
        >
        
        <div 
            v-show="!isMobile" 
            class="flex relative w-full h-[50px] md:h-fit py-2">
                <div class="flex w-[60px] justify-center ">
                    <LogoMainPicOnly class="bg-main-gradient shadow-black shadow-custom-main rounded-full" :svg="logoStyle" />
                </div>
                <div>
                    <TransitionOpacity v-if="!isMobile" :durationIn="'duration-300'" :durationOut="'duration-0'">
                        <p 
                            v-if="isHovered && isTextIconsVisible"
                            class="flex absolute w-[140px] right-[0px] top-[50%] transform -translate-y-1/2 
                            pl-3 text-white font-light" 
                        >
                        Squirrel Stash</p>
                    </TransitionOpacity>
                </div>
        
        </div>


        <div v-show="!isMobile" class="w-[80%] border-[1px] mt-1"></div>

        <div class="md:mt-3 w-full justify-center flex md:flex-col overflow-hidden">
            <div 
                v-for="(icon, index) of dataListPage1" 
                class="flex flex-col relative items-center justify-center w-[100px] md:w-full md:items-stretch h-[50px] md:h-fit" 
            >
                
                <div :key="index" @click="handleClickHeader(icon.page)" 
                    :class="`flex relative ${classTranslateY} md:py-2 cursor-pointer py-4
                    ${borderCurrentPage(icon.page)}`">
                    
                        <div class="flex flex-col items-center relative">
                            <component :is="icon.Component" :svg="iconsStyle" class="w-header-width"/>
                        </div>
                        
                        <TransitionOpacity v-if="!isMobile" :durationIn="'duration-300'" :durationOut="'duration-0'">
                            <router-link 
                                :to="icon.link" v-show="isHovered && isTextIconsVisible" 
                                class="flex items-center absolute w-[150px] right-[0px] top-[50%] transform -translate-y-1/2 
                                pl-3 text-[14px] text-white font-light">{{ icon.text }}
                            </router-link>
                        </TransitionOpacity>
                </div>
            </div>
        </div>

        <div v-show="!isMobile" class="w-[80%] border-[1px] mt-3"></div>

        <div v-show="!isMobile" class="w-full flex md:flex-col mt-3">
            <div class="w-full flex flex-col relative" v-for="(icon, index) of dataListPage2">
                <div 
                    :key="index" @click="handleClickHeader(icon.page)" 
                    :class="`flex relative ${classTranslateY} py-2 cursor-pointer ${borderCurrentPage(icon.page)}`">
                    <component :is="icon.Component" :svg="iconsStyle" class="w-header-width "/>

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
        <OverlayPrivacy v-if="isOverlayActive.privacy" v-model="isOverlayActive.privacy"/>
        <OverlayUserRules v-if="isOverlayActive.userRules" v-model="isOverlayActive.userRules"/>
        <OverlayContactUs v-if="isOverlayActive.contactUs" v-model="isOverlayActive.contactUs"/>
    </TransitionOpacity>        
</template>


<script setup>
    import {ref, onMounted, onUnmounted, computed, defineAsyncComponent, reactive} from 'vue';
    import { useRouter } from 'vue-router';
    import { storeAuthTOken } from '@/storePinia/useStoreDashboard';
    import TransitionOpacity from '@/component/transition/TransitionOpacity.vue';  
    import { classTransitionHover } from '@/composable/useClassTransitionHover';
    import { disconnectUser } from '@/composable/useBackendActionData';
    import { setSvgConfig } from '@/svg/svgConfig';
    import LogoMain from '@/component/svgs/LogoMain.vue';
    import LogoMainPicOnly from '@/component/svgs/LogoMainPicOnly.vue';
    
    
    // import async icons & dynamic
    const IconDashboard = defineAsyncComponent(() => import('@/component/svgs/IconDashboard.vue'));
    const IconPurchases = defineAsyncComponent(() => import('@/component/svgs/IconPurchase.vue'));
    const IconGraph = defineAsyncComponent(() => import('@/component/svgs/IconGraph.vue'));
    const IconUser = defineAsyncComponent(() => import('@/component/svgs/IconUser.vue'));
    const IconLogOut = defineAsyncComponent(() => import('@/component/svgs/IconLogOut.vue'));
    const IconSupport = defineAsyncComponent(() => import('@/component/svgs/IconSupport.vue'));
    const IconInfo = defineAsyncComponent(() => import('@/component/svgs/IconInfo.vue'));

    const OverlayPrivacy = defineAsyncComponent(() => import('@/component/overlay/OverlayPrivacy.vue'));
    const OverlayUserRules = defineAsyncComponent(() => import('@/component/overlay/OverlayUserRules.vue'));
    const OverlayContactUs = defineAsyncComponent(() => import('@/component/overlay/OverlayContactUs.vue'));

    // variables, props, ...
    const dataListPage1 = [
        { Component: IconDashboard, link:'/tableau-de-bord', page: 'tableau-de-bord', text:'Tableau de bord'},
        { Component: IconGraph, link:'/recap-annuel',page:'recap-annuel',text: 'Recap Annuel' },
        { Component: IconPurchases, link:'/historique-transactions',page:'historique-transactions', text:'Historique'},
        { Component: IconUser, link:'/utilisateur', page: 'utilisateur', text: 'Utilisateur' },
    ];

    const dataListPage2 = [    
    { Component: IconSupport, link:'',page: 'contact', text: 'Support et aide' },
    { Component: IconInfo, link:'',page: '', text: 'Version 1.0' },
    { Component: IconLogOut, link:'',page: 'disconnect', text: 'Déconnexion' },
    ];

    const iconsStyle = setSvgConfig({width:'25px', fill:'white'});
    const logoStyle = setSvgConfig({width:'45px', fill:'white'});

    const classTranslateY = classTransitionHover('translateY');
    const classTranslateWidth = classTransitionHover('extendHeader');
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

    const currentLogo = computed(() => {
        return (isTextIconsVisible.value) ? LogoMain : LogoMainPicOnly;
    });

    const isValidPage = computed(() => {
        const currentPath = router.currentRoute.value.path.substring(1);
        return !notAllowedPages.includes(currentPath) && currentPath;
    });

    const borderCurrentPage = computed(() => {
        return (page) => {
            return  page === router.currentRoute.value.path.substring(1) ? 'bg-main-gradient shadow-black shadow-custom-main' : '';
        }
    });

    const handleResize = ()  => { width.value = window.innerWidth; }
    const updateTextVisibility = () => {
        if(headerRef.value) isTextIconsVisible.value = headerRef.value.clientWidth > 160;
    };

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
            case 'recap-annuel' : {
                router.push('/recap-annuel');
                break;
            }
            case 'utilisateur' : {
                router.push('/utilisateur');
                break;
            }
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


