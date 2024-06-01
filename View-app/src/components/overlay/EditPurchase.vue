<template>
        <TransitionOpacity :durationIn="'duration-500'" :durationOut="'duration-500'">
            <div v-show="modelMenuActive" class="fixed inset-0 bg-black bg-opacity-80 z-30"></div>
        </TransitionOpacity>

        <TransitionOpacity :durationIn="'duration-500'" :durationOut="'duration-500'">
            <div v-show="modelMenuActive" 
            class="bg-main-gradient flex flex-col gap-[75px] fixed 
            shadow-black shadow-custom-main rounded-md top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 trigger-class-menu-edit
            z-30 text-white ">

                <MainContainerSlot :textBtn1="'Annuler'" :textBtn2="'Modifier'" :titleContainer="'Modifier la transaction'" @toggleMenu="toggleMenu">

                    <div class="flex flex-col rounded-[3px] items-center">
                        <label for="id-input-name"> Nom de transaction</label>
                        <div class="rounded-[3px] overflow-hidden w-fit shadow-black shadow-custom-main mt-2">
                            <input id="id-input-name" class="search-webkit pl-3 font-light text-[15px] text-center py-[3px] w-[250px] bg-transparent border-white border rounded-[3px] overflow-hidden focus:outline-none" placeholder="Transaction" type="text">
                        </div>
                    </div>

                    <div class="flex flex-col rounded-[3px] items-center">
                        <label for="id-input-price">Montant en €</label>
                        <div class="rounded-[3px] overflow-hidden w-fit shadow-black shadow-custom-main mt-2">
                            <input id="id-input-price" class="pl-3 font-light text-[15px] text-center py-[3px] w-[250px] bg-transparent border-white border rounded-[3px] overflow-hidden focus:outline-none" placeholder="Prix" type="text">
                        </div>
                    </div>

                    <div class="flex flex-col rounded-[3px] items-center">
                        <label for="id-input-date">Date</label>
                        <div class="rounded-[3px] overflow-hidden w-fit shadow-black shadow-custom-main mt-2">
                            <input id="id-input-date" class="pl-3 font-light text-[15px] text-center py-[3px] w-[250px] bg-transparent border-white border rounded-[3px] overflow-hidden focus:outline-none " placeholder="Date" type="date">
                        </div>
                    </div>

                </MainContainerSlot>
                    
            </div>
        </TransitionOpacity>
</template>

<script setup>

    import MainContainerSlot from '../containerSlot/MainContainerSlot.vue';
    import {ref} from 'vue';

    // const props = defineProps({
    //     menuActive: {default: null}
    // });
    const modelMenuActive = defineModel('menuActive');

    import TransitionOpacity from '../transition/TransitionOpacity.vue';
    import useClickOutside from '@/composables/useClickOutSide';

    useClickOutside('.trigger-class-menu-edit', modelMenuActive, () => {
            //alert('est');
            modelMenuActive.value = false;
            //console.log('edit');
        }
    );

    

    function toggleMenu($request) {
        switch($request) {
            case 'cancel' : {
                modelMenuActive.value = false;
            }
            case 'valid' : {

            }
        }
    }
</script>