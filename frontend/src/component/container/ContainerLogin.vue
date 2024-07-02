<template>
    <section class="bg-main-gradient flex flex-col items-center font-main-font w-[50%] justify-center shadow-black shadow-custom-main">
        <h1 class="text-[25px] text-white">Bienvenue !</h1>

        <form class="mt-[40px]" @submit.prevent="handleSubmit()">

            <!-- Errors -->
            <div class="relative">
                <p class="absolute text-red-400">{{ computedFormatErrors }}</p>
                <p v-if="computedEmptyInputs.length > 0"></p>
            </div>

            <div class="mt-[30px]">
                <label class="text-white font-light " for="login-mail">Votre adresse email</label>
                <InputBase 
                    id="login-mail" 
                    v-model="email" 
                    extraClass="border-b-2 w-full py-1 text-white font-light mt-[2px]"
                    type="email"
                    placeholder="Entrez votre email"
                />
            </div>

            <div class="mt-[40px]">
                <label class="text-white font-light" for="login-pass">Votre mot de passe</label>
                <InputBase 
                    id="login-pass" 
                    v-model="password" 
                    extraClass="border-b-2 w-full py-1 text-white font-light mt-[2px]"
                    type="password"
                    placeholder="Entrez votre mot de passe"
                />
            </div>
     
            <div class="mt-[40px] flex pt-[6px] px-[10px] justify-between ">
                <div class="flex gap-1">
                    <InputBase :extraClass="''" v-model="confirmCheckbox" type="checkbox" />
                    <a class="txt-main-blue text-[13px] text-white font-light" href="">Se rappeler de moi</a>
                </div>
                <router-link class="text-main-blue font-light text-[13px]" to="/mot-de-passe-oublie">Mot de passe oublié ?</router-link>
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
    import { ref, computed } from 'vue';
    import { useRouter } from 'vue-router';
    import InputBase from '@/component/input/InputBase.vue';
    import ButtonComponent from '@/component/button/ButtonBasic.vue';
    import { getHandleLogin } from '@/composable/useBackendGetData';
    import { verifyLogin, useErrorFormat } from '@/error/useHandleError';
    import { useMandatoryEmptyInputs } from '@/error/useMandatoryEmptyInputs';

    // props, variables
    const router = useRouter();
    const email = ref('');
    const password = ref('');
    const confirmCheckbox = ref(false);

    // Errors 
    const { computedEmptyInputs, stateEmptyInputs } = useMandatoryEmptyInputs([
        { name: 'email', ref: email },
        { name: 'password', ref: password },
    ]);
    const { stateFormatErrors, computedFormatErrors } = useErrorFormat(verifyLogin, {
        email: {name: 'email', ref: email}, 
        password: {name: 'password', ref: password}
    });

    // life cycle, functions
    async function handleSubmit() {
        if(isAnyErrorActive()) return;
        const isSuccessLogin = await getHandleLogin(email.value, password.value);
        if(isSuccessLogin) {
            router.push('/tableau-de-bord');
            resetInputs();
        }
    }
    
    function resetInputs() {
        email.value = '';
        password.value = '';
        confirmCheckbox.value = false;
    }

    function isAnyErrorActive() {
        console.log(stateFormatErrors.value, stateEmptyInputs.value);
        return stateFormatErrors.value.length > 0 || stateEmptyInputs.value.length > 0;
    }
</script>
