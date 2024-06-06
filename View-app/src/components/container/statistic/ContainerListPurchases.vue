<template>
    <div class="rounded-[3px] overflow-hidden my-custom-margin-main shadow-black shadow-custom-main ">
        <div class="gradient-border pl-2 text-white overflow-hidden
            bg-main-gradient $ ">     
    
            <div class="flex items-center justify-between">
                <h2 class="py-3 text-[20px] font-extralight pr-8">{{ props.title}}</h2>
                <p :class="`cursor-pointer pr-3 ${translateY}`">Voir plus ></p>
            </div>
    
            <div class="flex border-gray-700 pl-[calc(50px+60px+15px)] py-2">
                <p class="w-[15%]">Nom</p>
                <p class="w-[20%]">Montant</p>
                <p class="w-[17%]">Date</p>
                <p class="w-[20%]">Itération</p>
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
    import { classTransitionHover } from '../../../functions/classTransitionHover';

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
        date: '26/03/2024',
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