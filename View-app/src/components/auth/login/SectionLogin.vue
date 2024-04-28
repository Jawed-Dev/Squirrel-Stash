
<template>
    <section class="flex flex-col items-center font-main-font w-[50%] justify-center">
        <h1 class="text-black text-[25px]">Bienvenue !</h1>
        <p class="text-custom-gray  text-[14px]">dazaazdadaazaz</p>

        <form @submit.prevent="handleSubmit">

            <input-basic ref="inputEmailRef" :extraClass="'w-[389px] py-[6.5px]'" :titleInput="'Email'" />
            <input-basic ref="inputPassRef" :extraClass="'w-[389px] py-[6.5px]'" :titleInput="'Mot de passe'" />
     
            <div class="flex pt-[6px] px-[10px] justify-between">
                <div class="flex">
                    <input-checkbox :extraClass="''" ref="inputCheckBox" />
                    <a class="txt-main-blue font-medium text-[12px]" href="">Se rappeler de moi</a>
                </div>
                <a  class="text-main-blue font-medium text-[12px]" href="">Mot de passe oublié ?</a>
            </div>
                
            <button-component :extraClass="'w-[389px] py-[6.5px]'" :titleButton="'Se connecter'" />
            

            <div class="flex pt-[28px] gap-9 justify-center">
                <p class=" font-medium">Vous n'avez pas de compte ?</p> 
                <router-link to="/create-acc" class="text-main-blue  font-medium">S'inscrire</router-link>
            </div>
        </form>
    </section>
</template>


<script setup>
    import { ref, reactive } from 'vue';

    import InputBasic from '../../input/InputText.vue';
    import ButtonComponent from '../../button/ButtonBasic.vue';
    import inputCheckbox from '../../input/InputCheckbox.vue';
    import { useRouter } from 'vue-router';
    const router = useRouter();

    const inputEmailRef = ref(null);
    const inputPassRef = ref(null);

    const inputCheckBox = ref(null);

    function handleSubmit() {
        //alert(inputCheckBox.value.handleInput('getState'));

        const refEmail = inputEmailRef.value;
        const refPass = inputPassRef.value;

        //alert(refEmail.handleInput('getInputVal'));
        refEmail.handleInput('resetInput');

        //alert(refPass.handleInput('getInputVal'));
        refPass.handleInput('resetInput');

        router.push('/home');


    }

    

</script>

