<template>
    <div class="flex items-center border-b py-6 border-[#38393b] text-white">
        <IconDynamic :nameIcon="props.nameIcon"  :svg="svg" :class="`${props.svg.color} rounded-full p-3 shadow-custom-main mr-[20px]`"/>
        <p class="w-[150px] text-[15px] pr-4">{{infoPurchase.name}}</p>
        <p class="w-[150px] text-[15px] pr-4">{{infoPurchase.price}}€</p>
        <p class="w-[150px] text-[15px] pr-4">{{infoPurchase.date}}</p>
        <p class="w-[150px] text-[15px] pr-4">{{infoPurchase.iteration}}x</p>
        
        <div class="pl-[120px] relative">
            <Options @click="idMenuSelected" class="cursor-pointer trigger-class-menu-purchase" :svg="iconOptions"/>

            <TransitionOpacity :durationIn="'duration-500'" :durationOut="'duration-500'">
                <div v-show="props.modelValue === props.idMenu" 
                    class="flex flex-col items-center absolute top-[-20px] left-[10px] z-10 trigger-class-menu-purchase
                    bg-main-gradient w-[100px] rounded-md overflow-hidden
                    shadow-black shadow-custom-test">
                    <p @click="handleMenuPurchase('edit')" class="hover:bg-custom-blue w-[100%] text-center cursor-pointer p-1">Modifier</p>
                    <p @click="handleMenuPurchase('delete')" class="hover:bg-custom-blue w-[100%] text-center cursor-pointer p-1 mb-2 border-b">Supprimer</p>
                    <p @click="handleMenuPurchase('cancel')" class="hover:bg-custom-blue w-[100%] text-center cursor-pointer p-1">Annuler</p>
                </div>
            </TransitionOpacity>
        </div>


        <MenuPurchase :menuActive="isMenuPurchActive" />
        
    </div>
</template>

<script setup>

    // import
    import Options from '../svgs/IconOptions.vue';
    import {ref, onMounted, onBeforeUnmount, shallowRef } from 'vue';
    import TransitionOpacity from '../transition/TransitionOpacity.vue';
    import IconDynamic from '../icons/nav/IconDynamic.vue';
    import useClickOutside from '@/composables/useClickOutSide';

    import MenuPurchase from '../overlay/MenuPurchase.vue';
    

    // variables, props, emit
    const props = defineProps({
        infoPurchase: { default: {} },
        svg: { default: {} }, 
        idMenu: { default: 0 },
        modelValue: { default: -1 },
        purchaseType: {default: 'standard'},
        nameIcon: {default: ''}
    });

    const isMenuPurchActive = ref(false);

    const emit = defineEmits(['update:modelValue']);
    
    const iconOptions = {
        fill: 'white',
        width: '30px',
        height: '30px',
    }
    
    // cycle de vie

    useClickOutside( () => {
        emit('update:modelValue', -1);
    }, '.trigger-class-menu-purchase');


    // functions
    function handleMenuPurchase(request) {
        switch(request) {
            case 'edit': {
                isMenuPurchActive.value = !isMenuPurchActive.value;
                emit('update:modelValue', -1)
                break;
            }
            case 'delete' : {
                emit('update:modelValue', -1)
                break;
            }
            case 'cancel' : {
                emit('update:modelValue', -1)
                break;
            }
        }
    }

    
    

    // function handleClickOutside(event) {
    //     if (!event.target.closest('.trigger-class-menu')) emit('update:modelValue', -1);
    // }

    function idMenuSelected () {
        //alert('alert');
        (props.modelValue === props.idMenu) ? emit('update:modelValue', -1) : emit('update:modelValue', props.idMenu); 
    }


</script>