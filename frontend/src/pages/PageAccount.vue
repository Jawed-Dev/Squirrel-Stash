<template>
    <div class="font-main flex flex-col bg-main-bg w-full min-h-screen pb-[calc(50px)] md:pb-0">
        <div class="mx-1 md:ml-[calc(20px+65px+20px)] xl:ml-[calc(30px+75px+30px)] md:mr-[20px] xl:mr-[30px] flex flex-col my-5">
            <h1 class="font-light flex justify-start text-[25px] text-white">Mon Compte</h1>
            <ContainerEditProfil />
            <ContainerEditEmail />
            <ContainerEditPass />
        </div>
    </div>
</template>

<script setup>
    import ContainerEditProfil from '@/components/container/ContainerEditProfil.vue'; 
    import ContainerEditEmail from '@/components/container/ContainerEditEmail.vue'; 
    import ContainerEditPass from '@/components/container/ContainerEditPass.vue'; 
</script>