<template>
    <div class="pl-[120px] relative">
            <IconOptions @click="toggleMenu()" class="cursor-pointer trigger-class-menu-editdelete" :svg="iconOptions"/>

            <TransitionOpacity :durationIn="'duration-500'" :durationOut="'duration-500'">
                <div v-show="modelCurrentMenu === props.indexMenu" 
                    class="flex flex-col items-center absolute top-[-20px] left-[10px] z-10 trigger-class-menu-editdelete
                    bg-main-gradient w-[100px] rounded-md overflow-hidden
                    shadow-black shadow-custom-main">
                    <p @click="handleMenu('edit')" class="hover:bg-custom-blue w-[100%] text-center cursor-pointer p-1 ">Modifier</p>
                    <p @click="handleMenu('delete')" class="hover:bg-custom-blue w-[100%] text-center cursor-pointer p-1">Supprimer</p>
                </div>
            </TransitionOpacity>
        </div>

        <EditPurchase v-model:menuActive="isMenuEditActive" />
</template>


<script setup>

    // import
    import { ref } from 'vue';

    import IconOptions from '../svgs/IconOptions.vue';
    import TransitionOpacity from '../transition/TransitionOpacity.vue';
    import useClickOutside from '@/composables/useClickOutSide';
    import EditPurchase from './EditPurchase.vue';

    // variables, props ...

    const isMenuEditActive = ref(false);

    const iconOptions = {
        fill: 'white',
        width: '30px',
        height: '30px',
    };

    const props = defineProps({
        indexMenu: { default: 0 },
    });
    

    const modelCurrentMenu = defineModel('currentMenu');

    // cycle de vie

    useClickOutside( () => {
        modelCurrentMenu.value = -1;
        //alert('test');
    }, '.trigger-class-menu-editdelete');


    // functions

    function handleMenu(request) {
        switch(request) {
            case 'edit': {
                isMenuEditActive.value = !isMenuEditActive.value;
                modelCurrentMenu.value = -1;
                break;
            }
            case 'delete' : {
                modelCurrentMenu.value = -1;
                break;
            }
            case 'cancel' : {
                modelCurrentMenu.value = -1;
                break;
            }
        }
    }

    function toggleMenu () {
        //alert('alert');
        (modelCurrentMenu.value === props.indexMenu) ? modelCurrentMenu.value = -1 : modelCurrentMenu.value = props.indexMenu;
        //alert(props.idMenu);
    }
</script>