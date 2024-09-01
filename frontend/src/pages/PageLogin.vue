<template>
    <main class="absolute flex min-h-full w-full flex-col md:flex-row 
    bg-cover bg-center bg-no-repeat opacity-100">
        <ContainerInformation :image="ImageLogin" />
        <ContainerLogin/>
    </main>
</template>

<script setup>
    import ContainerLogin from '@/components/container/ContainerLogin.vue';
    import ContainerInformation from '@/components/container/ContainerInformation.vue';
    import ImageLogin from '@/components/svg/ImageLogin.vue';
</script>