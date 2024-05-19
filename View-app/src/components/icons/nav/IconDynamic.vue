<template>
    <component :is="iconComponent" :svg="props.svg" :class="`${props.svg.color} p-1 shadow-custom-main`"/>
</template>


<script setup>
    // import
    import { ref, onMounted, shallowRef, watch } from 'vue';
    import { getIconByName } from '@/functions/icons/getIcon';

    // variables, props...
    const iconComponent = shallowRef(null);
    const props = defineProps({
        svg:  {default: {} },
        nameIcon: {default: ''}
    })

    async function loadIcon(name) {
        const module = await getIconByName(name);
        iconComponent.value = module.default;
    }
  
    onMounted(() => {
        loadIcon(props.nameIcon);
    });


    watch(() => props.nameIcon, (newName) => {
        loadIcon(newName);
    });


    // functions
    
</script>