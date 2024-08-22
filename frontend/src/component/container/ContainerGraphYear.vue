<template>
    <div class="overflow-hidden  shadow-main rounded-[3px] relative">
        <div class="pl-3 bg-main-gradient text-white rounded-md gradient-border ">
            <h2 class="pb-2 pt-3 text-xl font-extralight pr-8">{{(!typeTransaction) ? title1 : title2 }}</h2>
            <div class="w-full flex justify-center">
                <div v-if="!hideToggleButton" class="w-1/6 min-w-[250px]">
                    <ToggleButton v-model:typeTransaction="typeTransaction" text1="Achats" text2="Prélèvements" />
                </div>
            </div>
            <typeGraph 
            :class="opacityDataEmpty" :limitWidth="sizeAxeXGraph" :typeTransaction="typeTransaction" 
            :colorsGraph="(!typeTransaction) ? colorPurchases : colorReccurings" 
            :dataTransaction="dataTransaction" />
      
        </div>
        <div v-show="isDataEmpty" class="w-full">
            <p class="opacity-100 absolute w-fit transform -translate-x-1/2 -translate-y-1/2 text-center text-xl text-white top-1/2 left-1/2">Aucune donnée</p>
        </div>    
    </div>
</template>

<script setup>
    import { computed } from 'vue';
    import ToggleButton from '@/component/button/ToggleButton.vue';
    import { getScreenSize } from '@/composable/useSizeScreen';
    
    
    // variables, props ...
    const props = defineProps({
        typeGraph: {default: ''},
        hideToggleButton: {default: false},
        dataTransaction: {default: []},
        title1: {default:''},
        title2: {default:''},
    });
    const typeTransaction = defineModel();

    const colorPurchases = {
        color1: '#3358f4',
        color2: '#1d8cf810',
        borderColor: '#0098f0'
    };
    const colorReccurings = {
        color1: '#89216B',
        color2: '#DA445310',
        borderColor: '#ec250d'
    }
    const { widthScreenValue } = getScreenSize();

    // life cycle / functions
    const opacityDataEmpty = computed(() => {
        return isDataEmpty.value ? 'opacity-50' : '';
    });

    const sizeAxeXGraph = computed(() => {
        console.log('widthScreenValue', widthScreenValue.value < 500);
        return (widthScreenValue.value < 500) ? '20' : '35';
    });

    const isDataEmpty = computed(() => {
        let isAllDataEmpty = true;
        props.dataTransaction.forEach(data => {
            if (data.total_amount > 0) isAllDataEmpty = false;
        });
        return isAllDataEmpty;
    });
</script>