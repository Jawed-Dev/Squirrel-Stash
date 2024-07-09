<template>
    <section class="bg-main-gradient flex flex-col items-center font-main-font w-[50%] justify-center shadow-black shadow-custom-main">
        <h1 class="text-[25px] text-white">Mot de passe oublié</h1>
        
        <form class="mt-[40px] w-[20vw]" @submit.prevent="handleSubmit()">
            <!-- Errors -->
            <div class="relative">
                <p class="absolute text-red-400">{{ computedFormatErrors }}</p>
                <p v-if="computedEmptyInputs.length > 0"></p>
            </div>
            <div class="mt-[30px] w-">
                <label class="text-white font-light text-[17px]" for="input-pass">Entrez votre mot de passe</label>
                <InputBase 
                    unicode="🔒"
                    id="input-pass" 
                    v-model="password" 
                    extraClass="" 
                    placeholder="Mot de passe"
                    type="password"
                />
            </div>
            <div class="mt-[30px]">
                <label class="text-white font-light text-[17px]" for="input-confirm-pass">Confirmez votre mot de passe</label>
                <InputBase 
                    unicode="🔒"
                    id="input-confirm-pass" 
                    v-model="confirmPassword" 
                    extraClass="" 
                    placeholder="Mot de passe"
                    type="password"
                />
            </div>
                
            <ButtonComponent :extraClass="'shadow-black shadow-custom-main w-full py-[6.5px] mt-[40px]'" :titleButton="'Modifier'" />
            
            <div class="flex mt-[20px] gap-9 justify-center">
                <p class="text-white font-light">Retour à la connexion</p> 
                <router-link class="text-main-blue font-light" to="/connexion" >Se connecter</router-link>
            </div>
        </form>
        
    </section>
</template>


<script setup>
    import { ref } from 'vue';
    import { useRouter, useRoute } from 'vue-router';
    import InputBase from '@/component/input/InputBase.vue';
    import ButtonComponent from '@/component/button/ButtonBasic.vue';
    import { updatePassword } from '@/composable/useBackendActionData';
    import { useErrorFormat, verifyResetPass } from '@/error/useHandleError';
    import { useMandatoryEmptyInputs } from '@/error/useMandatoryEmptyInputs';

    // props, variables
    const router = useRouter();
    const route = useRoute();
    const password = ref('');
    const confirmPassword = ref('');

    // Errors 
    const { computedEmptyInputs, stateEmptyInputs } = useMandatoryEmptyInputs([
        { name: 'password', ref: password },
        { name: 'confirmPassword', ref: confirmPassword },
    ]);
    const { stateFormatErrors, computedFormatErrors } = useErrorFormat(verifyResetPass, {
        password: {name: 'password', ref: password}, 
        confirmPassword: {name: 'confirmPassword', ref: confirmPassword}
    });

    // life cycle, functions
    async function handleSubmit() {
        if(isAnyErrorActive()) return;
        const isSuccessRequest = await updatePassword({
            resetPassToken: getTokenResetPass(),
            password: password.value
        });
        if(isSuccessRequest) {
            router.push('/connexion');
            resetInputs();
        }
    }

    function resetInputs() {
        password.value = '';
        confirmPassword.value = '';
    }

    function isAnyErrorActive() {
        return stateFormatErrors.value.length > 0 || stateEmptyInputs.value.length > 0;
    }

    function getTokenResetPass() {
        return route.query.token;
    }


</script>

