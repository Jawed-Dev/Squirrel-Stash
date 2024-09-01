<template>
    <Transition
        :enter-active-class="`transition-all ${durationIn} ease-in`"
        enter-from-class="max-h-0"
        enter-to-class="max-h-screen"
        :leave-active-class="`transition-all ${durationIn} ease-out`"
        leave-from-class="max-h-screen"
        leave-to-class="max-h-0"
    >
    <slot></slot>
    </Transition>
</template>


<script setup>

    const props = defineProps({
        durationIn: { default: 'duration-300' },
        durationOut: { default: 'duration-0' }
    })

</script>