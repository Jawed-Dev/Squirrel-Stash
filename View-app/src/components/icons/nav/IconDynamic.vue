<template>
    <component :is="iconComponent" :svg="props.svg" :class="`${props.svg.color} rounded-lg p-3 shadow-custom-main`"/>
</template>


<script setup>
    // import
    import { ref, onMounted, shallowRef } from 'vue';

    // variables, props...
    const iconComponent = shallowRef(null);
    const props = defineProps({
        svg:  {default: {} }
    })

    // cycle de vie
    onMounted(async () => {
        const module = await getIconByName('restaurant');
        iconComponent.value = module.default;
    });



    // functions
    
</script>