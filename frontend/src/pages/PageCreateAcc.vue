<template>
    <div class="flex h-screen flex-col md:flex-row">
        <ContainerInformation :image="ImageRegistration" heightMin="min-h-[791px]" />
        <ContainerNewAccount />
    </div>
</template>

<script setup>
    import ContainerNewAccount from '@/components/container/ContainerNewAccount.vue';
    import ContainerInformation from '@/components/container/ContainerInformation.vue';
    import ImageRegistration from '@/components/svgs/ImageRegistration.vue';
    
</script>





