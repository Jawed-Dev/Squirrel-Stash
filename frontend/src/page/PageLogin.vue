<template>
    <main class="flex h-screen flex-col md:flex-row">
        <ContainerInformation :image="ImageLogin" />
        <ContainerLogin/>
    </main>
</template>

<script setup>
    import ContainerLogin from '@/component/container/ContainerLogin.vue';
    import ContainerInformation from '@/component/container/ContainerInformation.vue';
    import ImageLogin from '@/component/svgs/ImageLogin.vue';

</script>