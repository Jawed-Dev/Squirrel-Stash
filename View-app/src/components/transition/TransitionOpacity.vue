<template>
    <Transition
        :enter-active-class="`transition-opacity ${duration}`"
        enter-from-class="opacity-0"
        enter-to-class="opacity-1"
        :leave-active-class="`transition-opacity ${duration}`"
        leave-from-class="opacity-1"
        leave-to-class="opacity-0"
    >
    <slot></slot>
    </Transition>
</template>


<script setup>

const props = defineProps({
    duration: { default: 'duration-300' }
})

</script>