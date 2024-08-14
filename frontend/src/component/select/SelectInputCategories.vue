<template>
    <div class="flex justify-center items-center w-[65%] gap-2">
        <useIconLoader class="rounded-full p-2  shadow-main w-fit" 
            :svg="(!typeTransaction) ? svgPurchases : svgReccurings" 
            :nameIcon="nameIcon" 
        />
        <select v-model="currentCategory" 
        :class="`font-light pl-2 py-[2px] gradient-border text-white ${width} grow
            rounded-[3px] bg-main-gradient  shadow-main outline-none`"
        >
        <option class="bg-main-bg font-light" v-for="(category, index) in listSelect" :key="index" :value="index">{{ category.text }}</option>
        </select>
    </div>
</template>


<script setup>
    import { computed } from 'vue';
    import useIconLoader from '@/composable/useIconLoader.vue';
    import { setSvgConfig } from '@/svg/svgConfig';

    // props, variables
    const props = defineProps({
        width: { default: 'w-[70%]' },
        listSelect: { default: [] },
        typeTransaction: { default: false },
    });

    const currentCategory = defineModel('currentCategory');
    const svgPurchases = setSvgConfig({
        width: '35px',
        fill: 'white',
        color: 'bg-gradient-blue'
    });

    console.log(svgPurchases);
    const svgReccurings = setSvgConfig({
        width: '35px',
        fill: 'white',
        color: 'bg-gradient-vanusa'
    });

    const nameIcon = computed(() => {
        return (props.listSelect[currentCategory.value].text) ? props.listSelect[currentCategory.value].text : '';
    });
</script>