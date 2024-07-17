<template>
    <section class="bg-main-gradient flex flex-col items-center font-main-font w-[50%] justify-center shadow-black shadow-custom-main">
        <h1 class="text-[25px] text-white">Changer votre mot de passe</h1>
        
        <form class="mt-[40px] w-[20vw]" @submit.prevent="handleSubmit()">
            <!-- Errors -->
            <div class="relative">
                <p class="text-sm font-light pt-3 absolute text-red-300">{{ textError }}</p>
            </div>

            <div class="mt-[30px] w-">
                <label class="text-white font-light text-[17px]" for="input-pass">Nouveau mot de passe</label>
                <InputBase 
                    unicode="🔒"
                    id="input-pass" 
                    v-model="password" 
                    v-model:stateError="errorInput.password"
                    extraClass="" 
                    placeholder="Mot de passe"
                    type="password"
                    validFormat="password"
                />
            </div>
            <div class="mt-[30px]">
                <label class="text-white font-light text-[17px]" for="input-confirm-pass">Confirmer le mot de passe</label>
                <InputBase 
                    unicode="🔒"
                    id="input-confirm-pass" 
                    v-model="confirmPassword"
                    v-model:stateError="errorInput.confirmPassword" 
                    placeholder="Mot de passe"
                    type="password"
                    validFormat="password"
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
    import { ref, reactive } from 'vue';
    import { useRouter, useRoute } from 'vue-router';
    import InputBase from '@/component/input/InputBase.vue';
    import ButtonComponent from '@/component/button/ButtonBasic.vue';
    import { updatePasswordByToken } from '@/composable/useBackendActionData';
    import { isAnyMandatInputEmpty, isAnyInputError, TYPE_SUBMIT_ERROR } from '@/error/useHandleError';

    // props, variables
    const router = useRouter();
    const route = useRoute();
    const password = ref('');
    const confirmPassword = ref('');

    const errorInput = reactive({
        password: false,
        confirmPassword: false
    }); 
    const submitError = ref(null);

    // life cycle, functions
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
            resetInputs();
            return;
        }
        const isSuccessRequest = await updatePasswordByToken({
            resetPassToken: getTokenResetPass(),
            password: password.value
        });
        if(!isSuccessRequest) {
            submitError.value = TYPE_SUBMIT_ERROR.NOT_SUCCESS_REQUEST;
            resetInputs();
            return;
        }

        submitError.value = null;
        router.push('/connexion');
    }

    function resetInputs() {
        password.value = '';
        confirmPassword.value = '';
    }

    function getTokenResetPass() {
        return route.query.token;
    }

    function getStatesErrorInputs() {
        return {
            password: errorInput.password,
            confirmPassword: errorInput.confirmPassword
        }
    }
 
    function getValuesMandantInputs() {
        return {
            password: password.value,
            confirmPassword: password.value
        }
    }

    function isPasswordsAreDifferent() {
        if(password.value.length <= 0 || confirmPassword.value.length <= 0) return false;
        return password.value !== confirmPassword.value;
    }


</script>

