<template>
    <main class="absolute flex min-h-full w-full flex-col md:flex-row 
    bg-main-gradient lg:bg-[url('/Cloudy.svg')] bg-cover bg-center bg-no-repeat opacity-100">
        <ContainerInformation :image="ImageLogin" />
        <ContainerLogin/>
    </main>
</template>

<script setup>
    import ContainerLogin from '@/component/container/ContainerLogin.vue';
    import ContainerInformation from '@/component/container/ContainerInformation.vue';
    import ImageLogin from '@/component/svgs/ImageLogin.vue';
</script>