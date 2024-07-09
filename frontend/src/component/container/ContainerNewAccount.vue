<template>
    <section class="bg-main-gradient flex flex-col items-center font-main-font w-[50%] justify-center shadow-black shadow-custom-main">
        <h1 class="text-white text-[25px]">Inscription</h1>

        
        <form class="mt-[40px]" @submit.prevent="handleSubmit()">
            
            <div class="relative">
                <p class="absolute text-red-400">{{ computedFormatErrors }}</p>
                <p v-if="computedEmptyInputs.length > 0"></p>
            </div>
            <div class="flex justify-center w-full gap-5 mt-[30px]">
                <div class="w-[50%]">
                    <label class="text-white font-light" for="input-fname-crt-acc">Votre prénom</label>
                    <InputBase 
                        unicode="🛂"
                        v-model="firstName"
                        extraClass=""
                        id="input-fname-crt-acc"
                        placeholder="Entrez votre prénom"
                    />
                </div>

                <div class="w-[50%]">
                    <label class="text-white font-light" for="input-lname-crt-acc">Votre nom</label>
                    <InputBase 
                        unicode="🛂"
                        v-model="lastName"
                        extraClass=""
                        id="input-lname-crt-acc"
                        placeholder="Entrez votre nom"
                    />
                </div>
    
                
            </div>

            <div class="mt-[40px]">
                <label class="text-white font-light" for="input-email-crt-acc">Votre email</label>
                <InputBase 
                    unicode="📧"
                    v-model="email"
                    extraClass=""
                    id="input-email-crt-acc"
                    type="email"
                    placeholder="Entrez votre email"
                />
            </div>

            <div class="mt-[40px]">
                <label class="text-white font-light" for="input-pass-crt-acc">Votre mot de passe</label>
                <InputBase 
                    unicode="🔒"
                    v-model="password"
                    extraClass=""
                    id="input-pass-crt-acc"
                    placeholder="Entrez votre mot de passe"
                    type="password"
                />
            </div>

            <div class="mt-[40px]">
                <label class="text-white font-light " for="input-cpass-crt-acc">Confirmer votre mot de passe </label>
                <InputBase 
                    unicode="🔒"
                    v-model="confirmPassword"
                    extraClass=""
                    id="input-cpass-crt-acc"
                    placeholder="Entrez votre mot de passe"
                    type="password"
                />
            </div>

            <div class="flex mt-[20px] justify-center gap-1">
                <InputCheckbox v-model="confirmCheckbox" type="checkbox" />
                <a 
                    class="txt-main-blue text-[14px] text-white font-light" 
                    href="">
                    Accepter les règles et conditions d'utilisateurs
                </a>
            </div>

            <button-component :extraClass="'shadow-black shadow-custom-main w-full py-[6.5px]'" :titleButton="'S\'inscrire'" />

            <div class="flex pt-[28px] gap-9 justify-center">
                <p class="text-white font-light">Vous avez déjà un compte ?</p> 
                <router-link to="/" class="text-main-blue font-light">Se connecter</router-link>
            </div>
        </form>


    </section>
</template>


<script setup>
    import { ref, computed } from 'vue';
    import { useRouter } from 'vue-router';
    import InputBase from '@/component/input/InputBase.vue';
    import ButtonComponent from '@/component/button/ButtonBasic.vue';
    import { createAccount } from '@/composable/useBackendActionData';
    import { useErrorFormat, verifyCreateAccount } from '@/error/useHandleError';
    import { useMandatoryEmptyInputs } from '@/error/useMandatoryEmptyInputs';
    import InputCheckbox from '../input/InputCheckbox.vue';
    

    // props, variables ...
    const router = useRouter();
    const firstName = ref('');
    const lastName = ref('');
    const email = ref('');
    const password = ref('');
    const confirmPassword = ref('');
    const confirmCheckbox = ref(false);

    // Errors 
    const { computedEmptyInputs, stateEmptyInputs } = useMandatoryEmptyInputs([
        { name: 'firstName', ref: firstName },
        { name: 'lastName', ref: lastName },
        { name: 'email', ref: email },
        { name: 'password', ref: password },
        { name: 'confirmPassword', ref: confirmPassword },
        { name: 'confirmCheckbox', ref: confirmCheckbox }
    ]);

    const { stateFormatErrors, computedFormatErrors } = useErrorFormat(verifyCreateAccount, {
        firstName: {name: 'firstName', ref: firstName}, 
        lastName: {name: 'lastName', ref: lastName},
        email: {name: 'email', ref: email}, 
        password: {name: 'password', ref: password},
        confirmPassword: {name: 'confirmPassword', ref: confirmPassword}, 
        confirmCheckbox: {name: 'confirmCheckbox', ref: confirmCheckbox}
    });

    // life cycle / functions
    async function handleSubmit() {
        if(isAnyErrorActive()) return;
        const requestFetched = await createAccount({
            email: email.value,
            password: password.value,
            confirmPassword: confirmPassword.value,
            firstName: firstName.value,
            lastName: lastName.value
        });
        const isSuccessRequest = requestFetched?.isSuccessRequest;
        if(isSuccessRequest) {
            resetInputs();
            router.push('/connexion');
        }
    }

    function resetInputs() {
        email.value = '';
        password.value = '';
        confirmPassword.value = '';
        lastName.value = '';
        firstName.value = '';
        confirmCheckbox.value = false;
    }

    function isAnyErrorActive() {
        console.log(stateEmptyInputs.value, stateEmptyInputs.value.length);
        return stateFormatErrors.value.length > 0 || stateEmptyInputs.value.length > 0;
    }
    
</script>