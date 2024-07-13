<template>
    <IconPreferences @click="toggleMenu('openNClose')" class='cursor-pointer' v-show="isIconActive" :svg="svg.verySmallIcon" /> 

    <TransitionOpacity :durationIn="'duration-300'" :durationOut="'duration-200'">
        <div v-show="isMenuActive" class="fixed inset-0 bg-black bg-opacity-80 z-30"></div>
    </TransitionOpacity>

    

    <TransitionOpacity :durationIn="'duration-300'" :durationOut="'duration-200'">
        
        <div v-show="isMenuActive" 
        :class="`bg-main-gradient flex flex-col fixed 
            shadow-black shadow-custom-main rounded-md top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 trigger-set-treshold
            z-30 text-white ${width}`"
        >
            
            <MainContainerSlot 
                :textBtn1="'Annuler'" :textBtn2="'Choisir'" :titleContainer="'Choisir un nouveau seuil'" @toggleMenu="toggleMenu">
                <!-- Errors  -->
                <div class="relative">
                    <p class="p-3 absolute text-red-400">{{ computedFormatErrors }}</p>
                    <p v-if="computedEmptyInputs.length > 0"></p>
                </div>
                <div>
                    <div class="flex flex-col items-center mt-[60px]">
                        <div class="text-center w-[40%]">
                            <label class="font-extralight text-center" for="input-amount-treshold">Montant du seuil (€)</label>
                            <InputBase v-model="inputAmountThreshold"
                                unicode="🎯"
                                width="w-full"
                                extraClass=""
                                placeholder="seuil"
                                id="input-amount-treshold"
                            />
                        </div>
                    </div>
                    <div class="flex justify-center my-[60px]">
                        <p class="w-[full] text-[15px] font-light text-white">Ce seuil sera effectif pour le mois actuel <span class="block">et les suivants, jusqu'à un nouveau seuil.</span></p>
                    </div>
                </div>
            </MainContainerSlot>
        </div>
    </TransitionOpacity>
</template>

<script setup>
    // import
    import { computed, ref } from 'vue';
    import TransitionOpacity from '@/component/transition/TransitionOpacity.vue';  
    import MainContainerSlot from '@/component//containerSlot/MainContainerSlot.vue';
    import IconPreferences from '@/component//svgs/IconPreferences.vue';
    import { svgConfig } from '@/svg/svgConfig';
    import useClickOutside from '@/composable/useClickOutSide';
    import useEscapeKey from '@/composable/useEscapeKey';
    import InputBase from '@/component//input/InputBase.vue';
    import { saveThreshold } from '@/composable/useBackendActionData';
    import { storeDateSelected } from '@/storePinia/useStoreDashboard';
    import { updateBalanceEcoByMonth, updateThresholdByMonth, updateTotalTrsByMonth } from '@/storePinia/useUpdateStoreByBackend';
    import { useErrorFormat, verifySetThreshold } from '@/error/useHandleError';
    import { useMandatoryEmptyInputs } from '@/error/useMandatoryEmptyInputs';

    // variables, props ...
    const svg = svgConfig;
    const props = defineProps({
        isIconActive: { default: false},
        width: {default:''}
    });
    const isMenuActive = ref(false);
    const inputAmountThreshold = ref('');

    // Errors 
    const { computedEmptyInputs, stateEmptyInputs } = useMandatoryEmptyInputs([
        { name: 'inputAmountThreshold', ref: inputAmountThreshold }
    ]);
    const { stateFormatErrors, computedFormatErrors } = useErrorFormat(verifySetThreshold, {
        thresholdAmount: {name: 'thresholdAmount', ref: inputAmountThreshold}, 
    });

    // life cycle, functions 
    useEscapeKey(isMenuActive, () => {
        isMenuActive.value = false;
    });

    useClickOutside('.trigger-set-treshold',  isMenuActive, () => {
        isMenuActive.value = false;
    },);

    const dateSelected = storeDateSelected();

    function isAnyErrorActive() {
        return stateFormatErrors.value.length > 0 || stateEmptyInputs.value.length > 0;
    }

    async function toggleMenu(request) {
        switch(request) {
            case 'openNClose' : {
                inputAmountThreshold.value = '';
                isMenuActive.value = !isMenuActive.value;
                break
            }
            case 'valid': {
                if(isAnyErrorActive()) return;
                const responseFetched = await saveThreshold(dateSelected.month, dateSelected.year, inputAmountThreshold.value);
                const isSuccessRequest = responseFetched?.isSuccessRequest;
                if(isSuccessRequest) {
                    updateThresholdByMonth(dateSelected.month, dateSelected.year);
                    updateTotalTrsByMonth(dateSelected.month, dateSelected.year);
                    updateBalanceEcoByMonth(dateSelected.month, dateSelected.year);
                }
                isMenuActive.value = false;
                break;
            }
            case 'cancel': {
                inputAmountThreshold.value = '';
                isMenuActive.value = false;
                break;
            }
        }
    }

</script>