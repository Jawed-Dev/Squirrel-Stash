<template>
    <div class="
            flex flex-col items-center 
            w-header-width top-top-Header left-top-Header fixed pt-10  h-[calc(100vh-40px)] 
            bg-header-gradient rounded-md
          shadow--dark shadow-custom-test
            hover:w-[200px] transition-all duration-700 z-10"
                @mouseenter="isHovered = true"
                @mouseleave="isHovered = false"
            >
            
            <div class="pl-5 w-[70%] border-[1px] border-white mt-[40px] "></div>
 
            <div class="w-[100%] flex pl-5 flex-col gap-5 mt-[20px]">

                <div class="flex relative">
                    <NavIconDashboard :svg="svgConfig('home')" @click="handleClickIcon('home')" :class="classIcons" />
                    <TransitionText :text="'Tableau de bord'" :condition="isHovered" />
                </div>

                <div class="flex relative">
                    <NavIconPurchases :svg="svgConfig('', 'white')" :class="classIcons"  />
                    <TransitionText :text="'Liste d\'achat'" :condition="isHovered" />
                </div>
                
                <div class="flex relative">
                    <NavIconGraph :svg="svgConfig('')" :class="classIcons"/>
                    <TransitionText :text="'Graphiques'" :condition="isHovered" />
                </div>
                
                <div class="flex relative">
                    <NavIconBell :svg="svgConfig('')" :class="classIcons"/>
                    <TransitionText :text="'Alarme'" :condition="isHovered" />
                </div>

                <div class="flex relative">
                    <NavIconUser :svg="svgConfig('', 'white')" :class="classIcons"/>
                    <TransitionText :text="'Utilisateur'" :condition="isHovered" />
                </div>
                
                
            </div>
            
    </div>

    <!-- style="text-shadow: 4px 4px 3px rgba(0,0,0,1); -->
</template>


<script setup>
    import { useRouter } from 'vue-router';
    import iconNav from '../icons/nav/IconNav.vue';
    import IconListPurchase from '../svgs/IconListPurchase.vue';

    import NavIconDashboard from '../svgs/NavIconDashboard.vue';
    import NavIconPurchases from '../svgs/NavIconPurchases.vue';
    import NavIconGraph from '../svgs/NavIconGraph.vue';
    import NavIconBell from '../svgs/NavIconBell.vue';
    import NavIconUser from '../svgs/NavIconUser.vue';

    import TransitionText from './TransitionText.vue';

    import {ref, computed} from 'vue';
    
    const statePage = ref(null);
    const classIcons = 'cursor-pointer transition-transform hover:translate-y-[-5px] duration-[0.5s]';
    const router = useRouter();

    const isHovered = ref(false);
    const isTextVisible = ref(false);

    function test() {
   
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


    function svgConfig(nameSvg) {
        const width = '30px';
        const pathname = window.location.pathname;
        const partOfUrl = pathname.substring(1);

        const isActive = computed(() => partOfUrl === nameSvg);
        return {
            name: nameSvg,
            width: width,
            height: width,
            fill: isActive.value ? '#1b1e33' : 'white',
            stroke: 'none'
        }
    }

    

</script>


