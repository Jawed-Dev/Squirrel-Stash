<template>
    <main class="flex h-[100vh]">
        <ContainerCarousel />
        <ContainerLogin />
    </main>
</template>

<script setup>
    import ContainerLogin from '@/component/container/ContainerLogin.vue';
    import ContainerCarousel from '@/component/container/ContainerCarousel.vue';
</script>