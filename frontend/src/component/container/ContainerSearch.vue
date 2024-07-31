<template>
    <div class="flex justify-center rounded-[3px] mt-[10px] overflow-hidden">
        <div :class='`text-white bg-main-gradient shadow-black shadow-custom-main w-full relative`'>   
            <div 
                @click="toggleParamsSearch" 
                class="absolute w-full mt-5 shadow-black shadow-custom-main bg-gradient-x-blue py-2 pl-3
                font-light flex justify-start gap-2 text-[18px] text-white 
                hover:shadow-main-blue cursor-pointer">

                <h1>Paramètres de recherche</h1>
                <UseIconLoader :svg=iconConfig :nameIcon="typeIconShowParams"  />
            </div>

            <div :class="`gradient-border overflow-hidden ${paddingForMenuOpen}`">

                <form class="mt-16" @submit.prevent="handleSubmit()">
                    <TransitionAxeY>
                        <div v-show="toggleShowParams">

                            <div class="mt-5 w-full flex justify-center">
                                <div class="flex flex-col justify-center items-center gap-3 mt-3
                                     md:gap-20 lg:gap-32 xl:gap-48 md:flex-row">
                                    <ContainerAmountInputs 
                                        v-model:searchAmountMin="searchAmountMin" 
                                        v-model:searchAmountMax="searchAmountMax" 
                                    />
                                </div>
                            </div>
        
                            <div class="mt-5 w-full flex justify-center">
                                <div class="flex flex-col justify-center items-center gap-3 mt-3
                                    md:gap-20 lg:gap-32 xl:gap-48 md:flex-row">
                                    <ContainerDateInputs 
                                        v-model:checkboxDateMin="checkboxDateMin" 
                                        v-model:checkboxDateMax="checkboxDateMax" 
                                        v-model:dateStart="dateStart" 
                                        v-model:dateEnd="dateEnd" 
                                    />
                                </div>
                            </div>
        
                            <div class="mt-5 w-full flex justify-center">
                                <div class="flex flex-col justify-center items-center gap-3 mt-3
                                     md:gap-20 lg:gap-32 xl:gap-48 md:flex-row">
                                    <ContainerNoteInput v-model="searchNote" />
                                    <ContainerCategoryInput 
                                        v-model:checkboxCategory="checkboxCategory" 
                                        v-model:searchCategory="searchCategory" 
                                        v-model:searchType="searchType" 
                                    />
                                </div>
                            </div>
                    
                            <div class="w-full flex justify-center mt-8 my-3">
                                <div class="shadow-black shadow-custom-main min-w-[200px] w-1/4 md:w-1/5 overflow-x-hidden text-ellipsis">
                                    <button class="w-full rounded-sm py-2 bg-gradient-blue rounded-br-[3px] font-light">Rechercher
                                    </button>
                                </div>
                            </div>
                        </div>
                    </TransitionAxeY>
                </form>
            </div>
        </div>
    </div>
</template>


<script setup>
    import { ref, computed, onMounted } from 'vue';
    import { getCurrentDate } from '@/composable/useGetDate';
    import { updateDataTrsSearch } from '@/storePinia/useUpdateStoreByBackend';
    import { storeParamsSearch } from '@/storePinia/useStoreDashboard';
    import ContainerAmountInputs from '@/component/container/ContainerAmountInputs.vue';
    import ContainerNoteInput from '@/component/container/ContainerNoteInput.vue';
    import ContainerDateInputs from '@/component/container/ContainerDateInputs.vue';
    import ContainerCategoryInput from '@/component/container/ContainerCategoryInput.vue';
    import TransitionAxeY from '@/component/transition/TransitionAxeY.vue';
    import UseIconLoader from '@/composable/useIconLoader.vue';
    import { setSvgConfig } from '@/svg/svgConfig';
    
    // variables, props
    const checkboxDateMin = ref(false);
    const checkboxDateMax = ref(false);
    const checkboxCategory = ref(false);

    const props = defineProps({
        currentOrderSelected: {default: 0},
        orderAsc: {default: false}
    });

    const paramsSearch = storeParamsSearch();
    //const currentDate = getCurrentDate();

    const dateStart = ref('');
    const dateEnd = ref('');
    const searchCategory = ref('');
    const searchType = ref('');
    const searchAmountMin = ref('');
    const searchAmountMax = ref('');
    const searchNote= ref('');
    const toggleShowParams= ref(false);
    const iconConfig = setSvgConfig({width:'30px', fill:'white' });

    // life cycle, functions...
    onMounted(() => {
        const params = getAllParamsSearch();
        updateDataTrsSearch(params);
        updateStoreParams(params);
    });

    const typeIconShowParams = computed(() => {
        return (toggleShowParams.value) ? 'ArrowUp' : 'ArrowDown';
    });
    const paddingForMenuOpen = computed(() => {
        return (toggleShowParams.value) ? '' : 'pb-5';
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

    function toggleParamsSearch() {
        toggleShowParams.value = !toggleShowParams.value;
    }

</script>