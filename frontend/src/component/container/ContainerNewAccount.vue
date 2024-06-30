<template>
    <section class="bg-main-gradient flex flex-col items-center font-main-font w-[50%] justify-center shadow-black shadow-custom-main">
        <h1 class="text-white text-[25px]">Inscription</h1>

        
        <form class="mt-[40px]" @submit.prevent="handleSubmit()">
            
            <div class="relative">
                <p class="absolute text-red-400">{{ computedErrors }}</p>
            </div>
            <div class="flex justify-center w-full gap-5 mt-[30px]">
                <div class="w-[50%]">
                    <label class="text-white font-light" for="input-fname-crt-acc">Votre prénom</label>
                    <InputBase 
                        v-model="firstName"
                        extraClass="
                        border-b-2 w-full pl-2 w-full py-1 text-white font-light mt-[5px]"
                        id="input-fname-crt-acc"
                        placeholder="Entrez votre prénom"
                    />
                </div>

                <div class="w-[50%]">
                    <label class="text-white font-light" for="input-lname-crt-acc">Votre nom</label>
                    <InputBase 
                        v-model="lastName"
                        extraClass="
                        border-b-2 w-full w-full pl-2 py-1 text-white font-light mt-[5px]"
                        id="input-lname-crt-acc"
                        placeholder="Entrez votre nom"
                    />
                </div>
    
                
            </div>

            <div class="mt-[40px]">
                <label class="text-white font-light" for="input-email-crt-acc">Votre email</label>
                <InputBase 
                    v-model="email"
                    extraClass=" 
                    border-b-2 w-full pl-2 w-full text-white font-light mt-[5px]"
                    id="input-email-crt-acc"
                    type="email"
                    placeholder="Entrez votre email"
                />
            </div>

            <div class="mt-[40px]">
                <label class="text-white font-light" for="input-pass-crt-acc">Votre mot de passe</label>
                <InputBase 
                    v-model="password"
                    extraClass="
                    border-b-2 w-full pl-2 w-full py-1 text-white font-light mt-[5px]"
                    id="input-pass-crt-acc"
                    placeholder="Entrez votre mot de passe"
                    type="password"
                />
            </div>

            <div class="mt-[40px]">
                <label class="text-white font-light " for="input-cpass-crt-acc">Confirmer votre mot de passe </label>
                <InputBase 
                    v-model="confirmPassword"
                    extraClass="
                    border-b-2 w-full pl-2 w-full py-1 text-white font-light mt-[5px]"
                    id="input-cpass-crt-acc"
                    placeholder="Entrez votre mot de passe"
                    type="password"
                />
            </div>

            <div class="flex mt-[40px] justify-center gap-1">
                <InputBase v-model="confirmCheckbox" type="checkbox" />
                <a 
                    class="txt-main-blue text-[14px] text-white font-light" 
                    href="">
                    Accepter les règles et conditions d'utilisateurs
                </a>
            </div>

            <button-component :extraClass="'shadow-black shadow-custom-main w-[389px] py-[6.5px]'" :titleButton="'S\'inscrire'" />

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
    import { createAccount } from '@/composable/useBackendSetData';
    import { verifyCreateAccount } from '@/error/useHandleError';
    
    
    // props, variables ...
    const router = useRouter();
    const firstName = ref('');
    const lastName = ref('');
    const email = ref('');
    const password = ref('');
    const confirmPassword = ref('');
    const confirmCheckbox = ref(false);

    // Errors 
    const stateErrors = ref([]);

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
        confirmCheckbox = false;
    }

    
    const computedErrors = computed(() => {
        const isError = verifyCreateAccount({
            email: email.value,
            password: password.value,
            firstName: firstName.value,
            lastName: lastName.value,
            confirmPassword: confirmPassword.value,
            checkbox: confirmCheckbox.value
        });
        if(isError) {
            stateErrors.value = isError;
            return isError[0].message;
        }
        else stateErrors.value = [];
    });

    function isAnyErrorActive() {
        return stateErrors.value.length > 0;
    }
    
</script>