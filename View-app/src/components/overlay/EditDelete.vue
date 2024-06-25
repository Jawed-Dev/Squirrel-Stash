<template>
    <div class="flex justify-end relative">
            <IconOptions @click="toggleMenu()" class="cursor-pointer trigger-menu-editdelete" :svg="iconOptions"/>

            <TransitionOpacity ref="elementTransition" :durationIn="'duration-500'" :durationOut="'duration-500'">
                <div v-show="isMenuActive" 
                    class="flex flex-col items-center absolute top-[-20px] left-[2vw] z-10 trigger-menu-editdelete
                    bg-main-gradient w-[100px] rounded-md overflow-hidden
                    shadow-black shadow-custom-main">
                    <p @click="handleMenu('edit')" class="hover:bg-custom-blue w-[100%] text-center cursor-pointer p-1 ">Modifier</p>
                    <p @click="handleMenu('delete')" class="hover:bg-custom-blue w-[100%] text-center cursor-pointer p-1">Supprimer</p>
                </div>
            </TransitionOpacity>
    </div>

    <editTransaction width="w-[30%]" :infoTransaction="infoTransaction" v-model:menuActive="isMenuEditActive"/>
    <!-- <EditPurchase width="w-[30%]" :infoTransaction="infoTransaction" v-model:currentTrsIndexSelect="currentTrsIndexSelect" v-model:menuActive="isMenuEditActive" :transactionType="transactionType"/> -->
    <DeletePurchase width="w-[30%]" :infoTransaction="infoTransaction" v-model:currentTrsIndexSelect="currentTrsIndexSelect" v-model:menuActive="isMenuDeleteActive" :transactionType="transactionType"/> 
</template>


<script setup>

    // import
    import { ref, watch } from 'vue';

    import IconOptions from '@/components/svgs/IconOptions.vue';
    import TransitionOpacity from '@/components/transition/TransitionOpacity.vue';
    import useClickOutside from '@/composable/useClickOutSide';
    import EditPurchase from '@/components/overlay/EditPurchase.vue';
    import DeletePurchase from '@/components/overlay/DeletePurchase.vue';
    import useEscapeKey from '@/composable/useEscapeKey';
    import editTransaction from '@/components/overlay/EditTransaction.vue';

    // variables, props ...
    const elementTransition = ref(null);
    const isMenuEditActive = ref(false);
    const isMenuDeleteActive = ref(false);

    const iconOptions = {
        fill: 'white',
        width: '30px',
        height: '30px',
    };

    // functions
    const props = defineProps({
        indexMenu: { default: 0 },
        transactionType: { default: ''},
        infoTransaction: { default: [] }
    });

    const currentTrsIndexSelect = defineModel('currentTrsIndexSelect');
    const isMenuActive = ref(false);

    watch(currentTrsIndexSelect, (newVal, oldVald) => {
        isMenuActive.value = newVal === props.indexMenu;
    });

    useEscapeKey(isMenuActive, () => {
        currentTrsIndexSelect.value = -1;
    });
    
    useClickOutside('.trigger-menu-editdelete', isMenuActive, () => {
        if(isMenuActive.value) {
            //alert('close');
            currentTrsIndexSelect.value = -1;
        }
    });

    function handleMenu(request) {
        switch(request) {
            case 'edit': {
                isMenuEditActive.value = !isMenuEditActive.value;
                currentTrsIndexSelect.value = -1;
                break;
            }
            case 'delete' : {
                isMenuDeleteActive.value = !isMenuDeleteActive.value;
                currentTrsIndexSelect.value = -1;
                break;
            }
            case 'cancel' : {
                //alert(isMenuDeleteActive.value);
                //isMenuDeleteActive.value = !isMenuDeleteActive.value;
                currentTrsIndexSelect.value = -1;
                break;
            }
        }
    }

    function toggleMenu () {
        //alert('alert');
        (currentTrsIndexSelect.value === props.indexMenu) ? currentTrsIndexSelect.value = -1 : currentTrsIndexSelect.value = props.indexMenu;
        //alert(props.idMenu);
    }
</script>