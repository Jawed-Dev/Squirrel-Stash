<template>
    <div>
        <IconAddPurchase @click="toggleMenu('openNClose')"
            class="p-2 bg-gradient-blue rounded-full fixed right-[100px] top-[50vh] cursor-pointer z-20
                shadow-black shadow-custom-main trigger-class-AddPurchase" 
        :svg="svgConfig2" 
        />

        <TransitionOpacity :durationIn="'duration-500'" :durationOut="'duration-500'">
            <div v-show="ismenuAddActive" class="fixed inset-0 bg-black bg-opacity-80 z-10"></div>
        </TransitionOpacity>
        
        <TransitionOpacity :durationIn="'duration-500'" :durationOut="'duration-500'">
            <!-- top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 -->
            <div v-show="ismenuAddActive" 
                class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-10 text-white rounded-[3px] overflow-hidden 
                shadow-black shadow-custom-main trigger-class-AddPurchase bg-gradient-joomla">

                <div>
                    <!-- 1 premières sections (catégorie monnaie, catégorie achat) bg-gradient-green -->
                    <div class="flex">
                        <!-- 1er élement -->
                        <div class="flex justify-around items-center w-[100%] shadow-black shadow-custom-main">
                            <div class="pr-3">
                                <p class="w-[600px] text-center text-[22px] pt-3 font-extralight">{{ (stateMenuTab) ? "Catégorie d'achat" : "Catégories de récurence"}}</p>
                                <div class="flex items-center justify-center"> 
                                    <p class="text-[18px] font-medium">{{ stateMenuTab ? listCategories[idCatSelected].text : listRecurings[idCatSelected].text}}</p>
    
                                    <IconDynamic :class="`shadow-black shadow-custom-main p-2 rounded-full m-4 mr-[20px] 
                                    ${(stateMenuTab) ? listCategories[idCatSelected].color : listRecurings[idCatSelected].color }`"
                                    :svg="svgMediumGreen" 
                                    :nameIcon="(stateMenuTab) ? listCategories[idCatSelected].nameIcon : listRecurings[idCatSelected].nameIcon "/>
                                </div>
                            </div>
                        </div>
                    </div>
    
                    <!-- 2 autres éléments (dépense, valeur) -->
    
                    <div class="bg-main-gradient">
                        <div class="flex ">
                            <!-- dépenses valeur -->
                            <div class="flex flex-col w-[100%] border-b px-1 py-5">
                                <div class="w-full">
                                    <p class="flex justify-center text-[22px] font-light ">Montant en €</p>
                                    <div class="flex justify-center mt-2">
                                        <inputForm v-model="inputPriceVal" extraClass="shadow-black shadow-custom-main w-[150px] flex items-center font-extralight text-[17px] bg-transparent border-white border rounded-[3px] text-center w-[100px] focus:outline-none" placeholder="Montant"/>
                                            
                                        </input>
                                    </div>
                                </div>
                                <div class="flex gap-3 items-center justify-center pt-10">
                                    <div class="flex gap-2 items-center">
                                        <IconWrite :svg="svgConfig4"/>
                                    </div>
                                    
                                    <!-- placeholder notes -->
                                    <div class="font-extralight text-[17px]">
                                        <inputForm v-model="inputNoteVal" extraClass="shadow-black shadow-custom-main flex items-center text-[15px] bg-transparent border-white border rounded-[3px] w-[500px] pl-1 focus:outline-none" placeholder="Note"/>
                                        <!-- <input class="bg-transparent w-[500px] placeholder:text-white pl-1" placeholder="..." type="text">       -->
                                    </div>
                                </div>
        
                            </div>
                            
                        </div>
        
                        <!-- annuler, liste des catégories, bouttons paiement, réccurence -->
                        <div class="w-full py-5 px-2">
                            <div class="flex justify-between">
                                <ToggleButton v-model="stateMenuTab" :text1="'Achat'" :text2="'Récurence'" />
                            </div>
        
                            <!-- liste des catégories -->
                
                            <h2 class="text-center pt-10 text-[20px]">Sélectionnez la catégorie</h2>
                            <div class="w-[600px] flex flex-wrap py-5">
                                <div v-for="(icon, index) in (stateMenuTab ? listCategories : listRecurings)" :key="index" :class="`flex flex-col items-center w-[150px] ${translateY} will-change-transform`" >
                                    <div @click="handleSelectCategory(index)" :class="`mb-3 cursor-pointer py-3`">
                                        <IconDynamic :nameIcon="icon.nameIcon" 
                                        :class="`${icon.color} rounded-full p-4 m-4 mr-[20px] shadow-black shadow-custom-main`" :svg="dynamicIconCfg"/>
                                        <p class=" text-[14px] flex justify-center">{{ icon.text }}</p>
                                    </div>
                                </div>
                            </div>
                        
                        </div>
        
                        <div class="flex">
                            <!-- boutton valider -->
                            <div class="w-[50%]">
                                <button @click="toggleMenu('cancel')" class="w-full bg-gradient-namn h-full py-3">Annuler</button>
                            </div>
                            <!-- boutton Annuler -->
                            <div class="w-[50%]">
                                <button @click="toggleMenu('accept')" class="w-full bg-gradient-blue h-full py-2">Valider</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            
        </TransitionOpacity>
    </div>
</template>


<script setup>

    // import
    import { ref, onMounted, onBeforeUnmount, shallowRef, watch } from 'vue';
    import IconCash from '@/components/svgs/IconCash.vue';
    import IconAddPurchase from '@/components/svgs/IconAddPurchase.vue';
    import IconPreferences from '../svgs/IconPreferences.vue';
    import TransitionOpacity from '../transition/TransitionOpacity.vue';
    import IconWrite from '../svgs/IconWrite.vue';
    import ToggleButton from '../button/ToggleButton.vue';
    import IconDynamic from '../icons/nav/IconDynamic.vue';
    import { svgConfig } from '@/functions/svg/svgConfig';
    import { classTransitionHover } from '../transition/classTransitionHover';

    import Options from './Options.vue';

    import useClickOutside from '@/composables/useClickOutSide';
    import inputForm from '../input/inputForm.vue';

    const translateY = classTransitionHover('translateY');

    // variables, props...

    // menus ref
    const ismenuAddActive = ref(false); 
    const stateMenuTab = ref (true);
    const idCatSelected = ref(0);

    // input ref
    const inputNoteVal = ref('');
    const inputPriceVal = ref('');

    // icons
    const svgMediumGreen = svgConfig.setColorDynamic(svgConfig.mediumSmaller, 'bg-gradient-blue');
    const svgSmallWhite = svgConfig.setColorDynamic(svgConfig.medium, 'white');

    const dynamicIconCfg = svgConfig.setColorDynamic(svgConfig.largeIcon, 'bg-gradient-blue');

    watch(stateMenuTab, (newVal, oldVal) => {
        idCatSelected.value = 0;
    });

    const svgConfig2 =  {
        width: '70px',
        height: '70px',
        fill: 'white',
        color: 'bg-gradient-orange'
    }

    const svgConfig3 =  {
        width: '100px',
        height: '100px',
        fill: 'white'
    }

    const svgConfig4 =  {
        width: '15px',
        height: '15px',
        fill: 'white',
        color: 'bg-gradient-orange'
    }

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

    useClickOutside(() => {
        ismenuAddActive.value = false;
    }, '.trigger-class-AddPurchase');


     // cycle de vie

     
    // function

    function handleSelectCategory(idCategory) {
        //alert(idCategory);
        idCatSelected.value = idCategory;
        //alert(listCategories[idCatSelected.value].name);
    }

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
                //alert(`${inputNoteVal.value}`);
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