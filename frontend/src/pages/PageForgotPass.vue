<template>
    <main class="flex h-screen flex-col md:flex-row">
        <ContainerInformation :image="ImageForgotPass" heightMin="min-h-[520px]" />
        <ContainerForgotPass />
    </main>
</template>

<script setup>
    import ContainerForgotPass from '@/components/container/ContainerForgotPass.vue';
    import ContainerInformation from '@/components/container/ContainerInformation.vue';
    import ImageForgotPass from '@/components/svgs/ImageForgotPass.vue';
</script>