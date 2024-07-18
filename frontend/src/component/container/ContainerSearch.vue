<template>
    <div class="flex justify-center rounded-[3px] mt-[10px] overflow-hidden">
        <div class=" text-white bg-main-gradient
        shadow-black shadow-custom-main w-full relative">   
        
            <h2 class="absolute w-full mt-5 shadow-black shadow-custom-main bg-gradient-x-blue p-2 
            font-light flex justify-start text-[18px] text-white">Paramètres de recherche</h2>

            <div class="gradient-border overflow-hidden">
                <form class="py-3 flex flex-col items-center mt-12 w-full" @submit.prevent="handleSubmit()">
                 
                    <div class="flex justify-center gap-40 mt-5 w-full">
                        <ContainerAmountInputs 
                            v-model:searchAmountMin="searchAmountMin" 
                            v-model:searchAmountMax="searchAmountMax" 
                        />
                        <ContainerNoteInput v-model="searchNote" />
                    </div>

                    <div class="flex justify-center gap-40 mt-10 w-full">
                        <ContainerDateInputs 
                            v-model:checkboxDateMin="checkboxDateMin" 
                            v-model:checkboxDateMax="checkboxDateMax" 
                            v-model:dateStart="dateStart" 
                            v-model:dateEnd="dateEnd" 
                        />
                        <ContainerCategoryInput 
                            v-model:checkboxCategory="checkboxCategory" 
                            v-model:searchCategory="searchCategory" 
                            v-model:searchType="searchType" 
                        />
                    </div>
            
                    <div class="w-full flex justify-center">
                        <div class=" shadow-black shadow-custom-main w-1/5 mt-10">
                            <button class= "w-full rounded-sm py-2 bg-gradient-blue rounded-br-[3px] font-light">Rechercher
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>


<script setup>
    import { ref, watch, onMounted } from 'vue';

    import {getCurrentDate} from '@/composable/useGetDate';
    import { updateDataTrsSearch } from '@/storePinia/useUpdateStoreByBackend';
    import { storeParamsSearch } from '@/storePinia/useStoreDashboard';
    import ContainerAmountInputs from '@/component/container/ContainerAmountInputs.vue';
    import ContainerNoteInput from '@/component/container/ContainerNoteInput.vue';
    import ContainerDateInputs from '@/component/container/ContainerDateInputs.vue';
    import ContainerCategoryInput from '@/component/container/ContainerCategoryInput.vue';
    import { svgConfig } from '@/svg/svgConfig';

    const iconConfig = svgConfig.setColorDynamic(svgConfig.largeIcon, 'bg-gradient-blue');   
    
    // variables, props
    const checkboxDateMin = ref(false);
    const checkboxDateMax = ref(false);
    const checkboxCategory = ref(false);

    const props = defineProps({
        currentOrderSelected: {default: 0},
        orderAsc: {default: false}
    });

    const paramsSearch = storeParamsSearch();
    const currentDate = getCurrentDate();

    const dateStart = ref('');
    const dateEnd = ref('');
    const searchCategory = ref('');
    const searchType = ref('');
    const searchAmountMin = ref('');
    const searchAmountMax = ref('');
    const searchNote= ref('');

    // life cycle, functions...
    onMounted(() => {
        const params = getAllParamsSearch();
        updateDataTrsSearch(params);
        updateStoreParams(params);
    });

    async function handleSubmit() {
        const params = getAllParamsSearch();
        updateDataTrsSearch(params);
        updateStoreParams(params);
    }

    function translateTypeTrs() {
        if(!searchType.value) return '';
        return (searchType.value === 'Achat') ? 'purchase' : 'recurring';
    }
    function resetTrsCategory() {
        searchCategory.value = '';
    }
    
    function updateStoreParams(params) {
        paramsSearch.params = params;
        console.log('params 2', params);
    }

    function getAllParamsSearch() {
        return {
            searchDateRangeDateMin: dateStart.value || '',
            searchDateRangeDateMax: dateEnd.value || '',
            searchCategory: searchCategory.value || '',
            searchType: translateTypeTrs() || '',
            searchNote: searchNote.value,
            searchAmountMin: searchAmountMin.value,
            searchAmountMax: searchAmountMax.value,
            currentOrderSelected: props.currentOrderSelected,
            orderAsc: props.orderAsc,
        }
    }

</script>