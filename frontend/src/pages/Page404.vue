<template>
    <div class="w-full flex flex-col justify-center items-center min-h-screen bg-main-bg text-white font-main gap-10">
        <p class="text-4xl uppercase">Page introuvable</p>
        <Image404 :svg="{width:'400px'}" />
        <a class="text-3xl uppercase text-blue-500 hover:text-blue-400" href="/connexion">< Retour</a>
    </div>
</template>

<script setup>
    import Image404 from '@/components/svgs/Image404.vue';
</script>