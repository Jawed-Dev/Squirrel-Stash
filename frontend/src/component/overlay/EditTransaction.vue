<template>
    <div class="fixed inset-0 bg-black bg-opacity-80 z-30">

        <div 
            v-if="isOverlayActive" 
            :class="`fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-10 text-white rounded-[3px] overflow-hidden 
            shadow-black shadow-custom-main trigger-edit-transaction bg-main-gradient ${props.width}`">

            <MainContainerSlot 
                :textBtn1="'Annuler'" :textBtn2="'Modifier'" :titleContainer="(!typeTransaction) ? 'Modifier achat' : 'Modifier prélèvement'" 
                @toggleMenu="toggleMenu" 
            >
                <!-- Errors -->
                <div class="relative">
                    <p class="text-sm font-light p-3 absolute text-red-300">{{ textError }}</p>
                </div>
                <div>
                    <!-- inputs  -->
                    <ContainerInputs 
                    v-model:errorInputs="errorInputs"
                    v-model:inputPriceVal="inputPriceVal" 
                    v-model:inputNoteVal="inputNoteVal" 
                    v-model:inputDateVal="inputDateVal" 
                    :infoTransaction="infoTransaction"/>
                    <!-- liste des catégories -->
                    <ContainerSelectCategories v-model:currentCategory="currentCategory" 
                    v-model:typeTransaction="typeTransaction" />              
                </div>
            </MainContainerSlot>
        </div>
    </div>
</template>


<script setup>
    // import
    import { ref, reactive, computed, watch, onMounted, defineAsyncComponent } from 'vue';
    import TransitionOpacity from '@/component/transition/TransitionOpacity.vue';
    import useClickOutside from '@/composable/useClickOutSide';
    import useEscapeKey from '@/composable/useEscapeKey';

    const OverlaySuccessAction = defineAsyncComponent(() => import('@/component/overlay/OverlaySuccessAction.vue'));
    const ContainerInputs = defineAsyncComponent(() => import('@/component/container/ContainerInputs.vue'));
    const ContainerSelectCategories = defineAsyncComponent(() => import('../container/ContainerSelectCategories.vue'));

    import MainContainerSlot from '@/component/containerSlot/MainContainerSlot.vue';
    import { storeDateSelected } from '@/storePinia/useStoreDashboard';
    import { updateTransaction } from '@/composable/useBackendActionData';
    import { updateAllDataTransations} from '@/storePinia/useUpdateStoreByBackend';
    import { formatDateForCurrentDay, formatDateForFirstDay, isCurrentMonth } from '@/composable/useGetDate';
    import { listPurchases, listRecurings } from '@/svg/listTransactionSvgs';
    import { isAnyMandatInputEmpty, isAnyInputError, TYPE_SUBMIT_ERROR, TEXT_SUBMIT_ERROR } from '@/error/useHandleError';
    import TransitionPopUp from '@/component/transition/TransitionPopUp.vue';
    import { isValidCategory } from '@/error/useValidFormat';

    // stores Pinia
    const dateSelected = storeDateSelected();

    // variables, props...
    const props = defineProps({
        width: { default:'' },
        indexMenu: {default: 0},
        infoTransaction: { default: {} }
    });

    // menu
    const isOverlayActive = defineModel('menuActive');    
    const typeTransaction = ref(false); 
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
    const isSuccessEdit = defineModel('isSuccessEdit');

    // life cycle / functions
    const textError = computed(() => {
        if(submitError.value === TYPE_SUBMIT_ERROR.MANDATORY_EMPTY_INPUTS) return TEXT_SUBMIT_ERROR.MANDATORY_EMPTY_INPUTS;
        else if(submitError.value === TYPE_SUBMIT_ERROR.NOT_SUCCESS_REQUEST) return "La requête a échoué.";
        else if(submitError.value === TYPE_SUBMIT_ERROR.CATEGORY_ERROR) return TEXT_SUBMIT_ERROR.CATEGORY_ERROR;
        else if(submitError.value === TYPE_SUBMIT_ERROR.DATE_EMPTY) return TEXT_SUBMIT_ERROR.DATE_EMPTY;
    });

    onMounted(() => {
        loadDataTransaction();
    });

    watch( () => [dateSelected.month, dateSelected.year], async ([newMonth, newYear]) => {
        inputDateVal.value = formatDateInput();
    }, {  immediate:true, deep:true });

    watch(isOverlayActive, (newVal) => {
        submitError.value = null;
        if(newVal) loadDataTransaction();
    });

    watch(typeTransaction, () => {
        currentCategory.value = 0;
    });

    async function toggleMenu(request) {
        switch(request) {
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
                prepareUpdateTransaction();
                
                //closeMenu();
                break;
            }
            case 'cancel' : {
                closeMenu();
                break;
            }
        }
    }
    useEscapeKey(isOverlayActive, () => {
        closeMenu();
    });
    useClickOutside('.trigger-edit-transaction', isOverlayActive, () => {
        closeMenu();
    });
    function getCurrentTransactionType() {
        return (!typeTransaction.value) ? 'purchase' : 'recurring';
    }
    function formatDateInput() {
        const month = dateSelected.month;
        const year = dateSelected.year;
        return (isCurrentMonth(month, year)) ? formatDateForCurrentDay(month, year) : formatDateForFirstDay(month, year);
    }
    function closeMenu() {
        isOverlayActive.value = false;
    }

    function getCurrentNameCategory() {
        const listTransaction = (!typeTransaction.value) ? listPurchases : listRecurings;
        const currentIndex = currentCategory.value;
        const nameCategory = listTransaction[currentIndex].text;
        return nameCategory;
    }
    function loadDataTransaction() {
        if(!props.infoTransaction.transaction_id) return;
        inputPriceVal.value = props.infoTransaction.transaction_amount;
        inputNoteVal.value = props.infoTransaction.transaction_note;
        inputDateVal.value = props.infoTransaction.transaction_date;
        typeTransaction.value = (props.infoTransaction.transaction_type === 'purchase') ? false : true;
        currentCategory.value = getIndexCategory();
    }
    async function prepareUpdateTransaction() {
        const dataRequest = await updateTransaction({
            id: props.infoTransaction.transaction_id,
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
        await updateAllDataTransations(month, year, nameTypeTrs);
        submitError.value = null;
        isSuccessEdit.value = true;
        closeMenu();
    }

    function getIndexCategory() {
        let index = 0;
        if(!typeTransaction.value) index = listPurchases.findIndex(item => item.text === props.infoTransaction.transaction_category);
        else index = listRecurings.findIndex(item => item.text === props.infoTransaction.transaction_category);
        return index;
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