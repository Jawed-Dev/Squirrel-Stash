<template>
    <main class="flex h-[100vh]">
        <sectionCarousel />
        <section-login />
    </main>
</template>

<script setup>
    import SectionLogin from '@/component/auth/login/SectionLogin.vue';
    import SectionCarousel from '@/component/auth/login/SectionCarousel.vue';
</script>