<template>
    <div class="flex flex-col text-[17px] w-[250px] 
        border border-[#38393b] bg-custom-gradient rounded-md my-5 
        shadow-[#38393b] shadow-custom-lower">
        <p class="pt-3 pl-[13px] font-normal text-gray-300 text-[15px]"> {{ props.nameEconomy }}</p>
        <p class="pl-[13px] text-white  font-medium  text-[18px]">2000€</p>
        <p class="pl-[13px] text-[15px] mt-5 py-1 border-t border-[#38393b] font-medium text-gray-300">Dernier Mois: <span class="font-medium text-white  text-[18px]">2200€</span></p>
    </div>
</template>

<script setup>

    const props = defineProps(['nameEconomy']);

</script>