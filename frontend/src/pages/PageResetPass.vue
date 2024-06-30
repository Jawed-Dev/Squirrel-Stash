<template>
    <main class="flex h-[100vh]">
        <ContainerInformation />
        <ContainerResetPass />
    </main>
</template>

<script setup>
    import ContainerResetPass from '@/component/container/ContainerResetPass.vue';
    import ContainerInformation from '@/component/container/ContainerInformation.vue';
</script>