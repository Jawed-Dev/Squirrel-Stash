<template>
    <div>
        <div class="rounded-[3px] overflow-hidden shadow-black shadow-custom-main ">
            <div class="bg-main-gradient w-[230px] flex justify-around items-center p-1 gradient-border">
                <p class="text-white px-3 flex ">Ajouter un achat</p>
                <IconAddPurchase @click="toggleMenu('openNClose')"
                class="p-1 bg-gradient-blue rounded-md right-[100px] top-[50vh] cursor-pointer 
                z-10 shadow-black shadow-custom-main trigger-add-purchase" 
                :svg="svgCfgIconAdd"/>
            </div>
        </div>

        <TransitionOpacity :durationIn="'duration-300'" :durationOut="'duration-200'">
            <div v-if="isOverlayActive" class="fixed inset-0 bg-black bg-opacity-80 z-10"></div>
        </TransitionOpacity>

        <TransitionOpacity :durationIn="'duration-300'" :durationOut="'duration-200'">
            <div v-if="isOverlayActive" 
                :class="`fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-30 text-white rounded-[3px] overflow-hidden 
                shadow-black shadow-custom-main trigger-add-purchase bg-main-gradient ${props.width}`">

                <MainContainerSlot 
                    :textBtn1="'Annuler'" :textBtn2="'Ajouter'" :titleContainer="(!typeTransaction) ? 'Ajouter un achat' : 'Ajouter un prélèvement'" 
                    @toggleMenu="toggleMenu" 
                >
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
    import { ref, watch, computed, reactive, defineAsyncComponent } from 'vue';
    import { svgConfig } from '@/svg/svgConfig';
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
    const props = defineProps({
        width: { default: '' }
    });

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

    // icons
    const svgCfgIconAdd = svgConfig.mediumSmaller;

    // life cycle / functions
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