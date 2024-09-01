<template>
    <component v-if="componentLoaded"
        :is="iconComponent" 
        :svg="svg" 
        :class="`${svg.color} ${extraClass}`"
    />
    <component v-else
        :is="IconInvisible" 
        :svg="svg" 
        :class="`${svg.color} ${extraClass}`"
    />
</template>


<script setup>
    // import
    import { onMounted, ref, shallowRef, watch } from 'vue';
    import { getIconByName } from '@/svg/getIcon';
    import IconInvisible from '@/components/svgs/IconInvisible.vue';

    // variables, props...
    const componentLoaded = ref(false);
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
        if(lastRequestedIcon.value === name) { 
            iconComponent.value = module.default;
            componentLoaded.value = true;
        }
    }
    onMounted(async () => {
        await loadIcon(props.nameIcon);
    });
    watch(() => props.nameIcon, async (newName) => {
        await loadIcon(newName);
    });
</script>