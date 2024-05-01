<template>

    <div class="pl-2 mt-custom-margin-main w-[49%] text-white 
        bg-main-gradient rounded-sm my-5 
        shadow-custom-gray-dark shadow-custom-main">     

        <div class="flex items-center justify-between pr-10">
            <h2 class="py-3 text-[20px] font-extralight pr-8">Historique de transaction</h2>
            <p>Voir plus ></p>
        </div>

        <div class="flex border-[#38393b] border-b pl-3 pt-5">
            <p class="w-[150px]">Nom</p>
            <p class="w-[150px]">Prix</p>
            <p class="w-[150px]">Date</p>
            <p class="w-[150px]">Itération</p>
        </div>

        <div class="pl-3">
            <purchase :infoPurchase="infoPurchase"/>

            <purchase :infoPurchase="infoPurchase"/>

            <purchase :infoPurchase="infoPurchase"/>

            <purchase :infoPurchase="infoPurchase"/>

            <purchase :infoPurchase="infoPurchase"/>
        </div>

    </div>

</template>


<script setup>
    import Purchase from './Purchase.vue';
    const infoPurchase = {
        name: 'Spotify',
        price: 100,
        date: '26 Mars 2023 15:50',
        iteration: 10
    };

</script>