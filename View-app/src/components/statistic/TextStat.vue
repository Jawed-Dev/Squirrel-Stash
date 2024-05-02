<template>
    <div class="flex flex-col text-[17px] w-[25%] 
        bg-main-gradient rounded-sm mt-5
        shadow-custom-gray-dark shadow-custom-main">
        <div class="flex items-center  justify-between px-5 py-3">
            <icon-total-purch :svg="svg" class="bg-header-gradient2 rounded-full p-3 shadow-custom-main"/>
            <p class="pl-[12px] text-white font-light text-[18px]">{{ props.strValue }}</p>
        </div>
        
        <p class="flex justify-end pl-[13px] font-extralight text-white text-[15px] border-t border-[#38393b] py-1 pr-2"> {{ props.nameEconomy }}</p>
        
    </div>
</template>

<script setup>
    import iconTotalPurch from '../../components/svgs/iconTotalPurchase.vue';
    const props = defineProps(['nameEconomy', 'strValue']);

    const svg = {
        fill: 'white',
        width: '60px',
    }

</script>