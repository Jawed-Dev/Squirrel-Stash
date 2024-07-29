<template>
    <main class="flex h-screen flex-col lg:flex-row">
        <ContainerCarousel />
        <ContainerLogin />
    </main>
</template>

<script setup>
    import ContainerLogin from '@/component/container/ContainerLogin.vue';
    import ContainerCarousel from '@/component/container/ContainerCarousel.vue';
</script>