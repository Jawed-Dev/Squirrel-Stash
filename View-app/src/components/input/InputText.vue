
<template>
    <div class="">
        <p class="">{{ props.titleInput }}</p>
        <input class=""
            v-model="text" type="text" 
            :class="`border rounded-md border-custom-gray pl-2 ${extraClass}`" 
            id="input-mail"
        >
    </div>
</template>



<script setup>
    import { useText } from '@/composable/useText';

    const props = defineProps(['titleInput', 'extraClass']);

    function handleInput(action, param = null) {
        switch(action) {
            case 'getInputVal' : {
                return text.value;
            }
            case 'resetInput' : {
                resetText();
                break;
            }
            case 'setInputVal' : {
                updateText(param);
                break;
            }
        }
    }

    const { updateText, text, resetText } = useText(props.modelValue);

    defineExpose({
        handleInput
    });
</script>


