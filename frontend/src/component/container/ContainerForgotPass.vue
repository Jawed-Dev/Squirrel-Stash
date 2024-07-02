<template>
    <section class="bg-main-gradient flex flex-col items-center font-main-font w-[50%] justify-center shadow-black shadow-custom-main">
        <h1 class="text-[25px] text-white">Mot de passe oublié</h1>

        
        
        <form class="mt-[40px]" @submit.prevent="handleSubmit()">
            <!-- Errors -->
            <div class="relative">
                <p class="absolute text-red-400">{{ computedFormatErrors }}</p>
                <p v-if="computedEmptyInputs.length > 0"></p>
            </div>
            <div class="mt-[30px]">
                <label class="text-white font-light text-[17px]" for="login-mail">Entrez votre addresse email</label>
                <InputBase 
                    id="login-mail" 
                    v-model="email" 
                    extraClass="border-b-2 w-full py-1 text-white font-light mt-[2px]" 
                    placeholder="Adresse email"
                    type="mail"
                />
            </div>
                
            <ButtonComponent :extraClass="'shadow-black shadow-custom-main w-full py-[6.5px] mt-[40px]'" :titleButton="'Envoyer'" />
            
            <div class="flex mt-[20px] gap-9 justify-center">
                <p class="text-white font-light">Retour à la connexion</p> 
                <router-link class="text-main-blue font-light" to="/connexion" >Se connecter</router-link>
            </div>
        </form>
    </section>
</template>


<script setup>
    import { ref, computed } from 'vue';
    import { useRouter } from 'vue-router';
    import InputBase from '@/component/input/InputBase.vue';
    import ButtonComponent from '@/component/button/ButtonBasic.vue';
    import { sendResetPass } from '@/composable/useBackendSetData';
    import { useErrorFormat, verifyForgotPass } from '@/error/useHandleError';
    import { useMandatoryEmptyInputs } from '@/error/useMandatoryEmptyInputs';

    
    // props, variables
    const router = useRouter();
    const email = ref('');

    // Errors 
    const { computedEmptyInputs, stateEmptyInputs } = useMandatoryEmptyInputs([
        { name: 'email', ref: email },
    ]);
    const { stateFormatErrors, computedFormatErrors } = useErrorFormat(verifyForgotPass, {
        email: {name: 'email', ref: email}, 
    });


    // life cycle, functions
    async function handleSubmit() {
        if(isAnyErrorActive()) return;
        const isSuccessLogin = await sendResetPass(email.value);
        if(isSuccessLogin) {
            // router.push('/tableau-de-bord');
            resetInputs();
        }
    }
    function resetInputs() {
        email.value = '';
    }

    function isAnyErrorActive() {
        return stateFormatErrors.value.length > 0 || stateEmptyInputs.value.length > 0;
    }
</script>

