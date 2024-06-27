<template>
    <IconPreferences @click="toggleMenu('openNClose')" class='cursor-pointer' v-show="isIconActive" :svg="svg.verySmallIcon" /> 

    <TransitionOpacity :durationIn="'duration-500'" :durationOut="'duration-500'">
        <div v-show="isMenuActive" class="fixed inset-0 bg-black bg-opacity-80 z-30"></div>
    </TransitionOpacity>

    <TransitionOpacity :durationIn="'duration-500'" :durationOut="'duration-500'">
        <div v-show="isMenuActive" 
        :class="`bg-main-gradient flex flex-col fixed 
            shadow-black shadow-custom-main rounded-md top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 trigger-set-treshold
            z-30 text-white ${width}`"
        >
            <MainContainerSlot :textBtn1="'Annuler'" :textBtn2="'Choisir'" :titleContainer="'Choisir un nouveau seuil'" @toggleMenu="toggleMenu">
                <!-- Errors  -->
                <div class="relative">
                    <p class="p-3 absolute text-red-400">{{ computedErrors }}</p>
                </div>
                <div>
                    <div class="flex flex-col rounded-[3px] items-center my-[70px]">
                        <label class="font-extralight" for="input-amount-treshold">Montant du seuil en €</label>
                        <div class="mt-[10px]">
                            <InputBase v-model="inputAmountThreshold"
                                width="w-full"
                                extraClass="text-center font-light "
                                placeholder="seuil"
                                id="input-amount-treshold"
                            />
                        </div>
                    </div>
                    <div class="flex justify-center my-[70px]">
                        <p class="w-[full] text-[15px] font-light text-gray-200">Ce seuil sera effectif pour ce mois <span class="block">et les suivants jusqu'au nouveau seuil.</span></p>
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
    import { saveThreshold } from '@/composable/useBackendSetData';
    import { storeDateSelected } from '@/storePinia/useStoreDashboard';
    import { updateBalanceEcoByMonth, updateThresholdByMonth, updateTotalTrsByMonth } from '@/storePinia/useUpdateStoreByBackend';
    import { verifySetThreshold } from '@/error/useHandleError';
    

    // Errors 
    const stateErrors = ref([]);

    // variables, props ...
    const svg = svgConfig;
    const props = defineProps({
        isIconActive: { default: false},
        width: {default:''}
    });
    const isMenuActive = ref(false);
    const inputAmountThreshold = ref('');

    // life cycle, functions 
    useEscapeKey(isMenuActive, () => {
        isMenuActive.value = false;
    });

    useClickOutside('.trigger-set-treshold',  isMenuActive, () => {
        isMenuActive.value = false;
    },);

    const dateSelected = storeDateSelected();

    const computedErrors = computed(() => {
        const isError = verifySetThreshold({
            thresholdAmount: inputAmountThreshold.value,
        });
        if(isError) {
            stateErrors.value = isError;
            return isError[0].message;
        }
        else stateErrors.value = [];
    });

    function isAnyErrorActive() {
        return stateErrors.value.length > 0;
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