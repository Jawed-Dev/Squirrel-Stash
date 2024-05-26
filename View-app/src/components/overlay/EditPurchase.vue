<template>
        <TransitionOpacity :durationIn="'duration-500'" :durationOut="'duration-500'">
            <div v-show="modelMenuActive" class="fixed inset-0 bg-black bg-opacity-80 z-30"></div>
        </TransitionOpacity>

        <TransitionOpacity :durationIn="'duration-500'" :durationOut="'duration-500'">
            <div v-show="modelMenuActive" 
            class="bg-main-gradient flex flex-col gap-[50px] fixed 
            shadow-black shadow-custom-main rounded-md top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 trigger-class-menu-edit
            z-30 text-white ">

                <div class="rounded-[3px] shadow-black shadow-custom-main trigger-class-AddPurchase bg-gradient-joomla py-[40px] px-[150px]">
                    <h2 class="font-extralight text-[22px]">Modifier la transaction</h2>
                </div>

                <div class="flex flex-col rounded-[3px] items-center">
                    <label for="id-input-name"> Nom de transaction</label>
                    <div class="rounded-[3px] overflow-hidden w-fit shadow-black shadow-custom-main mt-2">
                        <input id="id-input-name" class="pl-1 font-light text-[13px] py-[2px] w-[200px] bg-transparent gradient-border rounded-[3px] overflow-hidden focus:outline-none" placeholder="Transaction" type="text">
                    </div>
                </div>


                <div class="flex flex-col rounded-[3px] items-center">
                    <label for="id-input-price">Prix</label>
                    <div class="rounded-[3px] overflow-hidden w-fit shadow-black shadow-custom-main mt-2">
                        <input id="id-input-price" class="pl-1 font-light text-[13px] py-[2px] w-[200px] bg-transparent gradient-border rounded-[3px] overflow-hidden focus:outline-none" placeholder="Prix" type="text">
                    </div>
                </div>

                <div class="flex flex-col rounded-[3px] items-center">
                    <label for="id-input-date">Date</label>
                    <div class="rounded-[3px] overflow-hidden w-fit shadow-black shadow-custom-main mt-2">
                        <input id="id-input-date" class="pl-1 font-light text-[13px] py-[2px] w-[200px] bg-transparent gradient-border rounded-[3px] overflow-hidden focus:outline-none" placeholder="Date" type="text">
                    </div>
                </div>

                <div class="pt-2">
                    <div class="flex shadow-black shadow-custom-main">
                        <div class="w-[50%]">
                                <button @click="toggleMenuPurchase('cancel')" class="w-full bg-gradient-namn h-full py-2 ">Annuler</button>
                        </div>
                        <div class="w-[50%]">
                            <button @click="toggleMenuPurchase('valid')" class="w-full bg-gradient-blue h-full py-2">Modifier</button>
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