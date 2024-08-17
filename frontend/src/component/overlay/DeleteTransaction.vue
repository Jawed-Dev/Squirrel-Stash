<template>
    <div class="fixed inset-0 bg-black bg-opacity-80 z-30">
        <div :class="`fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 z-30 text-white rounded-[3px] overflow-hidden 
                 shadow-main trigger-menu-delete bg-main-gradient
                max-[600px]:w-full sm:w-1/4 min-[600px]:min-w-[600px]`">
            <MainContainerSlot :bgMainBtn="'bg-gradient-vanusa'" :width="'w-full'" @toggleMenu="toggleMenu"
            :textBtn1="'Annuler'" :textBtn2="'Supprimer'" :titleContainer="textTitleComponent">

                <div class="max-h-[60vh] overflow-y-auto">
                    <div class="flex flex-col rounded-[3px] items-center my-28">
                        <p class="font-light text-lg opacity-90">{{ textBodyComponent }}</p>
                    </div>
                </div>
            </MainContainerSlot>
        </div>
    </div>
</template>

<script setup>
    import { computed } from 'vue';
    import MainContainerSlot from '@/component/containerSlot/MainContainerSlot.vue';
    import useClickOutside from '@/composable/useClickOutSide';
    import useEscapeKey from '@/composable/useEscapeKey';
    import { deleteTransaction } from '@/composable/useBackendActionData';
    import { storeDateSelected } from '@/storePinia/useStoreDashboard';
    import { updateAllDataTransations } from '@/storePinia/useUpdateStoreByBackend';
    import { createToast } from '@/composable/useToastNotification';

    // stores Pinia
    const dateSelected = storeDateSelected();
    // props, variables..
    const props = defineProps({
        width: {default: ''},
        infoTransaction: {default: {}},
        indexMenu: {default: 0},
    });
    const isOverlayActive = defineModel('menuActive');

    // life cycle, functions
    const textTitleComponent = computed(() => {
        const isPurchase = props.infoTransaction.transaction_type === 'purchase';
        return (isPurchase) ? 'Suppression d\'un achat' : 'Suppression d\'un prélèvement';
    });

    const textBodyComponent = computed(() => {
        const isPurchase = props.infoTransaction.transaction_type === 'purchase';
        return (isPurchase) ? 'Voulez vous vraiment supprimer cet achat ?' : 'Voulez vous vraiment supprimer ce prélèvement ?';
    });

    

    useEscapeKey(isOverlayActive, () => {
        closeOverlay();
    });

    useClickOutside( '.trigger-menu-delete', isOverlayActive, () => {
        closeOverlay();
    });

    async function toggleMenu(request) {
        switch(request) {
            case 'valid': {
                if(!isOverlayActive.value) return;
                const transactionId = props.infoTransaction?.transaction_id;
                const isSuccessRequest = await deleteTransaction(transactionId);
                if(isSuccessRequest) {
                    await updateAllDataTransations(dateSelected.month, dateSelected.year, props.infoTransaction.transaction_type);
                    createToast('Votre transaction a été supprimée.', 'success');
                    closeOverlay();
                }
                break;
            }
            case 'cancel': {
                closeOverlay();
                break;
            }
        }
    }

    function closeOverlay() {
        isOverlayActive.value = false;
    }

</script>