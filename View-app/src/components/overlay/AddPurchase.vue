<template>
    <div>
        <div class="rounded-[3px] overflow-hidden shadow-black shadow-custom-main ">
            <div class="bg-main-gradient w-[230px] flex justify-around items-center p-1 gradient-border">
                <p class="text-white px-3 flex ">Ajouter un achat</p>
                <IconAddPurchase @click="toggleMenu('openNClose')"
                class="p-1 bg-gradient-blue rounded-md right-[100px] top-[50vh] cursor-pointer z-10 shadow-black shadow-custom-main trigger-add-purchase" 
                :svg="svgSmallWhite"/>
            </div>
        </div>

        <TransitionOpacity :durationIn="'duration-500'" :durationOut="'duration-500'">
            <div v-show="isMenuActive" class="fixed inset-0 bg-black bg-opacity-80 z-10"></div>
        </TransitionOpacity>

        <TransitionOpacity :durationIn="'duration-500'" :durationOut="'duration-500'">
            <div v-show="isMenuActive" 
                :class="`fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-10 text-white rounded-[3px] overflow-hidden 
                shadow-black shadow-custom-main trigger-add-purchase bg-main-gradient ${props.width}`">

                <MainContainerSlot 
                    :textBtn1="'Annuler'" :textBtn2="'Ajouter'" :titleContainer="(!typeTransaction) ? 'Catégorie d\'achat' : 'Catégories de prélèvement'" 
                    @toggleMenu="toggleMenu" 
                >
                    <div>
                        <!-- inputs  -->
                        <ContainerInputs v-model:inputPriceVal="inputPriceVal" v-model:inputNoteVal="inputNoteVal" v-model:inputDateVal="inputDateVal" />
                        <!-- liste des catégories -->
                        <ContainerSelectCategories v-model:currentCategory="currentCategory" 
                        v-model:typeTransaction="typeTransaction" :listCategories="listCategories" :listRecurings="listRecurings" />              
                    </div>
                </MainContainerSlot>
            </div>
        </TransitionOpacity>
    </div>
</template>


<script setup>
    // import
    import { ref, watch } from 'vue';
    import IconAddPurchase from '@/components/svgs/IconAddPurchase.vue';
    import TransitionOpacity from '@/components/transition/TransitionOpacity.vue';
    import { svgConfig } from '@/functions/svg/svgConfig';
    import useClickOutside from '@/composable/useClickOutSide';
    import useEscapeKey from '@/composable/useEscapeKey';

    import ContainerSelectCategories from '../container/overlay/ContainerSelectCategories.vue';
    import ContainerInputs from '@/components/container/overlay/ContainerInputs.vue';
    import MainContainerSlot from '@/components/containerSlot/MainContainerSlot.vue';
    import { storeDateSelected } from '@/storePinia/useStoreDashboard';
    import { addTransaction } from '@/composable/useBackendSetData';
import { updateBalanceEcoByMonth, updateBiggestTrsByMonth, updateLastNTrsByMonth, updateListTrsMonthByDay, updateTotalTrsByMonth } from '@/storePinia/useUpdateStoreByBackend';


    // stores Pinia
    const dateSelected = storeDateSelected();

    // variables, props...
    const props = defineProps({
        width: { default:'' }
    });

    // menu
    const isMenuActive = ref(false); 
    const typeTransaction = ref (false); // menu state if purchase or reccuring
    const currentCategory = ref(0);

    // input ref
    const inputNoteVal = ref('');
    const inputPriceVal = ref('');
    const inputDateVal = ref('');

    // icons
    const svgSmallWhite = svgConfig.mediumSmaller;

    const listCategories = [
        // {nameIcon:'',color:'bg-gradient-orange', text:'Choissiez une catégorie'},
        {nameIcon:'restaurant',color:'bg-gradient-blue', text:'Restaurant'},
        {nameIcon:'clothes',color:'bg-gradient-blue', text:'Vestimentaire'},
        {nameIcon:'family',color:'bg-gradient-blue', text:'Famille'},
        {nameIcon:'hobbie',color:'bg-gradient-blue', text:'Loisir'},
        {nameIcon:'health',color:'bg-gradient-blue', text:'Santé'},
        {nameIcon:'food',color:'bg-gradient-blue', text:'Alimentation'},
        {nameIcon:'transport',color:'bg-gradient-blue', text:'Transport'},
        {nameIcon:'gift',color:'bg-gradient-blue', text:'Cadeau'},
        {nameIcon:'questionMark',color:'bg-gradient-blue', text:'Autre'},
    ];

    const listRecurings = [
        // {nameIcon:'',color:'bg-gradient-orange', text:'Choissiez une catégorie'},
        {nameIcon:'receipt', color:'bg-gradient-vanusa', text:'Facture'},
        {nameIcon:'mobile', color:'bg-gradient-vanusa', text:'Abonnement'},
        {nameIcon:'car', color:'bg-gradient-vanusa', text:'Assurance'},
        {nameIcon:'house', color:'bg-gradient-vanusa', text:'Loyer'},
        {nameIcon:'billet', color:'bg-gradient-vanusa', text:'Crédit'},
        {nameIcon:'questionMark', color:'bg-gradient-vanusa', text:'Autre'},
    ]

     
    // life cycle / functions

    watch( () => [dateSelected.month, dateSelected.year], async ([newMonth, newYear]) => {
        // reset Date for format input
        inputDateVal.value = `${newYear}-${newMonth.toString().padStart(2, '0')}-01`;
    }, {  immediate:true, deep:true });


    useEscapeKey(isMenuActive, () => {
        isMenuActive.value = false;
    });

    useClickOutside('.trigger-add-purchase', isMenuActive, () => {
        //alert('test');
        isMenuActive.value = false;
        console.log('Add Purchase');
    });

    watch(typeTransaction, (newVal, oldVal) => {
        currentCategory.value = 0;
    });

    function getCurrentTypeTrsName() {
        return (!typeTransaction.value) ? 'purchase' : 'recurring';
    }

    function resetInputs() {
        inputNoteVal.value = '';
        inputPriceVal.value = '';
        currentCategory.value = 0;
        currentCategory.value = 0;
        inputDateVal.value = `${dateSelected.year}-${dateSelected.month.toString().padStart(2, '0')}-01`;
    }


    function getCurrentCategorySelect() {
        const listTransaction = (!typeTransaction.value) ? listCategories : listRecurings;
        const currentIndex = currentCategory.value;
        console.log(listTransaction);
        const nameCategory = listTransaction[currentIndex].text;
        return nameCategory;
    }

    async function toggleMenu(request) {
        switch(request) {
            case 'openNClose' : {
                resetInputs();
                isMenuActive.value = !isMenuActive.value;
                break;
            }
            case 'valid' : {
                console.log(getCurrentCategorySelect());
                const dataRequest = await addTransaction({
                    amount: inputPriceVal.value,
                    trsName: getCurrentCategorySelect(),
                    category: getCurrentTypeTrsName(),
                    date: inputDateVal.value,
                    note: inputNoteVal.value
                });
                console.log(dataRequest);
                const isSuccessRequest = dataRequest?.isSuccessRequest;
                if(isSuccessRequest) {
                    const nameTypeTrs = getCurrentTypeTrsName();
                    const month = dateSelected.month;
                    const year = dateSelected.year;

                    updateBiggestTrsByMonth(month, year, nameTypeTrs);
                    updateListTrsMonthByDay(month, year, nameTypeTrs);
                    updateBalanceEcoByMonth(month, year);
                    updateTotalTrsByMonth(month, year);
                    updateLastNTrsByMonth(month, year, nameTypeTrs);
                }
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
</script>