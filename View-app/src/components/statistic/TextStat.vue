<template>
    <div :class="`flex flex-col text-[17px] ${props.width} rounded-md overflow-hidden
        bg-main-gradient mt-5 shadow-black shadow-custom-main`">
        <div class="gradient-border rounded-md">
            <div class="flex items-center justify-between px-2 py-3">
                <component :is="iconComponent" :svg="props.svg" :class="`${props.svg.color} p-3 rounded-lg shadow-black shadow-custom-main`"/>
                <p :class="`pl-[12px] ${props.colorValue} font-[300] text-[18px]`">{{ props.strValue }}</p>
            </div>
            <p class="flex justify-end pl-[13px] font-extralight text-white text-[15px] border-t border-[#38393b] py-1 pr-2"> {{ props.nameEconomy }}</p>
        </div>
    </div>
</template>

<script setup>
    import { getIconByName } from '@/functions/icons/getIcon';
    import { ref, onMounted, shallowRef } from 'vue';

    const iconComponent = shallowRef(null);

    onMounted(async () => {
        const module = await getIconByName(props.svg.name);
        iconComponent.value = module.default;
    });


    const props = defineProps({
        svg : { default: {} },
        nameEconomy : { default: '' },
        strValue : { default: ''  },
        colorValue : { default: '' },
        width: {default: ''}
    });


</script>