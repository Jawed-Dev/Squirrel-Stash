<template>
    <IconPreferences @click="toggleMenu('openNClose')" class='cursor-pointer' v-show="isIconActive" :svg="svg.verySmallIcon" /> 

    <TransitionOpacity :durationIn="'duration-500'" :durationOut="'duration-500'">
        <div v-show="isMenuActive" class="fixed inset-0 bg-black bg-opacity-80 z-30"></div>
    </TransitionOpacity>

    <TransitionOpacity :durationIn="'duration-500'" :durationOut="'duration-500'">
        <div v-show="isMenuActive" 
        :class="`bg-main-gradient flex flex-col gap-[75px] fixed 
        shadow-black shadow-custom-main rounded-md top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 trigger-set-treshold
        z-30 text-white ${width}`">
            <MainContainerSlot paddingX="px-[90px]" paddingY="py-[35px]" :textBtn1="'Annuler'" :textBtn2="'Modifier'" :titleContainer="'Choisir un nouveau seuil mensuel'" @toggleMenu="toggleMenu">
                <div class="flex flex-col rounded-[3px] items-center">
                    <label class="font-extralight" for="input-amount-treshold">Montant du seuil en €</label>
                    <div class="mt-2">
                        <InputBase v-model="inputAmountThreshold"
                        width="w-[250px]"
                        extraClass="text-center font-light "
                        placeholder="seuil"
                        id="input-amount-treshold"/>
                    </div>
                </div>
                <div class="flex justify-center">
                    <p class="w-[300px] text-[15px] font-light text-gray-200">Ce seuil sera effectif pour ce mois <span class="block">et les suivants jusqu'à son changement.</span></p>
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
    import { svgConfig } from '@/functions/svg/svgConfig';
    import useClickOutside from '@/composables/useClickOutSide';
    import useEscapeKey from '@/composables/useEscapeKey';
    import InputBase from '../input/InputBase.vue';

    import { ref } from 'vue';
    
    // variables, props ...
    const svg = svgConfig;
    const props = defineProps({
        isIconActive: { default: false},
        width: {default:''}
    });
    const isMenuActive = ref(false);
    const inputAmountThreshold = ref('');

    // functions 
    useEscapeKey(isMenuActive, () => {
        isMenuActive.value = false;
    });

    useClickOutside('.trigger-set-treshold',  isMenuActive, () => {
        isMenuActive.value = false;
    },);

    function toggleMenu(request) {
        switch(request) {
            case 'openNClose' : {
                inputAmountThreshold.value = '';
                isMenuActive.value = !isMenuActive.value;
                break
            }
            case 'valid': {
                break;
            }
            case 'cancel': {
                //alert('test');
                inputAmountThreshold.value = '';
                isMenuActive.value = false;
                break;
            }
        }
    }

</script>