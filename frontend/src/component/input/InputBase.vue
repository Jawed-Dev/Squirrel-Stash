<template>
        <div class="pl-3 flex items-center w-full" :class="`py-1 ${border} text-white font-light`">
            <div>
                <span>{{ unicode }}</span>
            </div>
            <input
                @input="onInput" 
                
                :class="`${props.extraClass} bg-transparent pl-1 focus:outline-none`"
                :value="modelValue"
                :type="type"
                :placeholder="placeholder"
                :id="props.id"
            >
        </div>
</template>

<script setup>
    import { computed } from 'vue';
    // variables, props, ...
    const model = defineModel()

    

    const props = defineProps({
        extraClass: { default:'' },
        modelValue: { default:''},
        type: {default: 'text'},
        placeholder: {default: ''},
        width: {default:''},
        id: {default:''},
        unicode: {default:''},
        borderHidden: {default: false}
    });
   
    // life cycle functions
    function onInput(event) {
        model.value = event.target.value;
        //console.log(event.target.value, typeof(event.target.value));
    }

    const border = computed(() => {
        return (!props.borderHidden) ? 'border-b' : 'border-none';
    });
</script>


