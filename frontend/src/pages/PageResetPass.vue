<template>
    <main class="flex h-screen flex-col md:flex-row">
        <ContainerInformation :image="ImageResetPass" />
        <ContainerResetPass />
    </main>
</template>

<script setup>
    import ContainerResetPass from '@/components/container/ContainerResetPass.vue';
    import ContainerInformation from '@/components/container/ContainerInformation.vue';
    import ImageResetPass from '@/components/svgs/ImageResetPass.vue';
</script>