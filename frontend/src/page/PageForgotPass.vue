<template>
    <main class="flex h-screen flex-col md:flex-row">
        <ContainerInformation :image="ImageForgotPass" heightMin="min-h-[520px]" />
        <ContainerForgotPass />
    </main>
</template>

<script setup>
    import ContainerForgotPass from '@/component/container/ContainerForgotPass.vue';
    import ContainerInformation from '@/component/container/ContainerInformation.vue';
    import ImageForgotPass from '@/component/svgs/ImageForgotPass.vue';
</script>