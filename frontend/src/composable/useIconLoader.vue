<template>
    <component :is="iconComponent" :svg="svg" :class="`${svg.color} ${extraClass}`"/>
</template>


<script setup>
    // import
    import { onMounted, ref, shallowRef, watch } from 'vue';
    import { getIconByName } from '@/svg/getIcon';

    // variables, props...
    const iconComponent = shallowRef(null);
    const props = defineProps({
        svg:  {default: {} },
        nameIcon: {default: ''},
        extraClass: {default: ''}
    });
    const lastRequestedIcon = ref('');

    // life cycle, functions
    async function loadIcon(name) {
        lastRequestedIcon.value = name;
        const module = await getIconByName(name);
        if(lastRequestedIcon.value === name) iconComponent.value = module.default;
    }
    onMounted(() => {
         loadIcon(props.nameIcon);
    });
    
    watch(() => props.nameIcon, async (newName) => {
        loadIcon(newName);
        //alert(newName);
    });
</script>