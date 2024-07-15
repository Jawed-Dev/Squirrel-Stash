<template>
    <TransitionOpacity :durationIn="'duration-300'" :durationOut="'duration-200'">
        <div v-if="isMenuActive" class="fixed inset-0 bg-black bg-opacity-80 z-30"></div>
    </TransitionOpacity>

    <TransitionOpacity :durationIn="'duration-300'" :durationOut="'duration-200'">
        <div v-if="isMenuActive" :class="`bg-main-gradient flex flex-col fixed 
        shadow-black shadow-custom-main rounded-md top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 trigger-menu-delete
        z-30 text-white ${width}`">
    
            <MainContainerSlot :bgMainBtn="'bg-gradient-vanusa'" :width="'w-full'" 
            :textBtn1="'Annuler'" :textBtn2="'Supprimer'" :titleContainer="textTitleComponent" @toggleMenu="toggleMenu">
                <div class="flex flex-col rounded-[3px] items-center my-[80px]">
                    <p class="text-[18px] font-light">{{ textBodyComponent }}</p>
                </div>
            </MainContainerSlot>

        </div>
    </TransitionOpacity>
</template>

    

<script setup>
    import { computed } from 'vue';
    import TransitionOpacity from '@/component/transition/TransitionOpacity.vue';  
    import MainContainerSlot from '@/component/containerSlot/MainContainerSlot.vue';
    import useClickOutside from '@/composable/useClickOutSide';
    import useEscapeKey from '@/composable/useEscapeKey';
    import { deleteTransaction } from '@/composable/useBackendActionData';
    import { storeDateSelected } from '@/storePinia/useStoreDashboard';
    import { updateAllDataTransations } from '@/storePinia/useUpdateStoreByBackend';

    // stores Pinia
    const dateSelected = storeDateSelected();

    // props, variables..
    const props = defineProps({
        width: {default: ''},
        infoTransaction: {default: []},
        indexMenu: {default: 0},
    });

    const textTitleComponent = computed(() => {
        const isPurchase = props.infoTransaction.transaction_type === 'purchase';
        return (isPurchase) ? 'Suppression d\'un achat' : 'Suppression d\'un prélèvement';
    });

    const textBodyComponent = computed(() => {
        const isPurchase = props.infoTransaction.transaction_type === 'purchase';
        return (isPurchase) ? 'Voulez vous vraiment supprimer cet achat ?' : 'Voulez vous vraiment supprimer ce prélèvement ?';
    });

    const isMenuActive = defineModel('menuActive');

    useEscapeKey(isMenuActive, () => {
        isMenuActive.value = false;
    });

    useClickOutside( '.trigger-menu-delete', isMenuActive, () => {
        isMenuActive.value = false;
    },);

    async function toggleMenu(request) {
        switch(request) {
            case 'valid': {
                if(!isMenuActive.value) return;
                const transactionId = props.infoTransaction.transaction_id;
                const isSuccessRequest = await deleteTransaction(transactionId);
                if(isSuccessRequest) {
                    updateAllDataTransations(dateSelected.month, dateSelected.year, props.infoTransaction.transaction_type);
                }
                isMenuActive.value = false;
                break;
            }
            case 'cancel': {
                isMenuActive.value = false;
                break;
            }
        }
    }

</script>