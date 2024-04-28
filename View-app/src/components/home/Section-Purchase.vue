<template>
    <section 
        class="w-[100%] pl-5 mt-custom-margin-main bg-gradient text-white
        border border-custom-gray-dark rounded-md
        shadow-[#38393b] shadow-custom-main"

        :class="[stateTabPurchase ? 'bg-slate-500' : '']">
        
        <h2 class="py-3 text-[25px] font-light">Achats du mois</h2>
        
        <div class="flex gap-[50px] py-3 border-b border-custom-gray-dark">
            <p @click="handleStateTab" class="border-b-2 border-main-blue cursor-pointer">Achats</p>
            <p @click="handleStateTab" class="border-b-2 border-main-blue cursor-pointer">Paiement réccurent</p>
        </div>

        <div class="h-[500px]">
                
        </div>

    </section>
</template>

<script setup>
    import { ref } from 'vue';
    const stateTabPurchase = ref(0);


    function handleStateTab() {
        stateTabPurchase.value = !stateTabPurchase.value;
    }   

</script>