<template>
    <div>
        <TransitionOpacity :durationIn="'duration-500'" :durationOut="'duration-500'">
            <div v-show="isMenuActive" class="fixed inset-0 bg-black bg-opacity-80 z-10"></div>
        </TransitionOpacity>

        <TransitionOpacity :durationIn="'duration-500'" :durationOut="'duration-500'">
            <div v-show="isMenuActive" 
                :class="`fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-10 text-white rounded-[3px] overflow-hidden 
                shadow-black shadow-custom-main trigger-edit-transaction bg-main-gradient ${props.width}`">

                <MainContainerSlot 
                    :textBtn1="'Annuler'" :textBtn2="'Modifier'" :titleContainer="(!typeTransaction) ? 'Modifier achat' : 'Modifier prélèvement'" 
                    @toggleMenu="toggleMenu" 
                >
                    <!-- Errors  -->
                    <div class="relative">
                        <p class="p-3 absolute text-red-400">{{ computedFormatErrors }}</p>
                        <p v-if="computedEmptyInputs.length > 0"></p>
                    </div>

                    <div>
                        <!-- inputs  -->
                        <ContainerInputs v-model:inputPriceVal="inputPriceVal" v-model:inputNoteVal="inputNoteVal" v-model:inputDateVal="inputDateVal" />
                        <!-- liste des catégories -->
                        <ContainerSelectCategories v-model:currentCategory="currentCategory" 
                        v-model:typeTransaction="typeTransaction" />              
                    </div>
                </MainContainerSlot>
            </div>
        </TransitionOpacity>
    </div>
</template>


<script setup>
    // import
    import { ref, watch, onMounted, computed } from 'vue';
    import TransitionOpacity from '@/component/transition/TransitionOpacity.vue';
    import useClickOutside from '@/composable/useClickOutSide';
    import useEscapeKey from '@/composable/useEscapeKey';

    import ContainerSelectCategories from '@/component/container/ContainerSelectCategories.vue';
    import ContainerInputs from '@/component/container/ContainerInputs.vue';
    import MainContainerSlot from '@/component/containerSlot/MainContainerSlot.vue';
    import { storeDateSelected } from '@/storePinia/useStoreDashboard';
    import { updateTransaction } from '@/composable/useBackendSetData';
    import { updateAllDataTransations} from '@/storePinia/useUpdateStoreByBackend';
    import { formatDateForCurrentDay, formatDateForFirstDay, isCurrentMonth } from '@/composable/useGetDate';
    import { listCategories, listRecurings } from '@/svg/listTransactionSvgs';
    import { useErrorFormat, verifyEditTransaction } from '@/error/useHandleError';
    import { useMandatoryEmptyInputs } from '@/error/useMandatoryEmptyInputs';

    // stores Pinia
    const dateSelected = storeDateSelected();

    // variables, props...
    const props = defineProps({
        width: { default:'' },
        indexMenu: {default: 0},
        infoTransaction: { default: {} }
    });
    const isDataLoaded = ref(false);

    // menu
    const isMenuActive = defineModel('menuActive');
    const typeTransaction = ref(false); 
    const currentCategory = ref(0);

    // input ref
    const inputNoteVal = ref('');
    const inputPriceVal = ref('');
    const inputDateVal = ref('');

    // Errors 
    const { computedEmptyInputs, stateEmptyInputs } = useMandatoryEmptyInputs([
        { name: 'inputPriceVal', ref: inputPriceVal }
    ]);
    const { stateFormatErrors, computedFormatErrors } = useErrorFormat(verifyEditTransaction, {
        trsAmount: {name: 'trsAmount', ref: inputPriceVal}, 
        date: {name: 'date', ref: inputDateVal},
        note: {name: 'note', ref: inputNoteVal},
        trsCategory: {name: 'trsCategory', ref: currentCategory},
        trsType: {name: 'trsType', ref: typeTransaction},
    });
     
    // life cycle / functions
    onMounted(() => {
        loadDataTransaction();
        isDataLoaded.value = true;
    });

    watch( () => [dateSelected.month, dateSelected.year], async ([newMonth, newYear]) => {
        inputDateVal.value = formatDateInput();
    }, {  immediate:true, deep:true });

    watch(isMenuActive, (newVal) => {
        if(newVal) {
            isDataLoaded.value = false;
            loadDataTransaction();
        }
    });

    watch(typeTransaction, (newVal, oldVal) => {
        if(isDataLoaded.value) currentCategory.value = 0;
         //alert('test');
    });

    function isAnyErrorActive() {
        return stateFormatErrors.value.length > 0 || stateEmptyInputs.value.length > 0;
    }

    async function toggleMenu(request) {
        switch(request) {
            case 'valid' : {
                if(isAnyErrorActive()) return;
                if(!isMenuActive.value) return;
                prepareUpdateTransaction();
                isMenuActive.value = false;
                resetInputs();
                break;
            }
            case 'cancel' : {
                closeMenu();
                resetInputs();
                break;
            }
        }
    }
    useEscapeKey(isMenuActive, () => {
        closeMenu();
    });
    useClickOutside('.trigger-edit-transaction', isMenuActive, () => {
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
        isMenuActive.value = false;
        typeTransaction.value = false;
    }
    function resetInputs() {
        if(!isDataLoaded) return;
        inputNoteVal.value = '';
        inputPriceVal.value = '';
        inputDateVal.value = formatDateInput();
    }
    function getCurrentCategory() {
        const listTransaction = (!typeTransaction.value) ? listCategories : listRecurings;
        const currentIndex = currentCategory.value;
        const nameCategory = listTransaction[currentIndex].nameIcon;
        return nameCategory;
    }
    function loadDataTransaction() {
        if(! props.infoTransaction.transaction_id) return;
        isDataLoaded.value = false;
        inputPriceVal.value = props.infoTransaction.transaction_amount;
        inputNoteVal.value = props.infoTransaction.transaction_note;
        inputDateVal.value = props.infoTransaction.transaction_date;
        typeTransaction.value = (props.infoTransaction.transaction_type === 'purchase') ? false : true;
        
        let index = 0;
        if(!typeTransaction.value) {
            index = listCategories.findIndex(item => item.nameIcon === props.infoTransaction.transaction_category);
        }
        else {
            index = listRecurings.findIndex(item => item.nameIcon === props.infoTransaction.transaction_category);
        }
        currentCategory.value = index;
        isDataLoaded.value = true;
    }
    async function prepareUpdateTransaction() {
        const dataRequest = await updateTransaction({
            id: props.infoTransaction.transaction_id,
            amount: inputPriceVal.value,
            trsCategory: getCurrentCategory(),
            trsType: getCurrentTransactionType(),
            date: inputDateVal.value,
            note: inputNoteVal.value
            
        });
        const isSuccessRequest = dataRequest?.isSuccessRequest;
        if(isSuccessRequest) {
            const nameTypeTrs = getCurrentTransactionType();
            const month = dateSelected.month;
            const year = dateSelected.year;
            updateAllDataTransations(month, year, nameTypeTrs);
        }
        
    }
</script>