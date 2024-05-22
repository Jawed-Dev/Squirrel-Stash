<template>
    <div>
        <IconAddPurchase @click="toggleMenu('openNClose')"
            class="p-2 bg-gradient-blue rounded-full fixed right-[100px] top-[50vh] cursor-pointer z-20
                    shadow-black shadow-custom-test trigger-class-AddPurchase" 
            :svg="svgConfig2" 
        />

        <TransitionOpacity :durationIn="'duration-500'" :durationOut="'duration-500'">
            <div v-show="ismenuAddActive" class="fixed inset-0 bg-black bg-opacity-80 z-10"></div>
        </TransitionOpacity>
        
        <TransitionOpacity :durationIn="'duration-500'" :durationOut="'duration-500'">
            <!-- top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 -->
            <div v-show="ismenuAddActive" 
                class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-10 text-white rounded-md overflow-hidden 
                shadow-black shadow-custom-test trigger-class-AddPurchase">

                <!-- 1 premières sections (catégorie monnaie, catégorie achat) -->
                <div class="flex">
                    <!-- 1er élement -->
                    <div class="flex justify-around items-center bg-gradient-blue p-3 w-[100%] shadow-black shadow-custom-test">
                        <div class="pr-3">
                            <p class="text-[16px] font-light">{{ (stateMenuTab) ? "Catégorie d'achats" : "Catégories de récurence"}}</p>
                            <div class="flex items-center"> 
                                <p class="text-[20px] font-medium">{{ stateMenuTab ? listCategories[idCatSelected].text : listRecurings[idCatSelected].text}}</p>

                                <IconDynamic :class="`rounded-full m-4 shadow-black shadow-custom-main mr-[20px] 
                                ${(stateMenuTab) ? listCategories[idCatSelected].color : listRecurings[idCatSelected].color }`"
                                :svg="mainIconCfg" 
                                :nameIcon="(stateMenuTab) ? listCategories[idCatSelected].nameIcon : listRecurings[idCatSelected].nameIcon "/>
                            </div>
                        </div>
                        <div class="flex flex-col gap-3">
                            <!-- <Options /> -->
                            <IconCash class="w-[100%]" :svg="svgConfig3" />
                        </div>
                    </div>
                </div>

                <!-- 2 autres éléments (dépense, valeur) -->

                <div class="bg-main-gradient">
                    <div class="flex">
                        <!-- dépenses valeur -->
                        <div class="flex flex-col w-[100%] border-b px-1 py-5">
                            <div class="w-full">
                                <p class="flex justify-center text-[25px] font-light ">Dépense</p>
                                <div class="flex justify-center mt-2">
                                    <inputForm v-model="inputPriceVal" extraClass="flex items-center text-[20px] bg-transparent text-center w-[100px] placeholder:text-white placeholder:pl-7 focus:outline-none" :placeholder="'...'"/>
                                        <p class="font-normal text-[20px]">€</p>
                                    </input>
                                </div>
                            </div>
                            <div class="flex gap-3 items-center pt-2">
                                <div class="flex gap-2 items-center">
                                    <p>Notes:</p>
                                    <IconWrite :svg="svgConfig4"/>
                                </div>
                                
                                <!-- placeholder notes -->
                                <div class="pt- font-light text-[17px]">
                                    <inputForm v-model="inputNoteVal" extraClass="flex items-center text-[17px] bg-transparent m w-[500px] placeholder:text-white pl-1 focus:outline-none" placeholder="..."/>
                                    <!-- <input class="bg-transparent w-[500px] placeholder:text-white pl-1" placeholder="......." type="text">       -->
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
                        <div class="w-[600px] flex flex-wrap py-5 justify-center">
                            <div v-for="(icon, index) in (stateMenuTab ? listCategories : listRecurings)" :key="index" class="flex flex-col items-center w-[150px]" >
                                <div @click="handleSelectCategory(index)" :class="`mb-3 cursor-pointer py-3 text-center ${idCatSelected === index ? 'border-custom-blue border rounded-lg' : ''}`">
                                    <IconDynamic class="" :nameIcon="icon.nameIcon" 
                                    :class="`${icon.color} rounded-full p-3 m-4 shadow-black shadow-custom-main mr-[20px]`" :svg="dynamicIconCfg"/>
                                    <p  class="text-[14px]">{{ icon.text }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
    
                    <div class="flex">
                        <!-- boutton valider -->
                        <div class="w-[50%]">
                            <button @click="toggleMenu('cancel')" class="w-full bg-gradient-red h-full py-3">Annuler</button>
                        </div>
                        <!-- boutton Annuler -->
                        <div class="w-[50%]">
                            <button @click="toggleMenu('accept')" class="w-full bg-gradient-green h-full py-2">Valider</button>
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

    import Options from './Options.vue';

    import useClickOutside from '@/composables/useClickOutSide';
    import inputForm from '../input/inputForm.vue';

   

    // variables, props...

    // menus ref
    const ismenuAddActive = ref(false); 
    const stateMenuTab = ref (true);
    const idCatSelected = ref(0);

    // input ref
    const inputNoteVal = ref('');
    const inputPriceVal = ref('');

    // icons
    const mainIconCfg = svgConfig.setColorDynamic(svgConfig.smallIcon, 'bg-gradient-orange');
    const dynamicIconCfg = svgConfig.setColorDynamic(svgConfig.largeIcon, 'bg-gradient-orange');

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
        {nameIcon:'balance',color:'bg-gradient-orange', text:'Restaurant'},
        {nameIcon:'balance',color:'bg-gradient-purple', text:'Vestimentaire'},
        {nameIcon:'restaurant',color:'bg-gradient-red', text:'Famille'},
        {nameIcon:'restaurant',color:'bg-gradient-green', text:'Loisir'},
        {nameIcon:'restaurant',color:'bg-gradient-turquoise', text:'Santé'},
        {nameIcon:'restaurant',color:'bg-gradient-purple', text:'Alimentation'},
        {nameIcon:'restaurant',color:'bg-gradient-purple', text:'Transport'},
        {nameIcon:'restaurant',color:'bg-gradient-purple', text:'Cadeau'},
    ];

    const listRecurings = [
        // {nameIcon:'',color:'bg-gradient-orange', text:'Choissiez une catégorie'},
        {nameIcon:'restaurant', color:'bg-gradient-red', text:'Facture'},
        {nameIcon:'restaurant', color:'bg-gradient-red', text:'Abonnement'},
        {nameIcon:'balance', color:'bg-gradient-red', text:'Assurance'},
        {nameIcon:'restaurant', color:'bg-gradient-red', text:'Loyer'},
        {nameIcon:'restaurant', color:'bg-gradient-red', text:'Crédit'},
    ]

    useClickOutside(() => {
        ismenuAddActive.value = false;
    }, '.trigger-class-AddPurchase', ismenuAddActive);


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
                alert(`${inputNoteVal.value}`);
                break;
            }
            case 'options' : {
                alert('valider');
                break;
            }
            case 'cancel' : {
                ismenuAddActive.value = false;
                break;
            }
        }
    }
</script>