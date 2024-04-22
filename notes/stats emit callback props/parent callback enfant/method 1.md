
<template>
    <div class="">
        <p class="">{{ props.titleInput }}</p>
        <input 
            :value="text" @input="handleInput" type="text" 
            class="border rounded-md border-custom-gray w-[347px] py-[6.5px] pl-2" 
            id="input-mail"
        >
    </div>
</template>



<script setup>
    import { defineProps, defineEmits, defineExpose   } from 'vue';
    import { useText } from '@/composables/useText';

    const props = defineProps(['titleInput', 'modelValue']);
    const emit = defineEmits(['update:modelValue']);

    const { updateText, text, resetText } = useText(props.modelValue);

    function handleInput(event) {
        updateText(event.target.value);
        emit('update:modelValue', event.target.value);
    }

    defineExpose({
        updateText,
        resetText
    });
</script>


<!-- ////////////// -->

// useText.js
import { ref } from 'vue';

export function useText(initialValue = '') {
    const text = ref(initialValue);

    function updateText(newValue) {
        text.value = newValue;
    }

    function resetText() {
        text.value = '';
    }

    return { text, updateText, resetText };
}



<!-- ////////////// -->



<template>
    <section class="flex flex-col items-center font-main-font">
        <h1 class="text-black text-[25px]">Bienvenue !</h1>
        <p class="text-custom-gray  text-[14px]">dazaazdadaazaz</p>

        <form @submit.prevent="handleSubmit">

            <input-component class="" ref="resetInputMail"  v-model="formValues.email" :titleInput="'Email'" />
            <input-component   ref="resetInputPassword"  v-model="formValues.password" :titleInput="'Mot de passe'" />
     
            <div class="flex pt-[6px] gap-8">
                <div class="flex">
                    <input-checkbox />
                    <a class="txt-main-blue font-medium text-[12px]" href="">Se rappeler de moi</a>
                </div>
                <a  class="text-main-blue font-medium text-[12px]" href="">Mot de passe oublié ?</a>
            </div>
            
            <button-component class="" :titleButton="'Se connecter'" />
            

            <div class="flex pt-[28px] gap-9">
                <p class=" font-medium">Vous n'avez pas de compte ?</p> 
                <a class="text-main-blue  font-medium" href="">S'inscrire</a>
            </div>
        </form>
    </section>
</template>


<script setup>
    import { ref, reactive  } from 'vue';

    import InputComponent from '../input/InputComponent.vue';
    import ButtonComponent from '../ButtonComponent.vue';
    import inputCheckbox from '../input/InputCheckbox.vue';


    const resetInputMail = ref(null);
    const resetInputPassword = ref(null);

    const formValues = reactive({
        email: '',
        password: ''
    });

    function handleSubmit() {
        resetInputMail.value.updateText('Wesh');
        resetInputPassword.value.resetText();
    }

    

</script>

