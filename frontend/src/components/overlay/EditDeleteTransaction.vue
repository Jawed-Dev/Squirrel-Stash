<template>
    <div class="w-full flex justify-center relative">
        <IconOptions @click="toggleMenu()" class="cursor-pointer trigger-menu-editdelete hover:stroke-blue-500" :svg="iconOptions"/>
        <TransitionOpacity :durationIn="'duration-500'" :durationOut="'duration-500'">
            <div v-if="isMenuActive" 
                class="absolute flex flex-col items-center z-10 trigger-menu-editdelete shadow-main
                -translate-y-0 -translate-x-20 bg-main-gradient w-[100px] rounded-md overflow-hidden">
                <p 
                    @click="handleMenu('edit')" 
                    class="hover:bg-custom-blue w-full text-center cursor-pointer p-1 border-b border-slate-700">Modifier
                </p>
                <p 
                    @click="handleMenu('delete')" 
                    class="hover:bg-custom-blue w-full text-center cursor-pointer p-1">Supprimer
                </p>
            </div>
        </TransitionOpacity>
        <TransitionOpacity :durationIn="'duration-500'" :durationOut="'duration-500'">
        <EditTransaction 
            v-if="isMenuEditActive" 
            :indexMenu="indexMenu" 
            :infoTransaction="infoTransaction" 
            v-model:menuActive="isMenuEditActive"
        />
        <DeleteTransaction 
            v-if="isMenuDeleteActive" 
            :indexMenu="indexMenu" 
            :infoTransaction="infoTransaction" 
            v-model:menuActive="isMenuDeleteActive"
        /> 
        </TransitionOpacity>
    </div>
</template>


<script setup>

    // import
    import { ref, watch, defineAsyncComponent } from 'vue';

    import IconOptions from '@/components/svgs/IconOptions.vue';
    import TransitionOpacity from '@/components/transition/TransitionOpacity.vue';
    import useClickOutside from '@/composables/useClickOutSide';
    //import DeleteTransaction from '@/components/overlay/DeleteTransaction.vue';
    import useEscapeKey from '@/composables/useEscapeKey';
    import EditTransaction from '@/components/overlay/EditTransaction.vue';
    
    const DeleteTransaction = defineAsyncComponent(() => import('@/components/overlay/DeleteTransaction.vue'));

    // variables, props ...
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
        infoTransaction: { default: {} }
    });

    const currentMenuEditDeleteTrs = defineModel('currentMenuEditDeleteTrs');
    const isMenuActive = ref(false);

    watch(currentMenuEditDeleteTrs, (newVal, oldVald) => {
        isMenuActive.value = newVal === props.indexMenu;
    });

    useEscapeKey(isMenuActive, () => {
        currentMenuEditDeleteTrs.value = -1;
    });
    
    useClickOutside('.trigger-menu-editdelete', isMenuActive, () => {
        if(isMenuActive.value) {
            //alert('close');
            currentMenuEditDeleteTrs.value = -1;
        }
    });

    function handleMenu(request) {
        switch(request) {
            case 'edit': {
                isMenuEditActive.value = !isMenuEditActive.value;
                currentMenuEditDeleteTrs.value = -1;
                break;
            }
            case 'delete' : {
                isMenuDeleteActive.value = !isMenuDeleteActive.value;
                currentMenuEditDeleteTrs.value = -1;
                break;
            }
            case 'cancel' : {
                currentMenuEditDeleteTrs.value = -1;
                break;
            }
        }
    }

    function toggleMenu () {
        (currentMenuEditDeleteTrs.value === props.indexMenu) ? currentMenuEditDeleteTrs.value = -1 : currentMenuEditDeleteTrs.value = props.indexMenu;
    }
</script>