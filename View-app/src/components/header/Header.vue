<template>
    <div class="w-header-width flex flex-col items-center top-top-Header left-top-Header fixed pt-10 gap-5 h-[calc(100vh-40px)] 
            bg-header-gradient rounded-md
             shadow-custom-gray-dark shadow-custom-main">
 
            <NavIconDashboard :svg="svg" @click="handleClickIcon('home')" />
            <NavIconPurchases :svg="svg" />

            <IconListPurchase :svg="svg" />
            
            <icon-nav :svgIcon="'detail'" @click="handleClickIcon('detail')"  />
            
            <icon-nav :svgIcon="'purchase'" @click="handleClickIcon('purchase')"  />

            <icon-nav :svgIcon="'alarm'" @click="handleClickIcon('alarm')"  />

            <icon-nav :svgIcon="'user'" @click="handleClickIcon('user')"  />

    </div>

    <!-- style="text-shadow: 4px 4px 3px rgba(0,0,0,1); -->
</template>


<script setup>
    import { useRouter } from 'vue-router';
    import iconNav from '../icons/nav/IconNav.vue';
    import IconListPurchase from '../svgs/iconListPurchase.vue';

    import NavIconDashboard from '../svgs/NavIconDashboard.vue';
    import NavIconPurchases from '../svgs/NavIconPurchases.vue';

    import {ref} from 'vue';



    const svg = {
        fill: 'white',
        width: '40px',
        height: '40px',
        stroke: 'white'
    }

    
    const router = useRouter();

    function handleClickIcon(request) {

        switch (request) {
            case 'home': {
                statePage.value = request;
                router.push('/login');
                break;
            }
                
        
            default:
                statePage.value = request;
                alert(statePage.value );
                break;
        }
    }

</script>


