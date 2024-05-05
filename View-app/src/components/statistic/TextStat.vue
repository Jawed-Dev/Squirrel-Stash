<template>
    <div class="flex flex-col text-[17px] w-[25%] 
        bg-main-gradient rounded-sm mt-5
        shadow-custom-gray-dark shadow-custom-main">
        <div class="flex items-center justify-between px-5 py-3">
            <component :is="iconComponent" :svg="props.svg" :class="`${props.svg.color} rounded-lg p-3 shadow-custom-main`"/>
            <p :class="`pl-[12px] ${props.colorValue} font-[300] text-[18px]`">{{ props.strValue }}</p>
        </div>
        
        <p class="flex justify-end pl-[13px] font-extralight text-white text-[15px] border-t border-[#38393b] py-1 pr-2"> {{ props.nameEconomy }}</p>
        
    </div>
</template>

<script setup>
    import { getIconByName } from '@/functions/icons/getIcon';
    import { ref, onMounted } from 'vue';

    const iconComponent = ref(null);

    onMounted(async () => {
        const module = await getIconByName(props.svg.name);
        iconComponent.value = module.default;
    });


    const props = defineProps({
        svg : { default: {} },
        nameEconomy : { default: '' },
        strValue : { default: ''  },
        colorValue : { default: '' }
    });


</script>