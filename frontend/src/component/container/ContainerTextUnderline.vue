<template>
    <div :class="`transition-all duration-500 border-2 ${colorBorder} pl-2 flex items-center w-full rounded-lg py-[1px]
        text-white font-light`">
        <div v-show="iconName">
            <!-- <span>{{ unicode }}</span> -->
            <UseIconLoader class="bg-transparent " :nameIcon="iconName" :svg="{width:'20px', fill:'rgba(255, 255, 255, 0.8)'}"  />
        </div>
        <p 
        :id="id" 
        :class="`bg-transparent text-[15px] p-1  pl-2 ${extraClass}`"> {{ text }}
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
        return (props.text) ? "border-custom-green" : "hover:border-custom-blue border-custom-gray-2";
    });
</script>