<template>
    <div>
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
    import { ref, watch, onMounted } from 'vue';
    import TransitionOpacity from '@/components/transition/TransitionOpacity.vue';
    import { svgConfig } from '@/functions/svg/svgConfig';
    import useClickOutside from '@/composable/useClickOutSide';
    import useEscapeKey from '@/composable/useEscapeKey';

    import ContainerSelectCategories from '@/components/container/overlay/ContainerSelectCategories.vue';
    import ContainerInputs from '@/components/container/overlay/ContainerInputs.vue';
    import MainContainerSlot from '@/components/containerSlot/MainContainerSlot.vue';
    import { storeDateSelected } from '@/storePinia/useStoreDashboard';
    import { addTransaction } from '@/composable/useBackendSetData';
    import { updateAllDataTransations} from '@/storePinia/useUpdateStoreByBackend';
    import { formatDateForCurrentDay, formatDateForFirstDay, isCurrentMonth } from '@/composable/useGetDate';
    

    // stores Pinia
    const dateSelected = storeDateSelected();

    // variables, props...
    const props = defineProps({
        width: { default:'' },
        infoTransaction: {default: []}
    });

    // menu
    const isMenuActive = defineModel('menuActive');
    const typeTransaction = ref(false); 
    const currentCategory = ref(0);

    // input ref
    const inputNoteVal = ref('');
    const inputPriceVal = ref('');
    const inputDateVal = ref('');

    // icons
    const svgSmallWhite = svgConfig.mediumSmaller;

    const listCategories = [
        // {nameIcon:'',color:'bg-gradient-orange', text:'Choissiez une catégorie'},
        {nameIcon:'Alimentation',color:'bg-gradient-blue', text:'Alimentation'},
        {nameIcon:'Vestimentaire',color:'bg-gradient-blue', text:'Vestimentaire'},
        {nameIcon:'Famille',color:'bg-gradient-blue', text:'Famille'},
        {nameIcon:'Restaurant',color:'bg-gradient-blue', text:'Restaurant'},
        {nameIcon:'Loisir',color:'bg-gradient-blue', text:'Loisir'},
        {nameIcon:'Santé',color:'bg-gradient-blue', text:'Santé'},
        {nameIcon:'Transport',color:'bg-gradient-blue', text:'Transport'},
        {nameIcon:'Cadeau',color:'bg-gradient-blue', text:'Cadeau'},
        {nameIcon:'Autre',color:'bg-gradient-blue', text:'Autre'},
    ];

    const listRecurings = [
        // {nameIcon:'',color:'bg-gradient-orange', text:'Choissiez une catégorie'},
        {nameIcon:'Facture', color:'bg-gradient-vanusa', text:'Facture'},
        {nameIcon:'Loyer', color:'bg-gradient-vanusa', text:'Loyer'},
        {nameIcon:'Assurance', color:'bg-gradient-vanusa', text:'Assurance'},
        {nameIcon:'Crédit', color:'bg-gradient-vanusa', text:'Crédit'},
        {nameIcon:'Abonnement', color:'bg-gradient-vanusa', text:'Abonnement'},
        {nameIcon:'Autre', color:'bg-gradient-vanusa', text:'Autre'},
    ]

     
    // life cycle / functions

    onMounted(() => {
        inputPriceVal.value = props.infoTransaction.transaction_amount;
        inputNoteVal.value = props.infoTransaction.transaction_note;
        inputDateVal.value = props.infoTransaction.transaction_date;
        typeTransaction.value = (props.infoTransaction.transaction_category === 'purchase') ? false : true;
        const index = (typeTransaction.value === false) ? listCategories.findIndex(item => item.nameIcon === props.infoTransaction.transaction_name) : listRecurings.findIndex(item => item.nameIcon === props.infoTransaction.transaction_name);
        currentCategory.value = index;
    });

    watch( () => [dateSelected.month, dateSelected.year], async ([newMonth, newYear]) => {
        inputDateVal.value = formatDateInput();
    }, {  immediate:true, deep:true });


    async function toggleMenu(request) {
        switch(request) {
            case 'openNClose' : {
                resetInputs();
                isMenuActive.value = !isMenuActive.value;
                break;
            }
            case 'valid' : {
                if(!isMenuActive.value) return;

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
                    updateAllDataTransations(month, year, nameTypeTrs);
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

    useEscapeKey(isMenuActive, () => {
        isMenuActive.value = false;
    });

    useClickOutside('.trigger-add-purchase', isMenuActive, () => {
        isMenuActive.value = false;
        console.log('Add Purchase');
    });

    watch(typeTransaction, (newVal, oldVal) => {
        currentCategory.value = 0;
    });

    function getCurrentTypeTrsName() {
        return (!typeTransaction.value) ? 'purchase' : 'recurring';
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


    function getCurrentCategorySelect() {
        const listTransaction = (!typeTransaction.value) ? listCategories : listRecurings;
        const currentIndex = currentCategory.value;
        console.log(listTransaction);
        const nameCategory = listTransaction[currentIndex].text;
        return nameCategory;
    }
</script>