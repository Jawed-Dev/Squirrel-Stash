<template>
    <div class="flex flex-col text-[17px] w-[25%] 
        bg-main-gradient rounded-sm mt-5 py-4
        shadow-custom-gray-dark shadow-custom-main">
        <div>
            <p class="pl-[12px] text-white font-normal text-[18px]">2000€</p>
        </div>
        
        <p class="pt-3 pl-[13px] font-extralight text-gray-300 text-[14px] border-t border-[#38393b]"> {{ props.nameEconomy }}</p>
    </div>
</template>

<script setup>

    const props = defineProps(['nameEconomy']);

</script>