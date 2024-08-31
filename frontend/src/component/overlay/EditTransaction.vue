<template>
    <div class="fixed inset-0 bg-black bg-opacity-80 z-30">
        <div 
            v-if="isOverlayActive" 
            :class="`fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-30 text-white rounded-[3px] 
                 shadow-main trigger-edit-transaction bg-main-gradient
                max-[550px]:w-full sm:w-1/4 min-[550px]:min-w-[550px]`">

            <MainContainerSlot 
                :textBtn1="'Annuler'" :textBtn2="'Modifier'" :titleContainer="(!typeTransaction) ? 'Modifier achat' : 'Modifier prélèvement'" 
                @toggleMenu="toggleMenu" 
            >
                <div class="max-h-[72vh] overflow-y-auto">
                    <div>
                        <!-- inputs  -->
                        <ContainerInputs 
                        v-model:errorInputs="errorInputs"
                        v-model:mandatoryInputs="mandatoryInputs"
                        v-model:inputPriceVal="inputPriceVal" 
                        v-model:inputNoteVal="inputNoteVal" 
                        v-model:inputDateVal="inputDateVal" 
                        :infoTransaction="infoTransaction"/>
                        <!-- liste des catégories -->
                        <ContainerSelectCategories v-model:currentCategory="currentCategory" 
                        v-model:typeTransaction="typeTransaction" />              
                    </div>
                </div>
            </MainContainerSlot>
        </div>
    </div>
</template>


<script setup>
    // import
    import { ref, reactive, computed, watch, onMounted, defineAsyncComponent } from 'vue';
    import useClickOutside from '@/composable/useClickOutSide';
    import useEscapeKey from '@/composable/useEscapeKey';

    const ContainerInputs = defineAsyncComponent(() => import('@/component/container/ContainerInputs.vue'));
    const ContainerSelectCategories = defineAsyncComponent(() => import('@/component/container/ContainerSelectCategories.vue'));

    import MainContainerSlot from '@/component/containerSlot/MainContainerSlot.vue';
    import { storeDateSelected } from '@/storePinia/useStoreDashboard';
    import { updateTransaction } from '@/composable/useBackendActionData';
    import { updateAllDataTransations} from '@/storePinia/useUpdateStoreByBackend';
    import { formatDateForCurrentDay, formatDateForFirstDay, isCurrentMonth } from '@/composable/useGetDate';
    import { listPurchases, listRecurings } from '@/svg/listTransactionSvgs';
    import { isAnyMandatoryInputEmpty, isAnyInputError, TEXT_SUBMIT_ERROR } from '@/error/useHandleError';
    import { isValidCategory } from '@/error/useValidFormat';
    import { createToast } from '@/composable/useToastNotification';
    import { formatStringToFloat, formatFloatAsStringNoEspace } from '@/composable/useMath';
    

    // stores Pinia
    const dateSelected = storeDateSelected();

    // variables, props...
    const props = defineProps({
        indexMenu: {default: 0},
        infoTransaction: { default: {} }
    });

    // menu
    const isOverlayActive = defineModel('menuActive');    
    const typeTransaction = ref(getTypeTransaction()); 
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

    // life cycle / functions
    onMounted(() => {
        loadDataTransaction();
    });

    // detect change on month/year
    watch( () => [dateSelected.month, dateSelected.year], async () => {
        inputDateVal.value = formatDateInput();
    }, {  immediate:true, deep:true });
    // toggle show overlay
    watch(isOverlayActive, (newVal) => {
        if(newVal) loadDataTransaction();
    });
    // toggle button type transaction 
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

                if(isAnyMandatoryInputEmpty(allMandatoryValInputs)) {
                    activeErrorMandatInputs();
                    createToast(TEXT_SUBMIT_ERROR.MANDATORY_EMPTY_INPUTS, 'error');
                    return;
                }
                else if(isAnyInputError(allErrorsInputs)) {
                    return;
                }
                else if(!isValidCategory(nameCategory)) {
                    return;
                }
                createToast('Votre transaction a été éditée.', 'success');
                prepareUpdateTransaction();
                closeMenu();
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
        disableErrorMandatInputs();
    }

    function getCurrentNameCategory() {
        const listTransaction = (!typeTransaction.value) ? listPurchases : listRecurings;
        const currentIndex = currentCategory.value;
        const nameCategory = listTransaction[currentIndex].text;
        return nameCategory;
    }
    function loadDataTransaction() {
        if(!props.infoTransaction.transaction_id) return;
        inputPriceVal.value = formatFloatAsStringNoEspace(props.infoTransaction.transaction_amount);
        inputNoteVal.value = props.infoTransaction.transaction_note;
        inputDateVal.value = props.infoTransaction.transaction_date;
        typeTransaction.value = getTypeTransaction();
        currentCategory.value = getIndexCategory();
    }
    async function prepareUpdateTransaction() {
        const dataRequest = await updateTransaction({
            id: props.infoTransaction.transaction_id,
            amount: formatStringToFloat(inputPriceVal.value),
            trsCategory: getCurrentNameCategory(),
            trsType: getCurrentTransactionType(),
            date: inputDateVal.value,
            note: inputNoteVal.value
            
        });
        const isSuccessRequest = dataRequest?.isSuccessRequest;
        if(!isSuccessRequest) {
            return;
        }
        const nameTypeTrs = getCurrentTransactionType();
        const month = dateSelected.month;
        const year = dateSelected.year;
        await updateAllDataTransations(month, year, nameTypeTrs);
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

    function activeErrorMandatInputs() {
        if (!inputPriceVal.value) mandatoryInputs.inputPriceVal = true;
        if (!inputDateVal.value) mandatoryInputs.inputDateVal = true;
    }

    function disableErrorMandatInputs() {
        mandatoryInputs.inputPriceVal = false;
        mandatoryInputs.inputDateVal = false;
    }

    function getTypeTransaction() {
        return (props.infoTransaction.transaction_type === 'purchase') ? false : true;
    }

</script>