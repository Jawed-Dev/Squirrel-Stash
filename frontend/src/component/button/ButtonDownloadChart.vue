<template>
    <button :class="`absolute right-3 ${top} font-light`" @click="downloadChart">Télécharger</button>
</template>

<script setup>
    const props = defineProps({
        canvas: {default:''},
        top: {default:'top-[-78px]'}
    });
    function downloadChart() {
        if (props.canvas) {
            const link = document.createElement('a');
            link.href = props.canvas.toDataURL('image/png');
            link.download = 'graphique.png';
            link.click();
        }
    }
</script>