<template>
    <div>
        <!-- responsive button add transaction & trigger click out side  -->
        <div class="fixed left-[calc(50%+2px)] bottom-[-2px] md:relative sm:bottom-[25px] md:bottom-auto md:left-auto 
            transform -translate-x-1/2 -translate-y-1/2 md:translate-x-0 sm:translate-y-0 
            z-20 md:z-10 w-full lg:min-w-[calc(200px*2+7px)] lg:w-1/4 rounded-[3px] 
            md:hover:shadow-custom-lower md:shadow-main">

            <!-- border and background -->
            <div @click="toggleOverlay" 
                class="bg-transparent md:bg-main-gradient border-none md:border-solid gradient-border 
                trigger-add-purchase cursor-pointer">
                <!-- width responsive -->
                <div class="flex justify-center md:justify-between items-center md:px-2 min-h-[42px] min-w-[214px] ">
                    <p v-show="!isBelow768px" 
                        class="w-full text-white flex justify-center font-light" 
                        >Ajouter une transaction
                    </p>
                    <div class="flex justify-center py-[2px]">
                        <!-- Icon and border rounded  -->
                        <IconAddPurchase 
                            @click="toggleOverlayMobile" 
                            class="p-[1px] bg-gradient-blue rounded-full md:rounded-md right-[100px] top-[50vh]
                            z-10 shadow-main" 
                            :svg="styleIcon"
                        />
                    </div>
                </div>
            </div>
        </div>

        <TransitionOpacity :durationIn="'duration-300'" :durationOut="'duration-200'">
            <div v-if="isOverlayActive" class="fixed inset-0 bg-black bg-opacity-80 z-30"></div>
        </TransitionOpacity>

        <TransitionOpacity :durationIn="'duration-300'" :durationOut="'duration-200'">
            <div v-if="isOverlayActive" 
                :class="`fixed top-1/2 left-1/2  -translate-x-1/2 -translate-y-1/2 z-30 text-white rounded-[3px] overflow-hidden 
                shadow-main trigger-add-purchase bg-main-gradient
                max-[550px]:w-full sm:w-1/4 min-[550px]:min-w-[550px]`">

                <MainContainerSlot 
                    :textBtn1="'Annuler'" :textBtn2="'Ajouter'" 
                    :titleContainer="(!typeTransaction) ? 'Ajouter un achat' : 'Ajouter un prélèvement'" 
                    @toggleMenu="toggleMenu" 
                >
                    <div class="max-h-[72vh] overflow-y-auto">
                        <div>
                            <!-- Inputs  -->
                            <ContainerInputs 
                                v-model:errorInputs="errorInputs"
                                v-model:inputPriceVal="inputPriceVal" 
                                v-model:inputNoteVal="inputNoteVal" 
                                v-model:inputDateVal="inputDateVal" 
                                v-model:mandatoryInputs="mandatoryInputs"
                                :typeTransaction="typeTransaction" 
                            />
                            <!-- Categories -->
                            <ContainerSelectCategories v-model:currentCategory="currentCategory" 
                            v-model:typeTransaction="typeTransaction" />              
                        </div>

                    </div>
                </MainContainerSlot>
            </div>
        </TransitionOpacity>
    </div>
</template>

<script setup>
    // import
    import { ref, watch, computed, reactive, defineAsyncComponent } from 'vue';
    import { setSvgConfig } from '@/svg/svgConfig';
    import IconAddPurchase from '@/components/svgs/IconAddPurchase.vue';
    import TransitionOpacity from '@/components/transition/TransitionOpacity.vue';
    import useClickOutside from '@/composables/useClickOutSide';
    import useEscapeKey from '@/composables/useEscapeKey';
    import MainContainerSlot from '@/components/containerSlot/MainContainerSlot.vue';
    import { storeDateSelected } from '@/storesPinia/useStoreDashboard';
    import { addTransaction } from '@/composables/useBackendActionData';
    import { updateAllDataTransations} from '@/storesPinia/useUpdateStoreByBackend';
    import { formatDateForCurrentDay, formatDateForFirstDay, isCurrentMonth } from '@/composables/useGetDate';
    import { listPurchases, listRecurings } from '@/svg/listTransactionSvgs';
    import { isAnyMandatoryInputEmpty, isAnyInputError, TEXT_SUBMIT_ERROR } from '@/errors/useHandleError';
    import { isValidCategory } from '@/errors/useValidFormat';
    import { createToast } from '@/composables/useToastNotification';
    import { formatStringToFloat } from '@/composables/useMath';
    import { getScreenSize } from '@/composables/useSizeScreen';

    const ContainerInputs = defineAsyncComponent(() => import('@/components/container/ContainerInputs.vue'));
    const ContainerSelectCategories = defineAsyncComponent(() => import('@/components/container/ContainerSelectCategories.vue'));

    // stores Pinia
    const dateSelected = storeDateSelected();

    // variables, props...
    // menu
    const isOverlayActive = ref(false); 
    const typeTransaction = ref (false); 
    const currentCategory = ref(0);

    // input ref
    const inputNoteVal = ref('');
    const inputPriceVal = ref('');
    const inputDateVal = ref('');

    const errorInputs = reactive({
        inputNoteVal: false,
        inputPriceVal: false,
        inputDateVal: false
    });
    const mandatoryInputs = reactive({
        inputPriceVal: false,
        inputDateVal: false
    });

    const styleIconMd = setSvgConfig({width:'32px', fill:'white', });
    const styleIconBase = setSvgConfig({width:'50px', fill:'white', });
    const { widthScreenValue } = getScreenSize();

    // life cycle / functions
    const styleIcon = computed(() => (isBelow768px.value) ? styleIconBase : styleIconMd );
    const isBelow768px = computed(() => widthScreenValue.value < 768);

    watch( () => [dateSelected.month, dateSelected.year], async ([newMonth, newYear]) => {
        inputDateVal.value = formatDateInput();
    }, {  immediate:true, deep:true });

    watch(typeTransaction, () => {
        currentCategory.value = 0;
    });

    useEscapeKey(isOverlayActive, () => {
        closeMenu();
    });

    useClickOutside('.trigger-add-purchase', isOverlayActive, () => {
        closeMenu();
    });

    function toggleOverlay() {
        if(isBelow768px.value) return;
        typeTransaction.value = false;
        resetInputs();
        isOverlayActive.value = !isOverlayActive.value;
    };

    function toggleOverlayMobile() {
        if(!isBelow768px.value) return;
        typeTransaction.value = false;
        resetInputs();
        isOverlayActive.value = !isOverlayActive.value;
    };


    async function toggleMenu(request) {
        switch(request) {
            case 'valid' : {
                if(!isOverlayActive.value) return;
                const allErrorsInputs = getStatesErrorInputs();
                const allMandatoryValInputs = getValuesMandantInputs();
                const nameCategory = getCurrentNameCategory();

                if(isAnyMandatoryInputEmpty(allMandatoryValInputs)) {
                    activeErrorMandatInput();
                    createToast(TEXT_SUBMIT_ERROR.MANDATORY_EMPTY_INPUTS, 'error');
                    return;
                }
                else if(isAnyInputError(allErrorsInputs)) {
                    return;
                }
                else if(!isValidCategory(nameCategory)) {
                    return;
                }
                prepareAddTransaction();
                closeMenu()
                break;
            }
            case 'cancel' : {
                closeMenu()
                break;
            }
        }
    }
    
    function getCurrentTransactionType() {
        return (!typeTransaction.value) ? 'purchase' : 'recurring';
    }

    function getCurrentNameCategory() {
        const listTransaction = (!typeTransaction.value) ? listPurchases : listRecurings;
        const currentIndex = currentCategory.value;
        const nameCategory = (listTransaction[currentIndex]?.text) ? listTransaction[currentIndex].text : '';
        return nameCategory;
    }

    function formatDateInput() {
        const month = dateSelected.month;
        const year = dateSelected.year;
        return (isCurrentMonth(month, year)) ? formatDateForCurrentDay(month, year) : formatDateForFirstDay(month, year);
    }

    function resetInputs() {
        inputNoteVal.value = '';
        inputPriceVal.value = '';
        currentCategory.value = 0;
        currentCategory.value = 0;
        inputDateVal.value = formatDateInput();
        disableErrorMandatInputs();
    }

    function closeMenu() {
        isOverlayActive.value = false;
    }
    
    async function prepareAddTransaction() {
        const response = await addTransaction({
            amount: formatStringToFloat(inputPriceVal.value),
            trsCategory: getCurrentNameCategory(),
            trsType: getCurrentTransactionType(),
            date: inputDateVal.value,
            note: inputNoteVal.value
        });
        const isSuccessRequest = response?.isSuccessRequest;
        if(!isSuccessRequest) {
            return;
        }
        const nameTypeTrs = getCurrentTransactionType();
        const month = dateSelected.month;
        const year = dateSelected.year;
        createToast('Votre transaction a été ajoutée.', 'success');
        updateAllDataTransations(month, year, nameTypeTrs);
    }
    
    function getStatesErrorInputs() {
        return {
            date: errorInputs.inputDateVal,
            amount: errorInputs.inputPriceVal,
            note: errorInputs.inputNoteVal
        }
    }
    function getValuesMandantInputs() {
        return {
            date: inputDateVal.value,
            amount: inputPriceVal.value,
            category: currentCategory.value,
        }
    }
    function activeErrorMandatInput() {
        if (!inputPriceVal.value) mandatoryInputs.inputPriceVal = true;
        if (!inputDateVal.value) mandatoryInputs.inputDateVal = true;
    }

    function disableErrorMandatInputs() {
        mandatoryInputs.inputPriceVal = false;
        mandatoryInputs.inputDateVal = false;
    }

</script>