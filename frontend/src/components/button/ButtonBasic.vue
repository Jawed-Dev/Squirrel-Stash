<template>
    <button :class="`bg-main-blue ${extraClass} rounded-lg text-white mt-5`">{{ titleButton }}</button>
</template>

<script setup> 

const props = defineProps(['titleButton', 'extraClass']);

</script>
