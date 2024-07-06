<template>
    <div ref="mainRefHeader" :class="`
            flex flex-col items-center 
            w-header-width top-top-Header left-top-Header fixed pt-10  h-[calc(100vh-40px)] 
            bg-header-gradient rounded-md
            shadow-black shadow-custom-main
           ${classTranslateWidth} z-10`"
                @mouseenter="isHovered = true"
                @mouseleave="isHovered = false"
            >
            
            <div class="pl-5 w-[70%] border-[1px] border-white mt-[40px] "></div>
 
            <div class="w-[100%] flex flex-col gap-5 mt-[20px]" v-for="(icon, index) of listIcons">
                
                <div :key="index" @click="handleClickIcon(icon.page)" :class="`flex relative ${classTranslateY} cursor-pointer ${borderCurrentPage(icon.page)} pl-3 ml-1`">
                    <component :is="icon.Component" :svg="svgConfig(icon.page)"/>
                    <TransitionOpacity :durationIn="'duration-500'" :durationOut="'duration-0'">
                        <p v-if="isHovered && isTextIconsVisible" class="w-[150px] absolute right-[0px] top-1 pl-3 flex items-center text-[14px] text-white">{{ icon.text }}</p>
                    </TransitionOpacity>
                </div>
                
            </div>
    </div>
</template>


<script setup>
    import { useRouter } from 'vue-router';
    import {ref, onMounted, onUnmounted} from 'vue';
    // icons 
    import IconDashboard from '@/component/svgs/IconDashboard.vue';
    import IconPurchases from '@/component/svgs/IconPurchase.vue';
    import IconGraph from '@/component/svgs/IconGraph.vue';
    import IconBell from '@/component/svgs/IconBell.vue';
    import IconUser from '@/component/svgs/IconUser.vue';
    import { classTransitionHover } from '@/composable/useClassTransitionHover';
    import IconTarget from '@/component/svgs/IconTarget.vue';
    import IconLogOut from '@/component/svgs/IconLogOut.vue';
    import TransitionOpacity from '@/component/transition/TransitionOpacity.vue';
    

    // variables, props, ...

        // class 
        const classTranslateY = classTransitionHover('translateY');
        const classTranslateWidth = classTransitionHover('extendHeader');

        // conditions / bool
        const isHovered = ref(false);
        const isTextIconsVisible = ref(false);
        const isCurrentPage = ref(false);

        // 
        const statePage = ref(null);
        const router = useRouter();
        const mainRefHeader = ref(null);

        // icons
        const listIcons = [ 
            {
                Component: IconDashboard,
                page: 'home',
                text: 'Tableau de bord'
            },
            {
                Component: IconPurchases,
                page: 'pageTransactions',
                text: 'Historique'
            },
            {
                Component: IconGraph,
                page: '',
                text: 'Graphiques'
            },
            {
                Component: IconBell,
                page: '',
                text: 'Alarmes'
            },
            {
                Component: IconUser,
                page: '',
                text: 'Utilisateur'
            },
            {
                Component: IconLogOut,
                page: 'disconnect',
                text: 'Déconnexion'
            }
        ];

    const updateTextVisibility = () => {
        isTextIconsVisible.value = mainRefHeader.value.clientWidth > 150;
    };

    // --- Cycle de vie 
    onMounted( () => {
        const observer = new ResizeObserver(() => {
            updateTextVisibility();
        });

        if (mainRefHeader.value) {
            observer.observe(mainRefHeader.value);
        }
        onUnmounted(() => {
            observer.disconnect();
        });
    });


    // --- Fonctions

    
    function svgConfig(nameSvg) {
        const sizeSvg = '30px';
        return {
            width: sizeSvg,
            height: sizeSvg,
            fill: currentPage(nameSvg) ? '#1b1e33' : 'white',
        }
    }

    function handleClickIcon(request) {
        switch (request) {
            case 'home': {
                statePage.value = request;
                router.push('/connexion');
                break;
            }
            case 'pageTransactions' : {
                statePage.value = request;
                router.push('/liste-achats');
                break;
            }
            case 'disconnect' : {
                localStorage.removeItem('authToken');
                router.push('/connexion');
                break;
            }
            default:
                statePage.value = request;
                alert(statePage.value );
                break;
        }
    }

    function borderCurrentPage(page) {
        return currentPage(page) ? 'border-l-2' : '';
    }

    function currentPage(page) {
        const pathname = window.location.pathname;
        const argUrl = pathname.substring(1);
        return isCurrentPage.value = page === argUrl;
    }
</script>


