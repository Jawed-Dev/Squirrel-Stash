<template>
    <Transition
        :enter-active-class="`transition-transform ${durationIn}`"
        enter-from-class="transform translate-y-full"
        enter-to-class="transform translate-y-0"
        :leave-active-class="`transition-transform ${durationOut}`"
        leave-from-class="transform translate-y-0"
        leave-to-class="transform translate-y-full"
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