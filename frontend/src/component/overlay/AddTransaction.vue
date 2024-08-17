<template>
    <div>
        <div class="fixed bottom-[-2px] sm:bottom-[25px] left-[calc(50%+1px)] transform -translate-x-1/2 -translate-y-1/2
            md:relative md:translate-x-0 sm:translate-y-0 md:bottom-auto md:left-auto
            z-20 md:z-10 mt-5 w-full lg:min-w-[calc(200px*2+7px)] lg:w-1/4 rounded-[3px] md:shadow-main
            hover:shadow-slate-500">

            <div @click="toggleMenu('openOverlay')" 
            class="bg-transparent md:bg-main-gradient border-none md:border-solid gradient-border trigger-add-purchase cursor-pointer">
                <div class="flex justify-center md:justify-between items-center md:px-2 min-h-[42px] min-w-[214px]">

                    <p v-show="!isMobile" 
                    class="w-full text-white flex justify-center font-light" 
                    >Ajouter une transaction</p>

                    <div class="grow flex justify-center py-[2px]">
                        <IconAddPurchase 
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
                max-[600px]:w-full sm:w-1/4 min-[600px]:min-w-[600px]`">

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
    import { ref, watch, computed, reactive, defineAsyncComponent, onMounted, onUnmounted } from 'vue';
    import { setSvgConfig } from '@/svg/svgConfig';
    import IconAddPurchase from '@/component/svgs/IconAddPurchase.vue';
    import TransitionOpacity from '@/component/transition/TransitionOpacity.vue';
    import useClickOutside from '@/composable/useClickOutSide';
    import useEscapeKey from '@/composable/useEscapeKey';
    import MainContainerSlot from '@/component/containerSlot/MainContainerSlot.vue';
    import { storeDateSelected } from '@/storePinia/useStoreDashboard';
    import { addTransaction } from '@/composable/useBackendActionData';
    import { updateAllDataTransations} from '@/storePinia/useUpdateStoreByBackend';
    import { formatDateForCurrentDay, formatDateForFirstDay, isCurrentMonth } from '@/composable/useGetDate';
    import { listPurchases, listRecurings } from '@/svg/listTransactionSvgs';
    import { isAnyMandatoryInputEmpty, isAnyInputError, TYPE_SUBMIT_ERROR, TEXT_SUBMIT_ERROR } from '@/error/useHandleError';
    import { isValidCategory } from '@/error/useValidFormat';
    import { createToast } from '@/composable/useToastNotification';

    const ContainerInputs = defineAsyncComponent(() => import('@/component/container/ContainerInputs.vue'));
    const ContainerSelectCategories = defineAsyncComponent(() => import('@/component/container/ContainerSelectCategories.vue'));

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
    const width = ref(window.innerWidth);

    // life cycle / functions
    onMounted( () => {
        window.addEventListener('resize', handleResize);
    });

    onUnmounted(() => {
        window.removeEventListener('resize', handleResize);
    });

    const styleIcon = computed(() => (isMobile.value) ? styleIconBase : styleIconMd );
    const isMobile = computed(() => width.value < 768);

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

    async function toggleMenu(request) {
        switch(request) {
            case 'openOverlay' : {
                typeTransaction.value = false;
                resetInputs();
                isOverlayActive.value = !isOverlayActive.value;
                break;
            }
            case 'valid' : {
                if(!isOverlayActive.value) return;
                const allErrorsInputs = getStatesErrorInputs();
                const allMandatoryValInputs = getValuesMandantInputs();
                const nameCategory = getCurrentNameCategory();

                if(isAnyMandatoryInputEmpty(allMandatoryValInputs)) {
                    activeErrorForMandatInputsEmpty();
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

    function handleResize() {
        width.value = window.innerWidth;
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
    }

    function closeMenu() {
        isOverlayActive.value = false;
    }
    
    async function prepareAddTransaction() {
        const response = await addTransaction({
            amount: inputPriceVal.value,
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
    function activeErrorForMandatInputsEmpty() {
        if (!inputPriceVal.value) mandatoryInputs.inputPriceVal = true;
        if (!inputDateVal.value) mandatoryInputs.inputDateVal = true;
    }
    function isDateEmpty() {
        return inputDateVal.value === '' || inputDateVal.valueAsDate === null;
    }
</script>