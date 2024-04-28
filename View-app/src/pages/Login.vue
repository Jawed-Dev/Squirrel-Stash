<template>
    <main class="flex h-[100vh]">
        <sectionCarousel />
        <section-login />
    </main>
</template>

<script setup>
    import HeaderComponent from '@/components/header/Header.vue';
    import SectionLogin from '@/components/auth/login/SectionLogin.vue';
    import SectionCarousel from '@/components/auth/login/SectionCarousel.vue';
</script>