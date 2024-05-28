<template>
        <TransitionOpacity :durationIn="'duration-500'" :durationOut="'duration-500'">
            <div v-show="modelMenuActive" class="fixed inset-0 bg-black bg-opacity-80 z-30"></div>
        </TransitionOpacity>

        <TransitionOpacity :durationIn="'duration-500'" :durationOut="'duration-500'">
            <div v-show="modelMenuActive" 
            class="bg-main-gradient flex flex-col gap-[75px] fixed 
            shadow-black shadow-custom-main rounded-md top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 trigger-class-menu-edit
            z-30 text-white ">

                <div class="rounded-t-[3px] shadow-black shadow-custom-main trigger-class-AddPurchase bg-gradient-joomla py-[36px] px-[150px]">
                    <h2 class="font-extralight text-[22px]">Modifier la transaction</h2>
                </div>

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

                <div class="pt-2">
                    <div class="flex shadow-black shadow-custom-main">
                        <div class="w-[50%]">
                            <button @click="toggleMenuPurchase('cancel')" class="w-full bg-gradient-namn h-full py-2 rounded-bl-[3px]">Annuler</button>
                        </div>
                        <div class="w-[50%]">
                            <button @click="toggleMenuPurchase('valid')" class="w-full bg-gradient-blue h-full py-2 rounded-br-[3px]">Modifier</button>
                        </div>
                    </div>
                </div>
                    
                
            </div>
        </TransitionOpacity>
</template>

<script setup>

    import {ref} from 'vue';

    // const props = defineProps({
    //     menuActive: {default: null}
    // });
    const modelMenuActive = defineModel('menuActive');

    const emit = defineEmits(['update:modelValue']);

    import TransitionOpacity from '../transition/TransitionOpacity.vue';
    import useClickOutside from '@/composables/useClickOutSide';

    useClickOutside( () => {
        //alert('est');
        modelMenuActive.value = false;
    }, '.trigger-class-menu-edit');

    

    function toggleMenuPurchase($request) {
        switch($request) {
            case 'cancel' : {
                modelMenuActive.value = false;
                // emit('update:modelValue', false);
            }
        }
    }
</script>