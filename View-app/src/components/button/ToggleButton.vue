<template>
    <div class="flex border-b border-main-color border w-fit shadow-black shadow-custom-test rounded-r-md rounded-l-md">
        <p class="px-3 py-1 cursor-pointer rounded-l-md" :class="[stateTabPurchase ? 'bg-gradient-blue' : '']" @click="handleStateTab(true)" >Achats</p>
        <p class="px-3 py-1 cursor-pointer rounded-r-md" :class="[stateTabPurchase ? '' : 'bg-gradient-blue']" @click="handleStateTab(false)" >Paiement réccurent</p>
    </div>
</template>


<script setup>
    // import
    import { ref } from 'vue';

    // functions, props ...
    const stateTabPurchase = ref(true);
    

    // functions
    function handleStateTab(state) {
        stateTabPurchase.value = state;
    }   
</script>