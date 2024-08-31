<template>
    <section class="w-full h-screen flex flex-col items-center justify-center bg-main-gradient 
        font-main  shadow-main overflow-y-auto min-h-[600px] lg:w-1/2">

        
        <div class="w-full flex flex-col items-center justify-center">
            <div class="min-w-[200px] min-h-[200px]">
                <LogoMain :svg="styleLogo" />
            </div>

            <form class="overflow-x-auto min-w-[350px] md:w-[45%] lg:w-[60%] xl:w-1/3 xl:min-w-[390px] 2xl:min-w-[420px]" @submit.prevent="handleSubmit()">

                <h1 class="text-2xl text-center text-white">Changer votre mot de passe</h1>
                <div class="mt-5">
                    <label class="text-white font-light" for="input-pass">Nouveau mot de passe</label>
                    <InputBase 
                        iconName = 'Password'
                        id="input-pass" 
                        v-model="password" 
                        v-model:stateError="errorInput.password"
                        v-model:mandatoryInput="mandatoryInputs.password"
                        extraClass="" 
                        placeholder="Mot de passe"
                        type="password"
                        validFormat="password"
                    />
                </div>
                <div class="mt-5">
                    <label class="text-white font-light" for="input-confirm-pass">Confirmer le mot de passe</label>
                    <InputBase 
                        iconName = 'Password'
                        id="input-confirm-pass" 
                        v-model="confirmPassword"
                        v-model:stateError="errorInput.confirmPassword" 
                        v-model:mandatoryInput="mandatoryInputs.confirmPassword"
                        placeholder="Mot de passe"
                        type="password"
                        validFormat="password"
                    />
                </div>
                    
                <div>
                    <button :class="`bg-main-blue  shadow-main 
                        w-full py-2 rounded-lg text-white mt-5`">Modifier
                    </button>
                </div>
                
                <div class="flex mt-[20px] gap-9 justify-center">
                    <p class="text-white font-light">Retour à la connexion</p> 
                    <router-link class="text-main-blue font-light" to="/connexion" >Se connecter</router-link>
                </div>
            </form>
        </div>
    </section>
</template>


<script setup>
    import { ref, reactive } from 'vue';
    import { useRouter, useRoute } from 'vue-router';
    import InputBase from '@/component/input/InputBase.vue';
    import { updatePasswordByToken } from '@/composable/useBackendActionData';
    import { isAnyMandatoryInputEmpty, isAnyInputError, TYPE_SUBMIT_ERROR, TEXT_SUBMIT_ERROR } from '@/error/useHandleError';
    import LogoMain from '@/component/svgs/LogoMain.vue';
    import { setSvgConfig } from '@/svg/svgConfig';
    import { createToast } from '@/composable/useToastNotification';

    // props, variables
    const router = useRouter();
    const route = useRoute();
    const password = ref('');
    const confirmPassword = ref('');
    const styleLogo = setSvgConfig({width:'200px'})

    const errorInput = reactive({
        password: false,
        confirmPassword: false
    }); 
    const mandatoryInputs = reactive({
        password: false,
        confirmPassword: false
    });

    // life cycle, functions
    async function handleSubmit() {
        const allErrorsInputs = getStatesErrorInputs();
        const allMandatoryValInputs = getValuesMandantInputs();
        if(isAnyMandatoryInputEmpty(allMandatoryValInputs)) {
            activeErrorMandatInputs();
            createToast(TEXT_SUBMIT_ERROR.MANDATORY_EMPTY_INPUTS, 'error');
            return;
        }
        else if(isAnyInputError(allErrorsInputs)) {
            return;
        }
        else if(isPasswordsAreDifferent()) {
            createToast(TEXT_SUBMIT_ERROR.CONFIRM_PASS_ERROR, 'error');
            resetInputs();
            return;
        }
        const isSuccessRequest = await updatePasswordByToken({
            resetPassToken: getTokenResetPass(),
            password: password.value
        });
        if(!isSuccessRequest) {
            resetInputs();
            return;
        }
        createToast("Votre mot de passe a été modifié.", 'success');
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
            confirmPassword: confirmPassword.value
        }
    }
    function activeErrorMandatInputs() {
        if (!password.value) mandatoryInputs.password = true;
        if (!confirmPassword.value) mandatoryInputs.confirmPassword = true;
    }

    function isPasswordsAreDifferent() {
        if(password.value.length <= 0 || confirmPassword.value.length <= 0) return false;
        return password.value !== confirmPassword.value;
    }


</script>

