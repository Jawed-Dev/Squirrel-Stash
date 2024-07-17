<template>
    <section class="bg-main-gradient flex flex-col items-center font-main-font w-[50%] justify-center shadow-black shadow-custom-main">
        <h1 class="text-white text-[25px]">Inscription</h1>
        <form class="w-1/2 mt-[40px]" @submit.prevent="handleSubmit()">
            <!-- Errors -->
            <div class="relative">
                <p class="text-sm font-light absolute text-red-300">{{ textError }}</p>
            </div>

            <div class="flex justify-center w-full gap-5 mt-[30px]">
                <div>
                    <label class="text-white font-light" for="input-fname-crt-acc">Votre prénom</label>
                    <InputBase 
                        unicode="🛂"
                        v-model="firstName"
                        v-model:stateError="errorInput.firstName"
                        extraClass=""
                        id="input-fname-crt-acc"
                        placeholder="Prénom"
                        validFormat="firstName"
                    />
                </div>

                <div>
                    <label class="text-white font-light" for="input-lname-crt-acc">Votre nom</label>
                    <InputBase 
                        unicode="🛂"
                        v-model="lastName"
                        v-model:stateError="errorInput.lastName"
                        extraClass=""
                        id="input-lname-crt-acc"
                        placeholder="Nom"
                        validFormat="lastName"
                    />
                </div>
    
                
            </div>

            <div class="mt-[40px]">
                <label class="text-white font-light" for="input-email-crt-acc">Votre email</label>
                <InputBase 
                    unicode="📧"
                    v-model="email" 
                    v-model:stateError="errorInput.email"
                    extraClass=""
                    id="input-email-crt-acc"
                    type="email"
                    placeholder="exemple.domaine.com"
                    validFormat="email"
                    
                />
            </div>

            <div class="mt-[40px]">
                <label class="text-white font-light" for="input-pass-crt-acc">Votre mot de passe</label>
                <InputBase 
                    unicode="🔒"
                    v-model="password"
                    v-model:stateError="errorInput.password"
                    extraClass=""
                    id="input-pass-crt-acc"
                    placeholder="Mot de passe"
                    type="password"
                    validFormat="password"
                />
            </div>

            <div class="mt-[40px]">
                <label class="text-white font-light " for="input-cpass-crt-acc">Confirmer votre mot de passe </label>
                <InputBase 
                    unicode="🔒"
                    v-model="confirmPassword"
                    v-model:stateError="errorInput.confirmPassword"
                    extraClass=""
                    id="input-cpass-crt-acc"
                    placeholder="Mot de passe"
                    type="password"
                    validFormat="password"
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
    import { ref, computed, reactive } from 'vue';
    import { useRouter } from 'vue-router';
    import InputBase from '@/component/input/InputBase.vue';
    import ButtonComponent from '@/component/button/ButtonBasic.vue';
    import { createAccount } from '@/composable/useBackendActionData';
    import InputCheckbox from '../input/InputCheckbox.vue';
    import { isAnyMandatInputEmpty, isAnyInputError, TYPE_SUBMIT_ERROR } from '@/error/useHandleError';
    
    // props, variables ...
    const router = useRouter();
    const firstName = ref('');
    const lastName = ref('');
    const email = ref('');
    const password = ref('');
    const confirmPassword = ref('');
    const confirmCheckbox = ref(false);

    const errorInput = reactive({
        firstName: false,
        lastName: false,
        email: false,
        password: false,
        confirmPassword: false
    }); 
    const submitError = ref(null);

    // life cycle / functions
    const textError = computed(() => {
        if(submitError.value === TYPE_SUBMIT_ERROR.MANDATORY_EMPTY_INPUTS) return "Veuillez remplir tous les champs obligatoires.";
        else if(submitError.value === TYPE_SUBMIT_ERROR.NOT_SUCCESS_REQUEST) return "La requête a échoué.";
        else if(submitError.value === TYPE_SUBMIT_ERROR.CONFIRM_PASS_ERROR) return "Les mots de passe ne sont pas identiques.";
    });

    async function handleSubmit() {
        const allErrorsInputs = getStatesErrorInputs();
        const allMandatoryValInputs = getValuesMandantInputs();
        if(isAnyMandatInputEmpty(allMandatoryValInputs)) {
            submitError.value = TYPE_SUBMIT_ERROR.MANDATORY_EMPTY_INPUTS;
            return;
        }
        else if(isAnyInputError(allErrorsInputs)) {
            submitError.value = TYPE_SUBMIT_ERROR.INPUTS_FORMAT_ERRORS;
            return;
        }
        else if(isPasswordsAreDifferent()) {
            submitError.value = TYPE_SUBMIT_ERROR.CONFIRM_PASS_ERROR;
            resetInputsPass();
            return;
        }
        const requestFetched = await createAccount({
            email: email.value,
            password: password.value,
            confirmPassword: confirmPassword.value,
            firstName: firstName.value,
            lastName: lastName.value
        });
        const isSuccessRequest = requestFetched?.isSuccessRequest;
        if(!isSuccessRequest) {
            submitError.value = TYPE_SUBMIT_ERROR.NOT_SUCCESS_REQUEST;
            resetInputsPass();
            return;
        }
        //resetInputs();
        submitError.value = null;
        router.push('/connexion');
    }

    function resetInputsPass() {
        password.value = '';
        confirmPassword.value = '';
    }

    function getStatesErrorInputs() {
        return {
            email: errorInput.email,
            password: errorInput.password,
            confirmPassword: errorInput.confirmPassword,
            firstName: errorInput.firstName,
            lastName: errorInput.lastName,
        }
    }
 
    function getValuesMandantInputs() {
        return {
            email: email.value,
            password: password.value,
            confirmPassword: confirmPassword.value,
            firstName: firstName.value,
            lastName: lastName.value,
            confirmCheckbox: confirmCheckbox.value
        }
    }

    function isPasswordsAreDifferent() {
        if(password.value.length <= 0 || confirmPassword.value.length <= 0) return false;
        return password.value !== confirmPassword.value;
    }

    
</script>