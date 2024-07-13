<template>
    <div class="font-main-font flex flex-col bg-main-bg w-full min-h-screen">
        <div class="ml-[calc(20px+70px+20px)] mr-custom-margin-main flex flex-col my-custom-margin-main">
            <h1 class="font-light flex justify-start text-[25px] text-white">Page Utilisateur</h1>
            <ContainerEditProfil />
            <ContainerEditEmail />
            <ContainerEditPass />
        </div>
    </div>
</template>



<script setup>
    import ContainerEditProfil from '@/component/container/ContainerEditProfil.vue'; 
    import ContainerEditEmail from '@/component/container/ContainerEditEmail.vue'; 
    import ContainerEditPass from '@/component/container/ContainerEditPass.vue'; 
</script>