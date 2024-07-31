<template>
    <div class="flex h-screen flex-col md:flex-row">
        <ContainerInformation :image="ImageRegistration" heightMin="min-h-[791px]" />
        <ContainerNewAccount />
    </div>
</template>

<script setup>
    import ContainerNewAccount from '@/component/container/ContainerNewAccount.vue';
    import ContainerInformation from '@/component/container/ContainerInformation.vue';
    import ImageRegistration from '@/component/svgs/ImageRegistration.vue';
    
</script>





