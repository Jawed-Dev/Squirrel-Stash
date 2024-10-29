<template>
    <main class="absolute flex min-h-full w-full flex-col md:flex-row 
    bg-cover bg-center bg-no-repeat opacity-100">
        <ContainerInformation 
            :image="ImageLogin" 
            text="Connectez-vous pour faire des <span class='block'> économies avec <span class='text-main-blue'>Squirrel Stash</span> !</span>"/>
        <ContainerLogin/>
    </main>
</template>

<script setup>
    import ContainerLogin from '@/components/container/ContainerLogin.vue';
    import ContainerInformation from '@/components/container/ContainerInformation.vue';
    import ImageLogin from '@/components/svg/ImageLogin.vue';
</script>