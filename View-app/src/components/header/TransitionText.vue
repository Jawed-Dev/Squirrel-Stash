<template>
    <Transition
        enter-active-class="transition-opacity duration-1000"
        enter-from-class="opacity-0"
        enter-to-class="opacity-1000"
        leave-active-class="transition-opacity duration-0"
        leave-from-class="opacity-0"
        leave-to-class="opacity-0"
    > 
        <p v-if="props.condition" class="w-[150px] absolute right-[0px] top-1 pl-3 flex items-center text-[14px] text-white">{{ props.text }}</p>
    </Transition>
</template>

<script setup>
    const props = defineProps({
        condition: { default: false},
        text: { default: ''}
    })
</script>