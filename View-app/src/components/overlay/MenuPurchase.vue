<template>

        <TransitionOpacity :durationIn="'duration-500'" :durationOut="'duration-500'">
            <div v-show="menuActive" class="fixed inset-0 bg-black bg-opacity-80 z-30"></div>
        </TransitionOpacity>

        <TransitionOpacity :durationIn="'duration-500'" :durationOut="'duration-500'">
            <div v-show="menuActive" 
            class="bg-main-gradient flex flex-col gap-[50px] fixed 
            shadow-black shadow-custom-main rounded-md top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2
            z-30">
            
                <h2 class="pt-[100px] px-[150px]">Nom d'overlay</h2>

                <div class="flex flex-col px-[150px]">
                    <label for="nameInput">Titre</label>
                    <input class="rounded-sm" id="nameInput" type="text">
                </div>

                <div class="flex flex-col px-[150px]">
                    <label for="">Prix</label>
                    <input class="rounded-sm" id="" type="text">
                </div>

                <div class="flex flex-col px-[150px]">
                    <label for="">Prix</label>
                    <input class="rounded-sm" id="" type="text">
                </div>

                <div class="flex flex-col px-[150px]">
                    <label for="">Date</label>
                    <input class="rounded-sm" id="" type="text">
                </div>

                <div class="flex pt-[50px]">
                    <div class="w-[50%]">
                            <button @click="toggleMenuPurchase('cancel')" class="w-full bg-gradient-red h-full py-2">Annuler</button>
                    </div>
                    <div class="w-[50%]">
                        <button @click="toggleMenuPurchase('valid')" class="w-full bg-gradient-green h-full py-2">Modifier</button>
                    </div>
                </div>
                    
                
            </div>
        </TransitionOpacity>
</template>

<script setup>

    import {ref} from 'vue';

    const props = defineProps({
        menuActive: {default: null}
    });

    const emit = defineEmits(['update:modelValue']);

    import TransitionOpacity from '../transition/TransitionOpacity.vue';
    import useClickOutside from '@/composables/useClickOutSide';

    useClickOutside( () => {
       
    }, '.trigger-class-menu-purchase');

    

    function toggleMenuPurchase($request) {
        switch($request) {
            case 'cancel' : {
                // emit('update:modelValue', false);
            }
        }
    }
</script>