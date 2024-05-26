<template>
    <div class="rounded-[3px] overflow-hidden my-custom-margin-main shadow-black shadow-custom-main ">
        <div class="gradient-border pl-2 text-white overflow-hidden
            bg-main-gradient $ ">     
    
            <div class="flex items-center justify-between">
                <h2 class="py-3 text-[20px] font-extralight pr-8">{{ props.title}}</h2>
                <p :class="`cursor-pointer pr-3 ${translateY}`">Voir plus ></p>
            </div>
    
            <div class="flex border-[#38393b] pl-[calc(50px+20px+15px)] py-2 ">
                <p class="w-[150px]">Nom</p>
                <p class="w-[150px]">Prix</p>
                <p class="w-[150px]">Date</p>
                <p class="w-[150px]">Itération</p>
            </div>
    
            <div class="pl-3">
                <PurchaseInfo v-for="(purchase, index) of listPurchases" 
                    :key="purchase.infoPurchase.date" :nameIcon="`restaurant`" :purchaseType="props.purchaseType" 
                    v-model:currentMenu="currentIdMenuOpen" :indexMenu="index" :svg="svg" :infoPurchase="purchase.infoPurchase"
                />
            </div>
        </div>
    </div>
</template>


<script setup>
    // import
    import PurchaseInfo from '@/components/statistic/PurchaseInfo.vue';
    import { ref, watch } from 'vue';
    import { classTransitionHover } from '../transition/classTransitionHover';

    const translateY = classTransitionHover('translateY');

    // variables, props...
    const currentIdMenuOpen = ref(-1);

    const props = defineProps({
        svg: { default: {} }, 
        title: { default: '' },
        purchaseType: {default: 'standard'}
    });

    watch(currentIdMenuOpen, (newVal, oldVal) => {
        //alert(newVal);
    });

    const infoPurchase = {
        name: 'Spotify',
        price: "100.00",
        date: '26 Mars 2023 15:50',
        iteration: 10
    };

    const listPurchases = [
        { infoPurchase },
        { infoPurchase },
        { infoPurchase },
        { infoPurchase },
        { infoPurchase }
    ];
</script>