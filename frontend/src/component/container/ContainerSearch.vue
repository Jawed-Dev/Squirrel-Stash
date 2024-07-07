<template>
    <div class="flex justify-center rounded-[3px] mt-custom-margin-main">
        <div class="gradient-border text-white overflow-hidden bg-main-gradient pl-3 shadow-black shadow-custom-main w-full relative">   
            <UseIconLoader 
                extraClass="absolute top-2 rounded-md shadow-black shadow-custom-main p-[0.7vw]" 
                :svg="iconConfig" nameIcon="Search" 
            />
            <form class="py-3 flex flex-col" @submit.prevent="handleSubmit()">
                <div class="flex justify-evenly mt-[20px]">
                    <ContainerAmountInputs 
                        v-model:searchAmountMin="searchAmountMin" 
                        v-model:searchAmountMax="searchAmountMax" 
                    />
                    <ContainerNoteInput v-model="searchNote" />
                </div>

                <div class="flex justify-evenly mt-[50px]">
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
                    <div class="mt-[40px] shadow-black shadow-custom-main w-[25%]">
                        <button class=
                            "text-[17px] w-full rounded-sm py-2 bg-gradient-blue 
                            rounded-br-[3px] font-light">Rechercher
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>


<script setup>
    import { ref, watch, onMounted } from 'vue';

    import {getCurrentDate} from '@/composable/useGetDate';
    import { updateListTrsBySearch } from '@/storePinia/useUpdateStoreByBackend';
    import { storeParamsSearch } from '@/storePinia/useStoreDashboard';
    import ContainerAmountInputs from '@/component/container/ContainerAmountInputs.vue';
    import ContainerNoteInput from '@/component/container/ContainerNoteInput.vue';
    import ContainerDateInputs from '@/component/container/ContainerDateInputs.vue';
    import ContainerCategoryInput from '@/component/container/ContainerCategoryInput.vue';
    import UseIconLoader from '@/composable/useIconLoader.vue';
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

    const dateStart = ref(currentDate);
    const dateEnd = ref(currentDate);
    const searchCategory = ref('Alimentation');
    const searchType = ref('Achat');
    const searchAmountMin = ref('');
    const searchAmountMax = ref('');
    const searchNote= ref('');

    // life cycle, functions...
    onMounted(() => {
        const params = getAllParamsSearch();
        updateListTrsBySearch(params);
        updateStoreParams(params);
    });

    async function handleSubmit() {
        const params = getAllParamsSearch();
        updateListTrsBySearch(params);
        updateStoreParams(params);
    }

    watch(searchType, (newVal) => {
        //resetTrsCategory();
    });

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
            searchDateRangeDateMin: (checkboxDateMin.value) ? dateStart.value : '',
            searchDateRangeDateMax: (checkboxDateMax.value) ?  dateEnd.value : '',
            searchCategory: (checkboxCategory.value) ? searchCategory.value : '',
            searchType: (checkboxCategory.value) ? translateTypeTrs() : '',
            searchNote: searchNote.value,
            searchAmountMin: searchAmountMin.value,
            searchAmountMax: searchAmountMax.value,
            currentOrderSelected: props.currentOrderSelected,
            orderAsc: props.orderAsc,
        }
    }

</script>