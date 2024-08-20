<template>
    <nav v-show="isValidPage"
        ref="headerRef" 
        :class="`font-main flex md:flex-col items-center bg-header-gradient shadow-main z-10 
        w-full md:w-header-tablet-width xl:w-header-width md:h-[calc(100vh-(40px))] h-[50px]
        md:top-top-Header bottom-0 md:left-[20px] xl:left-[30px] fixed md:rounded-md 
        ${extendHeader} md:max-h-[calc(100vh-(40px))] max-h-none md:overflow-y-auto`"
            @mouseenter="isHoverHeader = true"
            @mouseleave="isHoverHeader = false"
        >
        
        <div v-show="!isMobile" class="flex relative w-full h-[50px] md:h-fit py-2">
            <router-link to="/tableau-de-bord" class="flex md:w-header-tablet-width xl:w-header-width justify-center p-[8px] xl:p-[12px]">
                <LogoMainPicOnly class=" bg-main-gradient shadow-custom-hover rounded-xl" :svg="logoStyle" />
            </router-link>
            <div>
                <TransitionOpacity v-if="!isMobile" :durationIn="'duration-300'" :durationOut="'duration-0'">
                    <router-link  
                        to="/tableau-de-bord"
                        v-if="isHoverHeader && isTextIconsVisible"
                        class="absolute flex text w-[140px] 
                        right-[0px] top-[50%] -translate-y-1/2 pl-3 lg:pl-4 text-white cursor-pointer" 
                    >
                    Squirrel Stash</router-link>
                </TransitionOpacity>
            </div>
        </div>


        <div v-show="!isMobile" class="w-[80%] border-[1px] mt-1"></div>

        <div class="md:mt-3 w-full justify-center flex md:flex-col overflow-hidden">
            <div 
                v-for="(icon, index) of dataListPage1" 
                class="flex flex-col relative items-center justify-center w-[100px] md:w-full md:items-stretch h-[50px] md:h-fit" 
            >
                
                <div :key="index" @click="handleClickHeader(icon)" @mouseenter="blablabla(icon)" @mouseleave ="blablabla2(icon)"
                    :class="`flex relative ${classTranslateY} md:py-2 cursor-pointer py-4 
                    ${borderCurrentPage(icon.page)}`">
                    
                        <div class="flex flex-col items-center relative">
                            <component :is="icon.Component" :svg="styleIcon" 
                            :class="`w-header-width md:w-header-tablet-width xl:w-header-width ${changeColorHoverIcon(icon)}`"/> 
                        </div>
                        
                        <TransitionOpacity v-if="!isMobile" :durationIn="'duration-300'" :durationOut="'duration-0'">
                            <router-link 
                                :to="icon.link" v-show="isHoverHeader && isTextIconsVisible" 
                                class="flex items-center absolute w-[150px] right-[0px] top-[50%] transform -translate-y-1/2 
                                pl-3 text-[14px] text-white font-light">{{ icon.text }}
                            </router-link>
                        </TransitionOpacity>
                </div>
            </div>
        </div>

        <div v-show="!isMobile" class="w-[80%] border-[1px] mt-3"></div>

        <div v-show="!isMobile" class="w-full flex md:flex-col mt-3">
            <div class="w-full flex flex-col relative" v-for="(icon, index) of dataListPage2" >
                <div 
                    :key="index" @click="handleClickHeader(icon)" @mouseenter="blablabla(icon)" @mouseleave ="blablabla2(icon)"
                    :class="`flex relative ${classTranslateY} py-2 cursor-pointer 
                    ${borderCurrentPage(icon.page)}`"
                >
                    <component :is="icon.Component" :svg="styleIcon" :class="`md:w-header-tablet-width xl:w-header-width 
                    ${changeColorHoverIcon(icon)}`"/>

                    <TransitionOpacity v-if="!isMobile" :durationIn="'duration-300'" :durationOut="'duration-0'">
                        <router-link 
                            :to="icon.link" v-show="isHoverHeader && isTextIconsVisible" 
                            class="absolute w-[150px] right-[0px] top-[50%] transform -translate-y-1/2 pl-3 flex 
                            items-center text-[14px] text-white">{{ icon.text }}
                        </router-link>
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
    import {ref, onMounted, onUnmounted, computed, defineAsyncComponent, reactive, shallowRef} from 'vue';
    import { useRouter } from 'vue-router';
    import { storeAuthTOken } from '@/storePinia/useStoreDashboard';
    import TransitionOpacity from '@/component/transition/TransitionOpacity.vue';  
    import { classTransitionHover } from '@/composable/useClassTransitionHover';
    import { setSvgConfig } from '@/svg/svgConfig';
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
        { Component: IconDashboard, link:'/tableau-de-bord', page: 'tableau-de-bord', text:'Tableau de bord', isHover: ref(false)},
        { Component: IconGraph, link:'/recap-annuel',page:'recap-annuel',text: 'Recap Annuel', isHover: ref(false)},
        { Component: IconPurchases, link:'/historique-transactions',page:'historique-transactions', text:'Historique', isHover: ref(false)},
        { Component: IconUser, link:'/utilisateur', page: 'utilisateur', text: 'Utilisateur', isHover: ref(false)},
    ];

    const dataListPage2 = [    
        { Component: IconSupport, link:'',page: 'contact', text: 'Support et aide', isHover: ref(false)},
        { Component: IconInfo, link:'',page: 'info', text: 'Version 1.0', isHover: ref(false)},
        { Component: IconLogOut, link:'',page: 'disconnect', text: 'Déconnexion', isHover: ref(false)},
    ];

    const styleIcon = setSvgConfig({width:'25px', fill:'white'});
    const logoStyle = setSvgConfig({fill:'white'});

    const classTranslateY = classTransitionHover('translateY');
    const classTranslateWidth = classTransitionHover('extendHeader');
    const isHoverHeader = ref(false);
    const isTextIconsVisible = ref(false);
    const isOverlayActive = reactive({
        privacy: false,
        userRules: false,
        contactUs: false
    });

    const router = useRouter();
    const width = ref(window.innerWidth);
    const headerRef = ref(null);     
    const isDivIconHover = ref(false);

    const notAllowedPages = [
        'connexion',
        'inscription',
        'mot-de-passe-oublie',
        'reinitialiser-mot-de-passe',
        '404',
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

    const borderCurrentPage = computed(() => {
        return (page) => {
            const currentPage = router.currentRoute.value.path.substring(1);
            if(currentPage === 'mon-compte') {
                if(page === 'utilisateur') return 'bg-main-gradient  shadow-main';
            }
            return page === currentPage ? 'bg-main-gradient' : '';
        }
    });


    const changeColorHoverIcon = computed(() => {
        return (icon) => {
            if(icon.isHover.value && borderCurrentPage.value(icon.page)) return '';
            return icon.isHover.value && !borderCurrentPage.value(icon.page) ? 'stroke-black' : 'stroke-red';
        }
    });

    const handleResize = ()  => { width.value = window.innerWidth; }
    const updateTextVisibility = () => {
        if(headerRef.value) isTextIconsVisible.value = headerRef.value.clientWidth > 160;
    };

    async function handleClickHeader(data) {
        switch (data.page) {
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
            case 'info' : {
                break;
            }
            case 'disconnect' : {
                const authToken = storeAuthTOken();
                authToken.token = '';
                localStorage.removeItem('authToken');
                router.push('/connexion');
                break;
            }
            default: {
                document.title = 'Page introuvable';
                router.push('/404');
                break;
            }
        }
    }    

    function blablabla(icon) {
        icon.isHover.value = true;
    }

    function blablabla2(icon) {
        icon.isHover.value = false;
    }
</script>


