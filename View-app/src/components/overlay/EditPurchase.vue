<template>
        <TransitionOpacity :durationIn="'duration-500'" :durationOut="'duration-500'">
            <div v-show="isMenuActive" class="fixed inset-0 bg-black bg-opacity-80 z-30"></div>
        </TransitionOpacity>

        <TransitionOpacity :durationIn="'duration-500'" :durationOut="'duration-500'">
            <div v-show="isMenuActive" 
            :class="`bg-main-gradient flex flex-col gap-[75px] fixed 
            shadow-black shadow-custom-main rounded-md top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 trigger-menu-edit
            z-30 text-white ${props.width}`">

                <MainContainerSlot :textBtn1="'Annuler'" :textBtn2="'Modifier'" :titleContainer="'Modifier l\achat'" @toggleMenu="toggleMenu">

                    <div class="flex flex-col rounded-[3px] items-center">
                        <label class="font-extralight" for="id-input-name"> Nom de l'achat</label>
                        <div class="mt-1">
                            <InputBase v-model="inputNameTransaction"
                            width="w-[250px]"
                            extraClass="font-light text-center"
                            placeholder="Nom d'achat"
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
    import MainContainerSlot from '../containerSlot/MainContainerSlot.vue';
    import InputBase from '../input/InputBase.vue';
    import {ref} from 'vue';
    import TransitionOpacity from '../transition/TransitionOpacity.vue';
    import useClickOutside from '@/composables/useClickOutSide';
    import useEscapeKey from '@/composables/useEscapeKey';


    // variables, props, ...


    const props = defineProps({
        width: { default:'' }
    });

    const inputNameTransaction = ref('');
    const inputPrice = ref('');
    const inputDate = ref('');
    const isMenuActive = defineModel('menuActive');


    // functions ...
    useEscapeKey(isMenuActive, () => {
        isMenuActive.value = false;
    });

    useClickOutside('.trigger-menu-edit', isMenuActive, () => {
            //alert('est');
            isMenuActive.value = false;
            //console.log('edit');
        }
    );
    function toggleMenu($request) {
        switch($request) {
            case 'cancel' : {
                inputDate.value = '';
                inputNameTransaction.value = '';
                inputPrice.value = '';
                isMenuActive.value = false;
            }
            case 'valid' : {

            }
        }
    }
</script>