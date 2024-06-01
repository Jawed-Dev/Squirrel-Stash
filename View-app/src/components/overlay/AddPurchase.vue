<template>
    <div>
        <IconAddPurchase @click="toggleMenu('openNClose')"
            class="p-2 bg-gradient-blue rounded-md right-[100px] top-[50vh] cursor-pointer z-20
                shadow-black shadow-custom-main trigger-class-AddPurchase" 
        :svg="svgSmallWhite" 
        />

        <TransitionOpacity :durationIn="'duration-500'" :durationOut="'duration-500'">
            <div v-show="ismenuAddActive" class="fixed inset-0 bg-black bg-opacity-80 z-10"></div>
        </TransitionOpacity>
        
        <TransitionOpacity :durationIn="'duration-500'" :durationOut="'duration-500'">
            <div v-show="ismenuAddActive" 
                class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-10 text-white rounded-[3px] overflow-hidden 
                shadow-black shadow-custom-main trigger-class-AddPurchase bg-gradient-joomla">
                <MainContainerSlot :width="'300px'" :paddingY="'py-[5px]'" :textBtn1="'Annuler'" :textBtn2="'Ajouter'" :titleContainer="(!typeTransaction) ? 'Catégorie d\'achat' : 'Catégories de prélèvement'" 
                    @toggleMenu="toggleMenu" :isIconActive="true" :currentList="(!typeTransaction) ? listCategories[currentCategory] : listRecurings[currentCategory]"
                >
                    <div class="bg-main-gradient gradient-border-x">
                        <!-- inputs  -->
                        <ContainerInputs v-model:inputPriceVal="inputPriceVal" v-model:inputNoteVal="inputNoteVal" />
                        <!-- liste des catégories -->
                        <ContainerSelectCategories v-model:currentCategory="currentCategory" v-model:typeTransaction="typeTransaction" :listCategories="listCategories" :listRecurings="listRecurings" />              
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
    import TransitionOpacity from '../transition/TransitionOpacity.vue';
    import { svgConfig } from '@/functions/svg/svgConfig';
    import useClickOutside from '@/composables/useClickOutSide';

    import ContainerSelectCategories from '../container/overlayAddPurchase/ContainerSelectCategories.vue';
    import ContainerInputs from '../container/overlayAddPurchase/ContainerInputs.vue';
    import MainContainerSlot from '../containerSlot/MainContainerSlot.vue';

    // variables, props...

    // menu
    const ismenuAddActive = ref(false); 
    const typeTransaction = ref (false); // menu state if purchase or reccuring
    const currentCategory = ref(0);

    // input ref
    const inputNoteVal = ref('');
    const inputPriceVal = ref('');

    // icons
    const svgSmallWhite = svgConfig.setColorDynamic(svgConfig.medium, 'white');

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
    ];

    const listRecurings = [
        // {nameIcon:'',color:'bg-gradient-orange', text:'Choissiez une catégorie'},
        {nameIcon:'receipt', color:'bg-gradient-vanusa', text:'Facture'},
        {nameIcon:'mobile', color:'bg-gradient-vanusa', text:'Abonnement'},
        {nameIcon:'car', color:'bg-gradient-vanusa', text:'Assurance'},
        {nameIcon:'house', color:'bg-gradient-vanusa', text:'Loyer'},
        {nameIcon:'billet', color:'bg-gradient-vanusa', text:'Crédit'},
    ]

     
    // functions
    useClickOutside('.trigger-class-AddPurchase', ismenuAddActive, () => {
        //alert('test');
        ismenuAddActive.value = false;
        console.log('Add Purchase');
    });

    watch(typeTransaction, (newVal, oldVal) => {
        currentCategory.value = 0;
    });

    function toggleMenu(request) {
        switch(request) {
            case 'openNClose' : {
                inputNoteVal.value = '';
                inputPriceVal.value = '';
                ismenuAddActive.value = !ismenuAddActive.value;
                break;
            }
            case 'accept' : {
                //alert('valider');
                //alert(`${inputPriceVal.value}`);
                break;
            }
            case 'options' : {
                //alert('valider');
                break;
            }
            case 'cancel' : {
                ismenuAddActive.value = false;
                break;
            }
        }
    }
</script>