<template>
    <section class="bg-main-gradient flex flex-col items-center font-main-font w-[50%] justify-center">
        <h1 class="text-[25px] text-white">Bienvenue !</h1>

        <form class="mt-[30px]" @submit.prevent="handleSubmit()">
            <div class="mt-[30px]">
                <label class="text-white font-light " for="login-mail">Votre adresse email</label>
                <InputBase 
                    id="login-mail" :underline="true" 
                    v-model="inputEmail" 
                    extraClass="shadow-black shadow-custom-lower rounded-[3px] border w-full py-1 text-white font-light mt-[2px]" 
                />
            </div>

            <div class="mt-[30px]">
                <label class="text-white font-light" for="login-pass">Votre mot de passe</label>
                <InputBase 
                    id="login-pass" :underline="true" 
                    v-model="inputPass" 
                    extraClass="shadow-black shadow-custom-lower rounded-[3px] border w-full w-full py-1 text-white font-light mt-[2px]"
                    type="password"
                />
            </div>
     
            <div class="flex pt-[6px] px-[10px] justify-between mt-[30px]">
                <div class="flex">
                    <inputCheckbox :extraClass="''" ref="inputCheckBox" />
                    <a class="txt-main-blue text-[13px] text-white font-light" href="">Rester connecté</a>
                </div>
                <a  class="text-main-blue font-light text-[13px]" href="">Mot de passe oublié ?</a>
            </div>
                
            <ButtonComponent :extraClass="'shadow-black shadow-custom-main w-[389px] py-[6.5px]'" :titleButton="'Se connecter'" />
            
            <div class="flex pt-[28px] gap-9 justify-center">
                <p class="text-white font-light">Vous n'avez pas de compte ?</p> 
                <router-link class="text-main-blue font-light" to="/inscription" >S'inscrire</router-link>
            </div>
        </form>
    </section>
</template>


<script setup>
    import { ref } from 'vue';
    import { useRouter } from 'vue-router';

    import InputBase from '@/component/input/InputBase.vue';
    import ButtonComponent from '@/component/button/ButtonBasic.vue';
    import inputCheckbox from '@/component/input/InputCheckbox.vue';
    import { getHandleLogin } from '@/composable/useBackendGetData';

    const router = useRouter();
    const inputEmail = ref(null);
    const inputPass = ref(null);
    const inputCheckBox = ref(null);

    async function handleSubmit() {
        const isSuccessLogin = await getHandleLogin(inputEmail.value, inputPass.value);
        inputEmail.value = '';
        inputPass.value = '';
        if(isSuccessLogin) router.push('/tableau-de-bord');
    }
</script>

