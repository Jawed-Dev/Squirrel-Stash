<template>
    <div class="flex justify-center rounded-[3px] mt-[10px] bg-main-gradient overflow-hidden  shadow-main">
        <div :class='`text-white w-full relative`'>   
            <div 
                @click="toggleParamsSearch" 
                class="absolute w-full mt-3 shadow-main bg-gradient-x-blue py-2 pl-3
                font-light flex justify-start gap-2 text-[18px] text-white 
                hover:shadow-custom-lower cursor-pointer">
                <UseIconLoader :svg=iconConfig :nameIcon="typeIconShowParams"  />
                <h1>Paramètres de recherche</h1>
            </div>
            <div :class="`w-full gradient-border overflow-hidden ${paddingForMenuOpen}`">
                <form class="mt-16" @submit.prevent="handleSubmit()">
                    <TransitionAxeY>
                        <div v-show="toggleShowParams">
                            <div class="xl:flex w-full">
                                
                                <div class="xl:w-[40%] 2xl:w-[45%] flex justify-center">
                                    <ImageSearch class="pt-5 w-full" :svg="sizeImage" />
                                </div>

                                <div class="grow flex flex-col justify-center">

                                    <div class="xl:mt-5 w-full flex">
                                        <div class="w-full flex flex-col justify-center xl:justify-center 2xl:justify-evenly 
                                            items-center mt-6 gap-2 sm:gap-12 2xl:gap-0 sm:flex-row">
                                            <ContainerAmountInputs 
                                                v-model:searchAmountMin="searchAmountMin" 
                                                v-model:searchAmountMax="searchAmountMax" 
                                            />
                                        </div>
                                    </div>
                
                                    <div class="mt-5 w-full flex">
                                        <div class="w-full flex flex-col justify-center xl:justify-center 2xl:justify-evenly 
                                            items-center gap-2 sm:gap-12 2xl:gap-0 sm:flex-row">
                                            <ContainerDateInputs 
                                                v-model:checkboxDateMin="checkboxDateMin" 
                                                v-model:checkboxDateMax="checkboxDateMax" 
                                                v-model:dateStart="dateStart" 
                                                v-model:dateEnd="dateEnd" 
                                            />
                                        </div>
                                    </div>
                
                                    <div class="mt-5 w-full flex">
                                        <div class="w-full flex flex-col justify-center xl:justify-center 2xl:justify-evenly 
                                            items-center gap-2 sm:gap-12 2xl:gap-0 sm:flex-row">
                                            <ContainerNoteInput v-model="searchNote" />
                                            <ContainerCategoryInput 
                                                v-model:checkboxCategory="checkboxCategory" 
                                                v-model:searchCategory="searchCategory" 
                                                v-model:searchType="searchType" 
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="w-full flex justify-center mt-8 sm:mt-12 my-3">
                                <div class=" shadow-main min-w-[250px] w-1/4 md:w-1/5 overflow-x-hidden text-ellipsis">
                                    <button class="w-full rounded-sm py-2 bg-gradient-blue rounded-br-[3px] font-light hover:opacity-90">Rechercher
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
    import { updateDataTrsSearch } from '@/storesPinia/useUpdateStoreByBackend';
    import { storeParamsSearch } from '@/storesPinia/useStoreDashboard';
    import ContainerAmountInputs from '@/components/container/ContainerAmountInputs.vue';
    import ContainerNoteInput from '@/components/container/ContainerNoteInput.vue';
    import ContainerDateInputs from '@/components/container/ContainerDateInputs.vue';
    import ContainerCategoryInput from '@/components/container/ContainerCategoryInput.vue';
    import TransitionAxeY from '@/components/transition/TransitionAxeY.vue';
    import UseIconLoader from '@/composables/useIconLoader.vue';
    import ImageSearch from '@/components//svg/ImageSearch.vue';
    import { setSvgConfig } from '@/svgUtils/svgConfig';
    import { getScreenSize } from '@/composables/useSizeScreen';
    
    
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
    const imageConfigBig = setSvgConfig({width:'300px', fill:'white' });
    const imageConfig = setSvgConfig({width:'240px', fill:'white' });
    const { widthScreenValue } = getScreenSize();

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
        return (toggleShowParams.value) ? '' : 'pb-1';
    });

    const sizeImage = computed(() => {
        return widthScreenValue.value < 1024 ? imageConfig : imageConfigBig;
    })

    async function handleSubmit() {
        const params = getAllParamsSearch();
        updateDataTrsSearch(params);
        updateStoreParams(params);
    }

    function translateTypeTrs() {
        if(!searchType.value) return '';
        return (searchType.value === 'Achat') ? 'purchase' : 'recurring';
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