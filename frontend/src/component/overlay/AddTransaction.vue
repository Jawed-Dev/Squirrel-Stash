<template>
    <div>
        <div class="fixed bottom-[-2px] sm:bottom-[25px] left-1/2 -translate-x-1/2 -translate-y-1/2  
            md:relative md:translate-x-0 sm:translate-y-0 md:bottom-auto md:left-auto
            z-20 min-w-[197px] rounded-[3px] md:shadow-black md:shadow-custom-main ">
            <div class="bg-transparent md:bg-main-gradient border-none md:border-solid gradient-border">
                <div class="flex justify-center md:justify-between items-center px-2 min-h-[42px] min-w-[214px]">
                    <p v-show="!isMobile" class="text-white flex font-light w-full justify-center cursor-pointer " 
                    @click="toggleMenu('openNClose')">Ajouter un achat</p>
                    <div class="flex py-[2px]">
                        <IconAddPurchase 
                            class="p-[1px] bg-gradient-blue rounded-full md:rounded-md right-[100px] top-[50vh]
                            z-10 shadow-black shadow-custom-main trigger-add-purchase" 
                            :svg="handleStyleIcon"
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
                :class="`fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-30 text-white rounded-[3px] overflow-hidden 
                shadow-black shadow-custom-main trigger-add-purchase bg-main-gradient
                max-[500px]:w-full sm:w-1/4 min-[500px]:min-w-[500px]`">

                <MainContainerSlot 
                    :textBtn1="'Annuler'" :textBtn2="'Ajouter'" :titleContainer="(!typeTransaction) ? 'Ajouter un achat' : 'Ajouter un prélèvement'" 
                    @toggleMenu="toggleMenu" 
                >
                    <div class="max-h-[72vh] overflow-y-auto">
                        <!-- Errors -->
                        <div class="relative">
                            <p class="text-sm font-light p-3 absolute text-red-300">{{ textError }}</p>
                        </div>
                        <div>
                            <!-- Inputs  -->
                            <ContainerInputs 
                                v-model:errorInputs="errorInputs"
                                v-model:inputPriceVal="inputPriceVal" 
                                v-model:inputNoteVal="inputNoteVal" 
                                v-model:inputDateVal="inputDateVal" 
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

        <TransitionPopUp duration-in="500" duration-out="500">
            <OverlaySuccessAction text="Votre transaction a été créée." v-if="isSuccessAction" v-model:overlayActive="isSuccessAction" />
        </TransitionPopUp>
    </div>
    
</template>

<script setup>
    // import
    import { ref, watch, computed, reactive, defineAsyncComponent, onMounted, onUnmounted } from 'vue';
    import { setSvgConfig, svgConfig } from '@/svg/svgConfig';
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
    import { isAnyMandatInputEmpty, isAnyInputError, TYPE_SUBMIT_ERROR, TEXT_SUBMIT_ERROR } from '@/error/useHandleError';
    import TransitionPopUp from '@/component/transition/TransitionPopUp.vue';
    import { isValidCategory } from '@/error/useValidFormat';

    const OverlaySuccessAction = defineAsyncComponent(() => import('@/component/overlay/OverlaySuccessAction.vue'));
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

    const submitError = ref(null);
    const isSuccessAction = ref(false);
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

    const handleStyleIcon = computed(() => (isMobile.value) ? styleIconBase : styleIconMd );

    const isMobile = computed(() => width.value < 768);

    const textError = computed(() => {
        if(submitError.value === TYPE_SUBMIT_ERROR.MANDATORY_EMPTY_INPUTS) return TEXT_SUBMIT_ERROR.MANDATORY_EMPTY_INPUTS;
        else if(submitError.value === TYPE_SUBMIT_ERROR.NOT_SUCCESS_REQUEST) return "La requête a échoué.";
        else if(submitError.value === TYPE_SUBMIT_ERROR.CATEGORY_ERROR) return TEXT_SUBMIT_ERROR.CATEGORY_ERROR;
        else if(submitError.value === TYPE_SUBMIT_ERROR.DATE_EMPTY) return TEXT_SUBMIT_ERROR.DATE_EMPTY;
    });

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
            case 'openNClose' : {
                submitError.value = null;
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

                if(isDateEmpty(nameCategory)) {
                    submitError.value = TYPE_SUBMIT_ERROR.DATE_EMPTY;
                    return;
                }
                else if(isAnyMandatInputEmpty(allMandatoryValInputs)) {
                    submitError.value = TYPE_SUBMIT_ERROR.MANDATORY_EMPTY_INPUTS;
                    return;
                }
                else if(isAnyInputError(allErrorsInputs)) {
                    submitError.value = TYPE_SUBMIT_ERROR.INPUTS_FORMAT_ERRORS;
                    return;
                }
                else if(!isValidCategory(nameCategory)) {
                    submitError.value = TYPE_SUBMIT_ERROR.CATEGORY_ERROR;
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
        const dataRequest = await addTransaction({
            amount: inputPriceVal.value,
            trsCategory: getCurrentNameCategory(),
            trsType: getCurrentTransactionType(),
            date: inputDateVal.value,
            note: inputNoteVal.value
        });
        const isSuccessRequest = dataRequest?.isSuccessRequest;
        if(!isSuccessRequest) {
            submitError.value = TYPE_SUBMIT_ERROR.NOT_SUCCESS_REQUEST;
            return;
        }
        const nameTypeTrs = getCurrentTransactionType();
        const month = dateSelected.month;
        const year = dateSelected.year;
        updateAllDataTransations(month, year, nameTypeTrs);
        submitError.value = null;
        isSuccessAction.value = true;
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

    function isDateEmpty() {
        return inputDateVal.value === '';
    }
</script>