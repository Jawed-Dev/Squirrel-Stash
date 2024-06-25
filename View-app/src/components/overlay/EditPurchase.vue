<template>
        <TransitionOpacity :durationIn="'duration-500'" :durationOut="'duration-500'">
            <div v-show="isMenuActive" class="fixed inset-0 bg-black bg-opacity-80 z-30"></div>
        </TransitionOpacity>

        <TransitionOpacity :durationIn="'duration-500'" :durationOut="'duration-500'">
            <div v-show="isMenuActive" 
            :class="`bg-main-gradient flex flex-col gap-[75px] fixed 
            shadow-black shadow-custom-main rounded-md top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 trigger-menu-edit
            z-30 text-white ${props.width}`">

                <MainContainerSlot :textBtn1="'Annuler'" :textBtn2="'Modifier'" :titleContainer="transactionType === 'purchase' ? 'Modifier l\'achat' : 'Modifier prélèvement'" @toggleMenu="toggleMenu">

                    <div class="flex flex-col rounded-[3px] items-center">
                        <label class="font-extralight" for="id-input-name"> {{ transactionType === 'purchase' ? "Nom de l'achat" : "Nom du prélèvement" }} </label>
                        <div class="mt-1">
                            <InputBase v-model="inputNameTransaction"
                            width="w-[250px]"
                            extraClass="font-light text-center"
                            placeholder="Nom"
                            id="id-input-name"/>
                        </div>
                    </div>

                    <div class="flex flex-col rounded-[3px] items-center">
                        
                        <label class="font-extralight"  for="id-input-price">Montant en €</label>
                        <div class="mt-1">
                            <InputBase v-model="inputPrice"
                            width="w-[250px]"
                            extraClass="font-light text-center"
                            placeholder="Montant"
                            id="id-input-price"/>
                        </div>
                    </div>

                    <div class="flex flex-col rounded-[3px] items-center">
                        <label class="font-extralight"  for="id-input-date">Date</label>
                        <div class="mt-1">
                            <InputBase v-model="inputDate"
                            width="w-[250px]"
                            extraClass="font-light flex justify-center"
                            placeholder="Date"
                            id="id-input-date"
                            type="datetime-local"/>
                        </div>
                    </div>

                </MainContainerSlot>
                    
            </div>
        </TransitionOpacity>
</template>

<script setup>


    // imports 
    import {ref} from 'vue';
    import MainContainerSlot from '@/components/containerSlot/MainContainerSlot.vue';
    import InputBase from '@/components/input/InputBase.vue';
    import TransitionOpacity from '@/components/transition/TransitionOpacity.vue';
    import useClickOutside from '@/composable/useClickOutSide';
    import useEscapeKey from '@/composable/useEscapeKey';
    import { storeLastNTransactions } from '@/storePinia/useStoreDashboard';

    // stores Pinia
    const lastNTransactions = storeLastNTransactions();

    // variables, props, ...
    const props = defineProps({
        width: { default:'' },
        transactionType: { default:''},
        infoTransaction: { default: [] }
    });

    const inputNameTransaction = ref('');
    const inputPrice = ref('');
    const inputDate = ref('');
    const isMenuActive = defineModel('menuActive');
    const currentTrsIndexSelect = defineModel('currentTrsIndexSelect');

    // life cycle / functions
    useEscapeKey(isMenuActive, () => {
        isMenuActive.value = false;
    });

    useClickOutside('.trigger-menu-edit', isMenuActive, () => {
            isMenuActive.value = false;
        }
    );
    function toggleMenu($request) {
        switch($request) {
            case 'cancel' : {
                resetInputs();
                isMenuActive.value = false;
            }
            case 'valid' : {
                if(props.transactionType === 'purchase') {
                    console.log(props.infoTransaction);
                }
            }
        }
    }

    function resetInputs() {
        inputDate.value = '';
        inputNameTransaction.value = '';
        inputPrice.value = '';
    }
</script>