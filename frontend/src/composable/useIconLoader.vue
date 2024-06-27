<template>
    <component :is="iconComponent" :svg="svg" :class="`${svg.color} ${extraClass}`"/>
</template>


<script setup>
    // import
    import { onMounted, shallowRef, watch } from 'vue';
    import { getIconByName } from '@/svg/getIcon';

    // variables, props...
    const iconComponent = shallowRef(null);
    const props = defineProps({
        svg:  {default: {} },
        nameIcon: {default: ''},
        extraClass: {default: ''}
    })

    // life cycle, functions
    async function loadIcon(name) {
        const module = await getIconByName(name);
        iconComponent.value = module.default;
    }
    onMounted(() => {
         loadIcon(props.nameIcon);
    });
    
    watch(() => props.nameIcon, async (newName) => {
        if(newName) loadIcon(newName);
    });
</script>