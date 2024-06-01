<template>
    <IconPreferences @click="toggleMenu('openNClose')" class='cursor-pointer' v-show="props.showIconConfig" :svg="svg.verySmallIcon" /> 

    <TransitionOpacity :durationIn="'duration-500'" :durationOut="'duration-500'">
        <div v-show="modelMenuActive" class="fixed inset-0 bg-black bg-opacity-80 z-30"></div>
    </TransitionOpacity>

    <TransitionOpacity :durationIn="'duration-500'" :durationOut="'duration-500'">
        
        <div v-show="modelMenuActive" 
        class="bg-main-gradient flex flex-col gap-[75px] fixed 
        shadow-black shadow-custom-main rounded-md top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 trigger-class-set-treshold
        z-30 text-white ">
            <MainContainerSlot :textBtn1="'Annuler'" :textBtn2="'Modifier'" :titleContainer="'Choisir un nouveau seuil mensuel'" @toggleMenu="toggleMenu">
                <div class="flex flex-col rounded-[3px] items-center">
                    <label for="id-input-price">Montant du seuil en €</label>
                    <div class="rounded-[3px] overflow-hidden w-fit shadow-black shadow-custom-main mt-2">
                        <input id="id-input-price" class="pl-3 font-light text-[15px] text-center py-[3px] w-[250px] bg-transparent border-white border-[1px] rounded-[3px] overflow-hidden focus:outline-none" placeholder="seuil" type="text">
                    </div>
                </div>

                <div class="pl-3">
                    <p class="text-[15px]">Ce seuil sera effectif pour les prochains mois.</p>
                    <p class="text-[15px]">Il suffira de définir un nouveau seuil pour en définir un nouveau.</p>
                </div>
            </MainContainerSlot>
    
        </div>
    </TransitionOpacity>
</template>

<script setup>
    // import
    import TransitionOpacity from '@/components/transition/TransitionOpacity.vue';  
    import MainContainerSlot from '../containerSlot/MainContainerSlot.vue';
    import IconPreferences from '../svgs/IconPreferences.vue';
    import { ref } from 'vue';
    import { svgConfig } from '@/functions/svg/svgConfig';
    import useClickOutside from '@/composables/useClickOutSide';
    
    // variables, props ...
    const svg = svgConfig;
    const props = defineProps({
        showIconConfig: { default: false}
    });
    const modelMenuActive = ref(false);

    // functions 

    useClickOutside( '.trigger-class-set-treshold',  modelMenuActive, () => {
        //alert('est');
        modelMenuActive.value = false;
    },);

    function toggleMenu(request) {
        switch(request) {
            case 'openNClose' : {
                modelMenuActive.value = !modelMenuActive.value;
                break
            }
            case 'valid': {
                break;
            }
            case 'cancel': {
                //alert('test');
                modelMenuActive.value = false;
                break;
            }
        }
    }

</script>