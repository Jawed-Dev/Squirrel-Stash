<template>
    <Transition 
        :enter-active-class="`swing-in transition-all ${durationIn} ease-out`"  
        enter-from-class="opacity-0 translate-x-10 translate-y-2"  
        enter-to-class="opacity-1 translate-x-0 translate-y-0" 
        
        :leave-active-class="`transition-all ${durationOut}`"  
        leave-from-class="opacity-1"
        leave-to-class="opacity-0"
    >
        <slot></slot>
    </Transition>
</template>

<script setup>

    const props = defineProps({
        durationIn: { default: 'duration-300' },
        durationOut: { default: 'duration-100' }
    })

</script>