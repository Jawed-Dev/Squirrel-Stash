<template>
    <div class="flex flex-col text-[17px] w-[250px] 
        border border-[#38393b] bg-[linear-gradient(to_top_left,_#0c0c0c_80%,_#ffff_350%)] rounded-md my-5 
        shadow-[#38393b] shadow-[0_0_10px_#38393b]">
        <p class="pt-3 pl-[13px] font-medium text-gray-300 text-[16px]"> {{ props.nameEconomy }}</p>
        <p class="pl-[13px] text-white  font-medium  text-[20px]">2000€</p>
        <p class="pl-[13px] text-[15px] mt-5 py-1 border-t border-[#38393b] font-medium text-gray-300">Dernier Mois: <span class="font-medium text-white  text-[18px]">2200€</span></p>
    </div>
</template>

<script setup>

    const props = defineProps(['nameEconomy']);

</script>