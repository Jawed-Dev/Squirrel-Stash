<template>

    <div class="pl-2 mt-custom-margin-main w-[calc(50%-10px)] text-white 
        bg-main-gradient rounded-md my-5 
        shadow-black shadow-custom-main gradient-border">     

        <div class="flex items-center justify-between">
            <h2 class="py-3 text-[20px] font-extralight pr-8">{{ props.title}}</h2>
            <p class="cursor-pointer pr-3 transition-transform hover:translate-y-[-5px] duration-[0.5s]">Voir plus ></p>
        </div>

        <div class="flex border-[#38393b] border-b pl-[calc(50px+20px+15px)] py-2 ">
            <p class="w-[150px]">Nom</p>
            <p class="w-[150px]">Prix</p>
            <p class="w-[150px]">Date</p>
            <p class="w-[150px]">Itération</p>
        </div>

        <div class="pl-3">
            <purchase v-for="(purchase, index) of listPurchases" 
            :nameIcon="`restaurant`" :purchaseType="props.purchaseType" 
            :key="purchase.infoPurchase.date" v-model="currentMenuOpen" :idMenu="index" :svg="svg" :infoPurchase="purchase.infoPurchase"/>
        </div>

    </div>

</template>


<script setup>
    // import
    import Purchase from './Purchase.vue';
    import { ref, watch } from 'vue';

    // variables, props...
    const currentMenuOpen = ref(-1);

    const props = defineProps({
        svg: { default: {} }, 
        title: { default: '' },
        purchaseType: {default: 'standard'}
    });

    watch(currentMenuOpen, (newVal, oldVal) => {
        //alert(newVal);
    });

    const infoPurchase = {
        name: 'Spotify',
        price: 100,
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