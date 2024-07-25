<template>
    <div class="flex justify-end relative">
            <IconOptions @click="toggleMenu()" class="cursor-pointer trigger-menu-editdelete" :svg="iconOptions"/>

            <TransitionOpacity ref="elementTransition" :durationIn="'duration-500'" :durationOut="'duration-500'">
                <div v-if="isMenuActive" 
                    class="flex flex-col items-center absolute top-[-20px] left-[2vw] z-10 trigger-menu-editdelete
                    bg-main-gradient w-[100px] rounded-md overflow-hidden
                    shadow-black shadow-custom-main">
                    <p @click="handleMenu('edit')" class="hover:bg-custom-blue w-[100%] text-center cursor-pointer p-1 ">Modifier</p>
                    <p @click="handleMenu('delete')" class="hover:bg-custom-blue w-[100%] text-center cursor-pointer p-1">Supprimer</p>
                </div>
            </TransitionOpacity>
    </div>
    <TransitionOpacity :durationIn="'duration-500'" :durationOut="'duration-500'">
        <EditTransaction 
            v-if="isMenuEditActive" 
            v-model:isSuccessEdit="isSuccessEdit"
            width="sm:w-[25%] sm:min-w-[500px] w-full max-w-[550px]" 
            :indexMenu="indexMenu" 
            :infoTransaction="infoTransaction" 
            v-model:menuActive="isMenuEditActive"
        />
        <DeleteTransaction 
            v-if="isMenuDeleteActive" 
            v-model:isSuccessDelete="isSuccessDelete"
            width="w-[30%]" :indexMenu="indexMenu" 
            :infoTransaction="infoTransaction" 
            v-model:menuActive="isMenuDeleteActive"
        /> 
    </TransitionOpacity>

</template>


<script setup>

    // import
    import { ref, watch, defineAsyncComponent } from 'vue';

    import IconOptions from '@/component/svgs/IconOptions.vue';
    import TransitionOpacity from '@/component/transition/TransitionOpacity.vue';
    import useClickOutside from '@/composable/useClickOutSide';
    //import DeleteTransaction from '@/component/overlay/DeleteTransaction.vue';
    import useEscapeKey from '@/composable/useEscapeKey';
    import EditTransaction from '@/component/overlay/EditTransaction.vue';
    

    const DeleteTransaction = defineAsyncComponent(() => import('@/component/overlay/DeleteTransaction.vue'));

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
        infoTransaction: { default: {} }
    });

    const currentMenuEditDeleteTrs = defineModel('currentMenuEditDeleteTrs');
    const isMenuActive = ref(false);
    const isSuccessEdit = defineModel('isSuccessEdit');
    const isSuccessDelete = defineModel('isSuccessDelete');

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
                //alert(isMenuDeleteActive.value);
                //isMenuDeleteActive.value = !isMenuDeleteActive.value;
                currentMenuEditDeleteTrs.value = -1;
                break;
            }
        }
    }

    function toggleMenu () {
        //alert('alert');
        (currentMenuEditDeleteTrs.value === props.indexMenu) ? currentMenuEditDeleteTrs.value = -1 : currentMenuEditDeleteTrs.value = props.indexMenu;
        //alert(props.idMenu);
    }
</script>