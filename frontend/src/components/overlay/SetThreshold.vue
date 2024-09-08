<template>
    
        <IconPreferences 
            @click="toggleMenu('openNClose')" 
            class='cursor-pointer hover:fill-blue-500' 
            v-show="isIconActive" 
            :svg="iconThreshold" 
        /> 
    
        <TransitionOpacity :durationIn="'duration-300'" :durationOut="'duration-200'">
            <div v-show="isOverlayActive" class="fixed inset-0 bg-black bg-opacity-80 z-30"></div>
        </TransitionOpacity>
    
        <TransitionOpacity :durationIn="'duration-300'" :durationOut="'duration-200'">
            <div 
                v-show="isOverlayActive" 
                :class="`fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-30 text-white rounded-[3px]
                 shadow-main trigger-set-treshold bg-main-gradient
                max-[550px]:w-full sm:w-1/4 min-[550px]:min-w-[550px]`"
            >
                <ContainerSlotOverlay 
                    :textBtn1="'Annuler'" :textBtn2="'Choisir'" :titleContainer="'Choisir un nouveau seuil'" @toggleMenu="toggleMenu">

                    <div class="max-h-[65vh] overflow-y-auto">
                        <div>
                            <div class="flex flex-col items-center mt-20">
                                <div class="w-[60%] min-[550px]:w-1/2 min-w-[270px] ">
                                    <label class="pl-2 text-lg font-light" 
                                    for="input-amount-treshold">Montant du seuil</label>
                                    <InputBase 
                                        extraClass="text-[14px] py-[2px]"
                                        iconName="Amount"
                                        v-model="AmountThreshold"
                                        v-model:stateError="errorInput" 
                                        v-model:mandatoryInput="mandatoryInput"
                                        placeholder="Montant"
                                        id="input-amount-treshold"
                                        validFormat="onlyInt"
                                        :hideAnimation="true"
                                        :onlyNumbers="true"
                                    />
                                </div>
                            </div>
                            <div class="flex justify-center my-16">
                                <p class="w-[70%] font-light text-white text-[17px] opacity-90">
                                    Ce seuil sera appliqué pour le mois en cours. <br>
                                    <span class="block">Il restera en vigueur jusqu'à l'établissement d'un nouveau seuil.</span></p>
                            </div>
                        </div>
                    </div>
                </ContainerSlotOverlay>
            </div>
        </TransitionOpacity>   
</template>

<script setup>
    // import
    import { ref } from 'vue';
    import TransitionOpacity from '@/components/transition/TransitionOpacity.vue';  
    import ContainerSlotOverlay from '@/components//containerSlot/ContainerSlotOverlay.vue';
    import IconPreferences from '@/components//svg/IconPreferences.vue';
    import { setSvgConfig } from '@/svgUtils/svgConfig';
    import useClickOutside from '@/composables/useClickOutSide';
    import useEscapeKey from '@/composables/useEscapeKey';
    import InputBase from '@/components//input/InputBase.vue';
    import { saveThreshold } from '@/requests/useBackendAction';
    import { storeDateSelected } from '@/storesPinia/useStoreDashboard';
    import { updateBalanceEcoByMonth, updateThresholdByMonth, updateTotalTrsByMonth } from '@/storesPinia/useUpdateStoreByBackend';
    import { isAnyMandatoryInputEmpty, isAnyInputError, TYPE_SUBMIT_ERROR, TEXT_SUBMIT_ERROR} from '@/errors/useHandleError';
    import { createToast } from '@/composables/useToastNotification';

    // variables, props ...
    const iconThreshold = setSvgConfig({width:'19px', fill:'white'});

    const props = defineProps({
        isIconActive: { default: false},
        width: {default:''}
    });
    const isOverlayActive = ref(false);
    const AmountThreshold = ref('');
    const errorInput = ref(false);
    const mandatoryInput = ref(false);


    // life cycle, functions
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
                break
            }
            case 'valid': {
                const allErrorsInputs = getStatesErrorInputs();
                const allMandatoryValInputs = getValuesMandantInputs();
                if(isAnyMandatoryInputEmpty(allMandatoryValInputs)) {
                    activeErrorMandatInputs();
                    createToast(TEXT_SUBMIT_ERROR.MANDATORY_EMPTY_INPUTS, 'error');
                    return;
                }
                else if(isAnyInputError(allErrorsInputs)) {
                    return;
                }
                const response = await saveThreshold(dateSelected.month, dateSelected.year, AmountThreshold.value);
                const isSuccessRequest = response?.isSuccessRequest;
                if(!isSuccessRequest) {
                    resetInput();
                    return;
                }
                updateThresholdByMonth(dateSelected.month, dateSelected.year);
                updateTotalTrsByMonth(dateSelected.month, dateSelected.year);
                updateBalanceEcoByMonth(dateSelected.month, dateSelected.year);
                createToast('Votre seuil mensuel a été édité.', 'success');
                closeOverlay();
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
    function activeErrorMandatInputs() {
        if (!AmountThreshold.value) mandatoryInput.value = true;
    }

    function disableErrorMandatInputs() {
        mandatoryInput.value = false;
    }

    function resetInput() {
        AmountThreshold.value = '';
        
    }
    function closeOverlay() {
        isOverlayActive.value = false;
        disableErrorMandatInputs();
    }
</script>