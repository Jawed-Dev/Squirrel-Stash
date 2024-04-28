<template>
    <section 
        class="w-[100%] pl-5 mt-custom-margin-main bg-gradient text-white 
        border border-custom-gray-dark rounded-md
        shadow-[#38393b] shadow-custom-main">
        
        <h2 class="py-3 text-[25px] font-light">Achats du mois</h2>
        
        <div class="flex gap-[50px] py-3  border-b border-custom-gray-dark">
            <p class="px-3 py-1 rounded-md cursor-pointer" :class="[stateTabPurchase ? 'bg-custom-gray-2' : '']" @click="handleStateTab(true)" >Achats</p>
            <p class="px-3 py-1 rounded-md cursor-pointer" :class="[stateTabPurchase ? '' : 'bg-custom-gray-2']" @click="handleStateTab(false)" >Paiement réccurent</p>
        </div>

        <div class="h-[500px]">
            <canvas id="myChart"></canvas>
        </div>

    </section>
</template>

<script setup>
    import { ref, onMounted } from 'vue';

    import Chart from 'chart.js/auto';

    const stateTabPurchase = ref(true);

    function handleStateTab(state) {
        stateTabPurchase.value = state;
    }   


    const chartOptions = ref({
    responsive: true,
    maintainAspectRatio: true,
    plugins: {
        legend: {
        display: false,
        },
    },
});
    

</script>