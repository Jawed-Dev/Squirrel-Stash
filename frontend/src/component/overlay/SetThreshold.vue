<template>
    
        <IconPreferences @click="toggleMenu('openNClose')" class='cursor-pointer' v-show="isIconActive" :svg="iconThreshold" /> 
    
        <TransitionOpacity :durationIn="'duration-300'" :durationOut="'duration-200'">
            <div v-show="isOverlayActive" class="fixed inset-0 bg-black bg-opacity-80 z-30"></div>
        </TransitionOpacity>
    
        <TransitionOpacity :durationIn="'duration-300'" :durationOut="'duration-200'">
            
            <div 
                v-show="isOverlayActive" 
                :class="`fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-30 text-white rounded-[3px]
                shadow-black shadow-custom-main trigger-set-treshold bg-main-gradient
                max-[500px]:w-full sm:w-1/4 min-[500px]:min-w-[500px]`"
            >
                <MainContainerSlot 
                    :textBtn1="'Annuler'" :textBtn2="'Choisir'" :titleContainer="'Choisir un nouveau seuil'" @toggleMenu="toggleMenu">

                    <div class="max-h-[65vh] overflow-y-auto">
                        <!-- Errors -->
                        <div class="relative">
                            <p class="text-sm font-light pt-3 absolute text-red-300">{{ textError }}</p>
                        </div>
                        <div>
                            <div class="flex flex-col items-center mt-12">
                                <div>
                                    <label class="pl-2 text-lg font-light" for="input-amount-treshold">Montant du seuil</label>
                                    <InputBase 
                                        iconName="Amount"
                                        v-model="AmountThreshold"
                                        v-model:stateError="errorInput" 
                                        placeholder="Montant"
                                        id="input-amount-treshold"
                                        validFormat="amount"
                                        :hideAnimation="true"
                                        :onlyNumbers="true"
                                    />
                                </div>
                            </div>
                            <div class="flex justify-center my-12">
                                <p class="font-light text-white">Ce seuil sera effectif pour le mois actuel <span class="block">et les suivants, jusqu'à un nouveau seuil.</span></p>
                            </div>
                        </div>
                    </div>
                </MainContainerSlot>
            </div>
        </TransitionOpacity>
        <TransitionPopUp duration-in="500" duration-out="500">
            <OverlaySuccessAction text="Votre seuil de transaction a été modifié." v-if="isSuccessAction" v-model:overlayActive="isSuccessAction" />
        </TransitionPopUp>
   
</template>

<script setup>
    // import
    import { computed, ref, defineAsyncComponent } from 'vue';
    import TransitionOpacity from '@/component/transition/TransitionOpacity.vue';  
    import MainContainerSlot from '@/component//containerSlot/MainContainerSlot.vue';
    import IconPreferences from '@/component//svgs/IconPreferences.vue';
    import { setSvgConfig, svgConfig } from '@/svg/svgConfig';
    import useClickOutside from '@/composable/useClickOutSide';
    import useEscapeKey from '@/composable/useEscapeKey';
    import InputBase from '@/component//input/InputBase.vue';
    import { saveThreshold } from '@/composable/useBackendActionData';
    import { storeDateSelected } from '@/storePinia/useStoreDashboard';
    import { updateBalanceEcoByMonth, updateThresholdByMonth, updateTotalTrsByMonth } from '@/storePinia/useUpdateStoreByBackend';
    import { isAnyMandatInputEmpty, isAnyInputError, TYPE_SUBMIT_ERROR, TEXT_SUBMIT_ERROR} from '@/error/useHandleError';
    import TransitionPopUp from '@/component/transition/TransitionPopUp.vue';

    const OverlaySuccessAction = defineAsyncComponent(() => import('@/component/overlay/OverlaySuccessAction.vue'));

    // variables, props ...
    const iconThreshold = setSvgConfig({width:'18px', fill:'white'});

    const props = defineProps({
        isIconActive: { default: false},
        width: {default:''}
    });
    const isOverlayActive = ref(false);
    const AmountThreshold = ref('');

    const errorInput = ref(false);
    const submitError = ref(null);
    const isSuccessAction = ref(false);

    // life cycle, functions
    const textError = computed(() => {
        if(submitError.value === TYPE_SUBMIT_ERROR.MANDATORY_EMPTY_INPUTS) return TEXT_SUBMIT_ERROR.MANDATORY_EMPTY_INPUTS;
        else if(submitError.value === TYPE_SUBMIT_ERROR.NOT_SUCCESS_REQUEST) return "La requête a échoué.";
    });

    useEscapeKey(isOverlayActive, () => {
        closeOverlay();
    });

    useClickOutside('.trigger-set-treshold',  isOverlayActive, () => {
        closeOverlay();
    });

    const dateSelected = storeDateSelected();

    async function toggleMenu(request) {
        switch(request) {
            case 'openNClose' : {
                AmountThreshold.value = '';
                isOverlayActive.value = !isOverlayActive.value;
                submitError.value = null;
                break
            }
            case 'valid': {
                const allErrorsInputs = getStatesErrorInputs();
                const allMandatoryValInputs = getValuesMandantInputs();
                if(isAnyMandatInputEmpty(allMandatoryValInputs)) {
                    submitError.value = TYPE_SUBMIT_ERROR.MANDATORY_EMPTY_INPUTS;
                    return;
                }
                else if(isAnyInputError(allErrorsInputs)) {
                    submitError.value = TYPE_SUBMIT_ERROR.INPUTS_FORMAT_ERRORS;
                    return;
                }
                const response = await saveThreshold(dateSelected.month, dateSelected.year, AmountThreshold.value);
                const isSuccessRequest = response?.isSuccessRequest;
                if(!isSuccessRequest) {
                    submitError.value = TYPE_SUBMIT_ERROR.NOT_SUCCESS_REQUEST;
                    resetInput();
                    return;
                }

                updateThresholdByMonth(dateSelected.month, dateSelected.year);
                updateTotalTrsByMonth(dateSelected.month, dateSelected.year);
                updateBalanceEcoByMonth(dateSelected.month, dateSelected.year);
                closeOverlay();
                submitError.value = null;
                isSuccessAction.value = true;
                break;
            }
            case 'cancel': {
                AmountThreshold.value = '';
                closeOverlay();
                break;
            }
        }
    }
    function getStatesErrorInputs() {
        return {
            amount: errorInput.value,
        }
    }
    function getValuesMandantInputs() {
        return {
            amount: AmountThreshold.value
        }
    }

    function resetInput() {
        AmountThreshold.value = '';
    }

    function closeOverlay() {
        isOverlayActive.value = false;
    }
    

</script>