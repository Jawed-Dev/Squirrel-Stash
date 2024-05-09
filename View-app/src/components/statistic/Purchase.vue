<template>
    <div class="flex items-center border-b py-6 border-[#38393b] text-white">
        <component :is="iconComponent" :svg="svg"  :class="`${props.svg.color} rounded-full p-3 shadow-custom-main mr-[20px]`"/>
        <p class="w-[150px] text-[15px] pr-4">{{infoPurchase.name}}</p>
        <p class="w-[150px] text-[15px] pr-4">{{infoPurchase.price}}€</p>
        <p class="w-[150px] text-[15px] pr-4">{{infoPurchase.date}}</p>
        <p class="w-[150px] text-[15px] pr-4">{{infoPurchase.iteration}}x</p>
        
        <div class="pl-[120px] relative">
            <Options @click="idMenuSelected" class="cursor-pointer icon-trigger-class" :svg="iconOptions"/>
            <TransitionOpacity :duration="'duration-500'">
                <div v-show="props.modelValue === props.idMenu" 
                    class="flex flex-col items-center absolute top-[-20px] left-[10px] z-10  
                    bg-main-gradient w-[100px] rounded-md overflow-hidden
                    shadow-black shadow-custom-test">
                    <p @click="handlePurchase('edit')" ref="iconOptionsEl" class="hover:bg-custom-blue w-[100%] text-center cursor-pointer p-1">Modifier</p>
                    <p @click="handlePurchase('delete')" class="hover:bg-custom-blue w-[100%] text-center cursor-pointer p-1">Supprimer</p>
                </div>
            </TransitionOpacity>
            <!-- <Transition
                enter-active-class="transition-opacity duration-500"
                enter-from-class="opacity-0"
                enter-to-class="opacity-1"
                leave-active-class="transition-opacity duration-500"
                leave-from-class="opacity-1"
                leave-to-class="opacity-0"
                >
                <div v-show="props.modelValue === props.idMenu" 
                    class="flex flex-col items-center absolute top-[-20px] left-[10px] z-10  
                    bg-main-gradient w-[100px] rounded-md overflow-hidden
                    shadow-black shadow-custom-test">
                    <p @click="handlePurchase('edit')" ref="iconOptionsEl" class="hover:bg-custom-blue w-[100%] text-center cursor-pointer p-1">Modifier</p>
                    <p @click="handlePurchase('delete')" class="hover:bg-custom-blue w-[100%] text-center cursor-pointer p-1">Supprimer</p>
                </div>
            </Transition> -->
            
        </div>
        
    </div>
</template>

<script setup>

    // import
    import { getIconByName } from '@/functions/icons/getIcon';
    import Options from '../svgs/Options.vue';
    import {ref, onMounted, onBeforeUnmount, shallowRef } from 'vue';
    import TransitionOpacity from '../transition/TransitionOpacity.vue';

    // variables, props, emit
    const props = defineProps({
        infoPurchase: { default: {} },
        svg: { default: {} }, 
        idMenu: { default: 0 },
        modelValue: { default: -1 },
        purchaseType: {default: 'standard'}
    });

    const iconComponent = shallowRef(null);

    const iconOptionsEl = ref(null);
    const emit = defineEmits(['update:modelValue']);
    
    const iconOptions = {
        fill: 'white',
        width: '30px',
        height: '30px',
    }
    
    // cycle de vie
    onMounted(async () => {
        const module = await getIconByName(props.svg.name);
        iconComponent.value = module.default;

        document.addEventListener('click', handleClickOutside, true);
    });

    onBeforeUnmount(() => {
        document.removeEventListener('click', handleClickOutside, true);
    });


    // fonctions
    function handlePurchase(request) {
        switch(request) {
            case 'edit': {
                emit('update:modelValue', -1)
                break;
            }
            case 'delete' : {
                emit('update:modelValue', -1)
                break;
            }
        }
    }

    function handleClickOutside(event) {
        if (!iconOptionsEl.value?.contains(event.target) && !event.target.closest('.icon-trigger-class')) {
        emit('update:modelValue', -1);
    }
    }

    function idMenuSelected () {
        //alert('alert');
        (props.modelValue === props.idMenu) ? emit('update:modelValue', -1) : emit('update:modelValue', props.idMenu); 
    }

    
    
</script>