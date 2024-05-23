<template>
    <div ref="mainRefHeader" :class="`
            flex flex-col items-center 
            w-header-width top-top-Header left-top-Header fixed pt-10  h-[calc(100vh-40px)] 
            bg-header-gradient rounded-md
            shadow--dark shadow-custom-test
           ${classTranslateWidth} z-10`"
                @mouseenter="isHovered = true"
                @mouseleave="isHovered = false"
            >
            
            <div class="pl-5 w-[70%] border-[1px] border-white mt-[40px] "></div>
 
            <div class="w-[100%] flex flex-col gap-5 mt-[20px]" v-for="(icon, index) of listIcons">
                
                <div @click="handleClickIcon(icon.page)" :class="`flex relative  ${classTranslateY} cursor-pointer ${borderCurrentPage(icon.page)} pl-3 ml-1`">
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
    import iconNav from '../icons/nav/IconNav.vue';
    import IconListPurchase from '../svgs/IconListPurchase.vue';

    // icones 
    import NavIconDashboard from '../svgs/IconDashboard.vue';
    import NavIconPurchases from '../svgs/IconPurchases.vue';
    import NavIconGraph from '../svgs/IconGraph.vue';
    import NavIconBell from '../svgs/IconBell.vue';
    import NavIconUser from '../svgs/NavIconUser.vue';
    import { classTransitionHover } from '../transition/classTransitionHover';
    import IconTarget from '../svgs/IconTarget.vue';
    import IconLogOut from '../svgs/IconLogOut.vue';

    import {ref, computed, onMounted, onUnmounted} from 'vue';

    import TransitionOpacity from '../transition/TransitionOpacity.vue';
    

    // --- Variables, props, ...

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
                Component: NavIconDashboard,
                page: 'home',
                text: 'Tableau de bord'
            },
            {
                Component: IconTarget,
                page: '',
                text: 'Objectifs'
            },
            {
                Component: NavIconPurchases,
                page: '',
                text: 'Liste des achats'
            },
            {
                Component: NavIconGraph,
                page: '',
                text: 'Graphiques'
            },
            {
                Component: NavIconBell,
                page: '',
                text: 'Alarmes'
            },
            {
                Component: NavIconUser,
                page: '',
                text: 'Utilisateur'
            },
            {
                Component: IconLogOut,
                page: '',
                text: 'Déconnexion'
            }
        ];

    const updateTextVisibility = () => {
        isTextIconsVisible.value = mainRefHeader.value.clientWidth > 190;
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


