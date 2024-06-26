<template>
    <div class="flex justify-center items-center w-full gap-[10px] px-[5px]">
        <useIconLoader class="rounded-full p-2 shadow-black shadow-custom-main" 
            :svg="(!typeTransaction) ? svgPurchases : svgReccurings" 
            :nameIcon="nameIcon" 
        />
        <select v-model="currentCategory" 
        :class="` text-[16px] font-light pl-2 py-1 gradient-border text-white ${width} rounded-[3px] bg-main-gradient 
            shadow-black shadow-custom-main outline-none`"
        >
            <option class="bg-main-bg font-light" v-for="(category, index) in listSelect" :key="index" :value="index">{{ category.text }}</option>
        </select>
    </div>
</template>


<script setup>
    import { computed } from 'vue';
    import useIconLoader from '@/composable/useIconLoader.vue';
    import { svgConfig } from '@/svg/svgConfig';


    // props, variables
    const props = defineProps({
        width: { default: 'w-[70%]' },
        listSelect: { default: [] },
        typeTransaction: { default: false },
    });

    const currentCategory = defineModel('currentCategory');
    const svgPurchases = svgConfig.setColorDynamic(svgConfig.mediumSmaller, 'bg-gradient-blue');   
    const svgReccurings = svgConfig.setColorDynamic(svgConfig.mediumSmaller, 'bg-gradient-vanusa');  

    const nameIcon = computed(() => {
        return (props.listSelect[currentCategory.value].nameIcon) ? props.listSelect[currentCategory.value].nameIcon : '';
    });

    // life cycle, functions ...
    function handleChange(event) {
        const index = event.target.value;
        alert(index);
      
    }

    

</script>