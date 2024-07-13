<template>
    <div class="pl-3 flex items-center w-full py-1 border-b 
        text-white font-light">
        <div>
            <span>{{ unicode }}</span>
        </div>
        <p 
        :id="id" 
        :class="`pl-1 ${extraClass}`"> {{ text }}
        </p>
    </div>
</template>

<script setup>
    const props = defineProps({
        unicode: {default: ''},
        text: { default: ''},
        extraClass: { default: ''},
        id: { default: ''},
    })
</script>