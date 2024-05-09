<template>
    <div>
        <IconAddPurchase @click="togMenuAddPurchase"
            class="p-2 bg-gradient-blue rounded-full fixed right-[100px] top-[50vh] cursor-pointer z-20
                    shadow-black shadow-custom-test" 
            :svg="svgConfig2" 
        />
        <div v-show="ismenuAddActive" 
            class="fixed top-[30vh] right-[35vw] z-10 text-white bg-main-bg rounded-md overflow-hidden">

            <!-- 2 premières sections (catégorie monnaie, catégorie achat) -->
            <div class="flex">
                <!-- 1er élement -->
                <div class="flex justify-between items-center bg-gradient-orange p-3 w-[50%] ">
                    <div class="pr-3">
                        <p class="text-[16px] font-light">Type de monnaie</p>
                        <p class="text-[20px]">Carte de crédit</p>
                    </div>
                    <div class="flex flex-col items-center gap-3">
                        <IconPreferences :svg="svgConfig4" />
                        <IconCash :svg="svgConfig3" />
                    </div>
                </div>
                <!-- 2eme élement -->
                <div class="flex justify-between items-center bg-gradient-purple p-3 w-[50%] ">
                    <div class="pr-3">
                        <p class="text-[16px]">Catégorie d'achat</p>
                        <p class="text-[20px]">Famille</p>
                    </div>
                    <div class="flex flex-col items-center gap-3">
                        <IconPreferences :svg="svgConfig4" />
                        <IconCash :svg="svgConfig3" />
                    </div>
                </div>
            </div>

            <!-- 2 autres éléments (dépense, valdier) -->

            <div class="flex">
                <!-- dépenses valeur -->
                <div class="flex flex-col w-[50%] bg-gradient-turquoise">
                    <div class="w-full text-center">
                        <p class="text-[16px]">Dépense</p>
                        <input class="text-black text-[20px] pl-2" placeholder="12,00€"></input>
                    </div>
                    <div>
                        <p class="py-2">Notes...</p>
                    </div>
                </div>

                <!-- boutton valider -->
                <div class="w-[50%]">
                    <button class="w-full bg-gradient-green py-9">Valider</button>
                </div>
            </div>


            <!-- annuler, liste des catégories, bouttons paiement, réccurence -->
            <div class="w-full">
                <div class="flex justify-between">
                    <p class="py-1 cursor-pointer">< Annuler</p>
                    <div class="flex gap-3">
                        <p class="py-1">Achat</p>
                        <p class="py-1">Réccurence</p>
                    </div>
                </div>

                <!-- liste des catégories -->
                <p class="text-center">Sélectionnez la catéorie</p>
                <div class="w-[600px] flex flex-wrap mt-5 justify-center">
                        
                        <div v-for="(icon, index) in listCategories" :key="index">
                            <component :is="iconComponent" :class="`${icon.color} rounded-full p-3 m-4 shadow-custom-main mr-[20px] cursor-pointer`" :svg="svgConfig2"/>
                            <p class="text-[14px] pl-4">{{ icon.text }}</p>
                        </div>
                </div>
            </div>
        </div>
    </div>
</template>


<script setup>

    // import
    import { ref, onMounted, shallowRef } from 'vue';
    import IconCash from '@/components/svgs/IconCash.vue';
    import IconAddPurchase from '@/components/svgs/IconAddPurchase.vue';
    import IconPreferences from '../svgs/IconPreferences.vue';
    import { getIconByName } from '@/functions/icons/getIcon';  

    // variables, props...
    const ismenuAddActive = ref(false); 
    const iconComponent = shallowRef(null);

    const svgConfig2 =  {
        width: '70px',
        height: '70px',
        fill: 'white'
    }

    const svgConfig3 =  {
        width: '100px',
        height: '100px',
        fill: 'white'
    }

    const svgConfig4 =  {
        width: '20px',
        height: '20px',
        fill: 'white'
    }

    const listCategories = [
        {color:'bg-gradient-purple', text:'Restaurant'},
        {color:'bg-gradient-purple', text:'Restaurant'},
        {color:'bg-gradient-purple', text:'Restaurant'},
        {color:'bg-gradient-purple', text:'Restaurant'},
        {color:'bg-gradient-purple', text:'Restaurant'},
        {color:'bg-gradient-purple', text:'Restaurant'},
        {color:'bg-gradient-purple', text:'Restaurant'}
    ]

    // functions
    onMounted(async () => {
        const module = await getIconByName('restaurant');
        iconComponent.value = module.default;
    });

    function togMenuAddPurchase() {
        ismenuAddActive.value = !ismenuAddActive.value;
    }
</script>