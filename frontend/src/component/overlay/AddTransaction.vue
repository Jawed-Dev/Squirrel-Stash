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
            <div v-show="isMenuActive" class="fixed inset-0 bg-black bg-opacity-80 z-10"></div>
        </TransitionOpacity>

        <TransitionOpacity :durationIn="'duration-300'" :durationOut="'duration-200'">
            <div v-show="isMenuActive" 
                :class="`fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-30 text-white rounded-[3px] overflow-hidden 
                shadow-black shadow-custom-main trigger-add-purchase bg-main-gradient ${props.width}`">

                <MainContainerSlot 
                    :textBtn1="'Annuler'" :textBtn2="'Ajouter'" :titleContainer="(!typeTransaction) ? 'Ajouter un achat' : 'Ajouter un prélèvement'" 
                    @toggleMenu="toggleMenu" 
                >
                    <!-- Errors  -->
                    <div class="relative">
                        <p class="p-3 absolute text-red-400">{{ computedFormatErrors }}</p>
                        <p v-if="computedEmptyInputs.length > 0"></p>
                    </div>
                    <div>
                        <!-- Inputs  -->
                        <ContainerInputs v-model:inputPriceVal="inputPriceVal" v-model:inputNoteVal="inputNoteVal" 
                        v-model:inputDateVal="inputDateVal" :typeTransaction="typeTransaction" />
                        <!-- Categories -->
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
    import { ref, watch, computed } from 'vue';
    import IconAddPurchase from '@/component/svgs/IconAddPurchase.vue';
    import TransitionOpacity from '@/component/transition/TransitionOpacity.vue';
    import { svgConfig } from '@/svg/svgConfig';
    import useClickOutside from '@/composable/useClickOutSide';
    import useEscapeKey from '@/composable/useEscapeKey';

    import ContainerSelectCategories from '../container/ContainerSelectCategories.vue';
    import ContainerInputs from '@/component/container/ContainerInputs.vue';
    import MainContainerSlot from '@/component/containerSlot/MainContainerSlot.vue';
    import { storeDateSelected } from '@/storePinia/useStoreDashboard';
    import { addTransaction } from '@/composable/useBackendActionData';
    import { updateAllDataTransations} from '@/storePinia/useUpdateStoreByBackend';
    import { formatDateForCurrentDay, formatDateForFirstDay, isCurrentMonth } from '@/composable/useGetDate';
    import { listCategories, listRecurings } from '@/svg/listTransactionSvgs';
    import { useErrorFormat, verifyAddTransaction } from '@/error/useHandleError';
    import { useMandatoryEmptyInputs } from '@/error/useMandatoryEmptyInputs';

    // stores Pinia
    const dateSelected = storeDateSelected();

    // variables, props...
    const props = defineProps({
        width: { default: '' }
    });

    // menu
    const isMenuActive = ref(false); 
    const typeTransaction = ref (false); 
    const currentCategory = ref(0);

    // input ref
    const inputNoteVal = ref('');
    const inputPriceVal = ref('');
    const inputDateVal = ref('');

    // Errors 
    const { computedEmptyInputs, stateEmptyInputs } = useMandatoryEmptyInputs([
        { name: 'inputPriceVal', ref: inputPriceVal }
    ]);
    const { stateFormatErrors, computedFormatErrors } = useErrorFormat(verifyAddTransaction, {
        trsAmount: {name: 'trsAmount', ref: inputPriceVal}, 
        date: {name: 'date', ref: inputDateVal},
        note: {name: 'note', ref: inputNoteVal},
        trsCategory: {name: 'trsCategory', ref: currentCategory},
        trsType: {name: 'trsType', ref: typeTransaction},
    });

    // icons
    const svgCfgIconAdd = svgConfig.mediumSmaller;

    // life cycle / functions
    watch( () => [dateSelected.month, dateSelected.year], async ([newMonth, newYear]) => {
        inputDateVal.value = formatDateInput();
    }, {  immediate:true, deep:true });

    watch(typeTransaction, (newVal, oldVal) => {
        currentCategory.value = 0;
    });

    useEscapeKey(isMenuActive, () => {
        closeMenu();
    });

    useClickOutside('.trigger-add-purchase', isMenuActive, () => {
        closeMenu();
    });

    async function toggleMenu(request) {
        switch(request) {
            case 'openNClose' : {
                typeTransaction.value = false;
                resetInputs();
                isMenuActive.value = !isMenuActive.value;
                break;
            }
            case 'valid' : {
                if(!isMenuActive.value) return;
                if(isAnyErrorActive()) return;
                //if(stateErrors.length > 0) return;
                prepareAddTransaction();
                resetInputs();
                isMenuActive.value = false;
                break;
            }
            case 'options' : {
                //alert('valider');
                break;
            }
            case 'cancel' : {
                resetInputs();
                isMenuActive.value = false;
                break;
            }
        }
    }
    
    function isAnyErrorActive() {
        return stateFormatErrors.value.length > 0 || stateEmptyInputs.value.length > 0;
    }

    function getCurrentTransactionType() {
        return (!typeTransaction.value) ? 'purchase' : 'recurring';
    }

    function getCurrentCategory() {
        const listTransaction = (!typeTransaction.value) ? listCategories : listRecurings;
        const currentIndex = currentCategory.value;
        console.log(listTransaction);
        const nameCategory = listTransaction[currentIndex].text;
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
        isMenuActive.value = false;
    }
    
    async function prepareAddTransaction() {
        const dataRequest = await addTransaction({
            amount: inputPriceVal.value,
            trsCategory: getCurrentCategory(),
            trsType: getCurrentTransactionType(),
            date: inputDateVal.value,
            note: inputNoteVal.value
        });
        //console.log(dataRequest);
        const isSuccessRequest = dataRequest?.isSuccessRequest;
        if(isSuccessRequest) {
            const nameTypeTrs = getCurrentTransactionType();
            const month = dateSelected.month;
            const year = dateSelected.year;
            updateAllDataTransations(month, year, nameTypeTrs);
        }
    }
</script>