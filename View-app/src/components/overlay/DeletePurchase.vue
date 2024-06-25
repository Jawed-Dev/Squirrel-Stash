<template>
    <TransitionOpacity :durationIn="'duration-500'" :durationOut="'duration-500'">
        <div v-show="isMenuActive" class="fixed inset-0 bg-black bg-opacity-80 z-30"></div>
    </TransitionOpacity>

    <TransitionOpacity :durationIn="'duration-500'" :durationOut="'duration-500'">
        <div v-show="isMenuActive" :class="`bg-main-gradient flex flex-col gap-[130px] fixed 
        shadow-black shadow-custom-main rounded-md top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 trigger-menu-delete
        z-30 text-white ${width}`">
    
            <MainContainerSlot :bgMainBtn="'bg-gradient-vanusa'"  :width="'w-[250px]'" :textBtn1="'Annuler'" :textBtn2="'Supprimer'" :titleContainer="'Suppression d\'achat'" @toggleMenu="toggleMenu">
                <div class="flex flex-col rounded-[3px] items-center">
                    <p class="text-[18px] font-light">Voulez vous supprimer cet achat ?</p>
                </div>
            </MainContainerSlot>

        </div>
    </TransitionOpacity>

</template>

    

<script setup>
    import TransitionOpacity from '@/components/transition/TransitionOpacity.vue';  
    import MainContainerSlot from '@/components/containerSlot/MainContainerSlot.vue';
    import useClickOutside from '@/composable/useClickOutSide';
    import useEscapeKey from '@/composable/useEscapeKey';
    import { deleteTransaction } from '@/composable/useBackendSetData';
    import { storeDateSelected } from '@/storePinia/useStoreDashboard';
    import { updateAllDataTransations } from '@/storePinia/useUpdateStoreByBackend';

    // stores Pinia
    const dateSelected = storeDateSelected();

    // props, variables..
    const props = defineProps({
        width: {default: ''},
        transactionType: {default: ''},
        infoTransaction: {default: []}
    });

    const isMenuActive = defineModel('menuActive');
    const currentTrsIndexSelect = defineModel('currentTrsIndexSelect');

    useEscapeKey(isMenuActive, () => {
        isMenuActive.value = false;
    });

    useClickOutside( '.trigger-menu-delete', isMenuActive, () => {
        //alert('test');
        isMenuActive.value = false;
    },);

    async function toggleMenu(request) {
        switch(request) {
            case 'valid': {
                if(!isMenuActive.value) return;

                const transactionId = props.infoTransaction.transaction_id;
                const isSuccessRequest = await deleteTransaction(transactionId);
                if(isSuccessRequest) {
                    updateAllDataTransations(dateSelected.month, dateSelected.year, props.transactionType);
                }
                isMenuActive.value = false;
                break;
            }
            case 'cancel': {
                //alert('test');
                isMenuActive.value = false;
                break;
            }
        }
    }

</script>