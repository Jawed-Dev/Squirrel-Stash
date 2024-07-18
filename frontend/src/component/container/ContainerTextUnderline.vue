<template>
    <div :class="`border transition-all duration-500 ${colorBorder} pl-1 flex items-center w-full mt-1 py-[1px]
        text-white font-light`">
        <div v-show="iconName">
            <!-- <span>{{ unicode }}</span> -->
            <UseIconLoader :nameIcon="iconName" :svg="{width:'20px', fill:'rgba(255, 255, 255, 0.8)'}"  />
        </div>
        <p 
        :id="id" 
        :class="`text-[15px] p-1 ${extraClass}`"> {{ text }}
        </p>
    </div>
</template>

<script setup>
    import { computed } from 'vue';
    import UseIconLoader from '@/composable/useIconLoader.vue';

    const props = defineProps({
        unicode: {default: ''},
        text: { default: ''},
        extraClass: { default: ''},
        id: { default: ''},
        borderHidden: { default: ''},
        iconName: {default:''}
    });

    const colorBorder = computed(() => {
        if(props.borderHidden) return '';
        return (props.text) ? "border-custom-blue" : "hover:border-custom-blue border-custom-gray-2";
    });
</script>