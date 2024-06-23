<template>
    <div class="flex justify-end relative">
            <IconOptions @click="toggleMenu()" class="cursor-pointer trigger-menu-editdelete" :svg="iconOptions"/>

            <TransitionOpacity ref="elementTransition" :durationIn="'duration-500'" :durationOut="'duration-500'">
                <div v-show="isMenuActive" 
                    class="flex flex-col items-center absolute top-[-20px] left-[10px] z-10 trigger-menu-editdelete
                    bg-main-gradient w-[100px] rounded-md overflow-hidden
                    shadow-black shadow-custom-main">
                    <p @click="handleMenu('edit')" class="hover:bg-custom-blue w-[100%] text-center cursor-pointer p-1 ">Modifier</p>
                    <p @click="handleMenu('delete')" class="hover:bg-custom-blue w-[100%] text-center cursor-pointer p-1">Supprimer</p>
                </div>
            </TransitionOpacity>
    </div>

    <EditPurchase width="w-[30%]" v-model:menuActive="isMenuEditActive" />
    <DeletePurchase width="w-[30%]" v-model:menuActive="isMenuDeleteActive" /> 
</template>


<script setup>

    // import
    import { ref, watch } from 'vue';

    import IconOptions from '../svgs/IconOptions.vue';
    import TransitionOpacity from '../transition/TransitionOpacity.vue';
    import useClickOutside from '@/composable/useClickOutSide';
    import EditPurchase from './EditPurchase.vue';
    import DeletePurchase from './DeletePurchase.vue';
    import useEscapeKey from '@/composable/useEscapeKey';

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
    });

    const currentMenu = defineModel('currentMenu');
    const isMenuActive = ref(false);

    watch(currentMenu, (newVal, oldVald) => {
        isMenuActive.value = newVal === props.indexMenu;
    });

    useEscapeKey(isMenuActive, () => {
        currentMenu.value = -1;
    });
    
    useClickOutside('.trigger-menu-editdelete', isMenuActive, () => {
        if(isMenuActive.value) {
            //alert('close');
            currentMenu.value = -1;
        }
        
    });

    function handleMenu(request) {
        switch(request) {
            case 'edit': {
                isMenuEditActive.value = !isMenuEditActive.value;
                currentMenu.value = -1;
                break;
            }
            case 'delete' : {
                isMenuDeleteActive.value = !isMenuDeleteActive.value;
                currentMenu.value = -1;
                break;
            }
            case 'cancel' : {
                //alert(isMenuDeleteActive.value);
                //isMenuDeleteActive.value = !isMenuDeleteActive.value;
                currentMenu.value = -1;
                break;
            }
        }
    }

    function toggleMenu () {
        //alert('alert');
        (currentMenu.value === props.indexMenu) ? currentMenu.value = -1 : currentMenu.value = props.indexMenu;
        //alert(props.idMenu);
    }
</script>