<template>
    <TransitionOpacity :durationIn="'duration-500'" :durationOut="'duration-500'">
        <div v-show="modelMenuActive" class="fixed inset-0 bg-black bg-opacity-80 z-30"></div>
    </TransitionOpacity>

    <TransitionOpacity :durationIn="'duration-500'" :durationOut="'duration-500'">
        <div v-show="modelMenuActive" class="bg-main-gradient flex flex-col gap-[130px] fixed 
        shadow-black shadow-custom-main rounded-md top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 trigger-class-menu-delete
        z-30 text-white ">
    
            <MainContainerSlot :textBtn1="'refuser'" :textBtn2="'Accepter'" :titleContainer="'Suppression d\'achat'" @toggleMenu="toggleMenu">
                <div class="flex flex-col rounded-[3px] items-center">
                    <p class="text-[18px]">Voulez vous supprimer cet achat ?</p>
                </div>
            </MainContainerSlot>

        </div>
    </TransitionOpacity>

</template>

    

<script setup>
    //import

    import TransitionOpacity from '@/components/transition/TransitionOpacity.vue';  
    import MainContainerSlot from '../containerSlot/MainContainerSlot.vue';
    import useClickOutside from '@/composables/useClickOutSide';
    import { ref } from 'vue';
    

    const modelMenuActive = defineModel('menuActive');


    useClickOutside( '.trigger-class-menu-delete', modelMenuActive, () => {
        //alert('test');
        modelMenuActive.value = false;
    },);

    function toggleMenu(request) {
        switch(request) {
            case 'valid': {
                break;
            }
            case 'cancel': {
                //alert('test');
                modelMenuActive.value = false;
                break;
            }
        }
    }

</script>