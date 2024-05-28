<template>

    <div :class="`${props.width} rounded-[3px] overflow-hidden mt-5 shadow-black shadow-custom-main`">
        <div :class="`flex flex-col text-[17px] gradient-border overflow-hidden bg-main-gradient  `">
            <div class="flex items-center justify-between px-2 py-2 ">
                <component :is="iconComponent" :svg="props.svg" :class="`${props.svg.color} p-3 rounded-lg shadow-black shadow-custom-main`"/>
    
                <div class="flex flex-col items-end gap-3">
                    <!-- <IconPreferences class='cursor-pointer'  v-show="props.showIconConfig" :svg="svg.verySmallIcon" /> -->
                    <setThreshold :showIconConfig="showIconConfig" />
                    <p :class="`pl-[12px] ${props.colorValue} font-[400] text-[18px]`">{{ props.strValue }}</p>
                </div>
            </div>
    
            <div class="pl-[13px] border-t py-1 pr-2 flex items-center justify-end font-extralight text-[15px] border-[#38393b]">
                <p class=" text-white"> {{ props.nameEconomy }}</p>
            </div>
        </div>
    </div>

</template>

<script setup>
    import { getIconByName } from '@/functions/svg/getIcon';
    import { ref, onMounted, shallowRef } from 'vue';
    import IconPreferences from '../svgs/IconPreferences.vue';
    import { svgConfig } from '@/functions/svg/svgConfig';
    import setThreshold from '../overlay/setThreshold.vue';


    const svg = svgConfig;
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
        width: {default: ''},
        showIconConfig: {default: false}
    });


</script>