<template>
    <main class="flex h-screen flex-col md:flex-row">
        <ContainerInformation :image="ImageResetPass" />
        <ContainerResetPass />
    </main>
</template>

<script setup>
    import ContainerResetPass from '@/component/container/ContainerResetPass.vue';
    import ContainerInformation from '@/component/container/ContainerInformation.vue';
    import ImageResetPass from '@/component/svgs/ImageResetPass.vue';
</script>