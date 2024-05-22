<template>
    <Transition
        :enter-active-class="`transition-opacity ${durationIn}`"
        enter-from-class="opacity-0"
        enter-to-class="opacity-1"
        :leave-active-class="`transition-opacity ${durationOut}`"
        leave-from-class="opacity-1"
        leave-to-class="opacity-0"
    >
    <slot></slot>
    </Transition>
</template>


<script setup>

const props = defineProps({
    durationIn: { default: 'duration-300' },
    durationOut: { default: 'duration-300' }
})

</script>