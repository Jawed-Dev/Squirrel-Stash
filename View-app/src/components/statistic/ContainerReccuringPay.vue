<template>

    <div class="pl-2 mt-custom-margin-main w-[49%] text-white 
        bg-main-gradient rounded-sm my-5 
        shadow-custom-gray-dark shadow-custom-main">    

        <div class="flex items-center justify-between pr-10">
            <h2 class="py-3 text-[20px] font-light pr-8">Paiments réccurents</h2>
            <p>Voir plus ></p>
        </div>


        <div class="flex border-[#38393b] border-b pl-3 pt-5">
            <p class="w-[150px]">Nom</p>
            <p class="w-[150px]">Prix</p>
            <p class="w-[150px]">Date</p>
        </div>

        <div class="pl-3">
            <recurring-pay :infoReccuringPay="infoRecPay"/>

            <recurring-pay :infoReccuringPay="infoRecPay"/>

            <recurring-pay :infoReccuringPay="infoRecPay"/>
        </div>
    </div>
</template>

<script setup>
        import RecurringPay from './RecurringPay.vue';


        const infoRecPay = {
        name: 'Electricité',
        price: 100,
        date: '26 Mars 2023 15:50',
    }

</script>