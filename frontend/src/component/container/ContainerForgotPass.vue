<template>
    <section class="bg-main-gradient flex flex-col items-center font-main-font w-[50%] justify-center shadow-black shadow-custom-main">
        <h1 class="text-[25px] text-white">Mot de passe oublié</h1>
        <form class="mt-[25x]" @submit.prevent="handleSubmit()">
            <!-- Errors -->
            <div class="relative pb-3">
                <p class="text-sm font-light absolute text-red-300">{{ textError }}</p>
            </div>

            <div class="mt-[30px]">
                <label class="text-white font-light" for="forgot-mail">Adresse Email</label>
                <InputBase 
                    iconName="Email"
                    id="forgot-mail" 
                    v-model="email" 
                    v-model:stateError="errorInput"
                    placeholder="exemple.domaine.com"
                    type="mail"
                    validFormat="email"
                />
            </div>
            <ButtonComponent :extraClass="'shadow-black shadow-custom-main w-full py-[6.5px] mt-[20px]'" :titleButton="'Envoyer'" />
            <div class="flex mt-[20px] gap-9 justify-center">
                <p class="text-white font-light">Retour à la connexion</p> 
                <router-link class="text-main-blue font-light" to="/connexion" >Se connecter</router-link>
            </div>
        </form>
    </section>
    <TransitionPopUp redirection="connexion" duration-in="500" duration-out="500">
        <OverlaySuccessAction text="Un email vous a été envoyé pour modifier votre mot de passe." v-if="isSuccessAction" v-model:overlayActive="isSuccessAction" />
    </TransitionPopUp>
</template>


<script setup>
    import { ref, computed, defineAsyncComponent } from 'vue';
    import { useRouter } from 'vue-router';
    import InputBase from '@/component/input/InputBase.vue';
    import ButtonComponent from '@/component/button/ButtonBasic.vue';
    import { sendResetPass } from '@/composable/useBackendActionData';
    import { isAnyMandatInputEmpty, isAnyInputError,TYPE_SUBMIT_ERROR, TEXT_SUBMIT_ERROR } from '@/error/useHandleError';
    import TransitionPopUp from '@/component/transition/TransitionPopUp.vue';
    const OverlaySuccessAction = defineAsyncComponent(() => import('@/component/overlay/OverlaySuccessAction.vue'));

    // props, variables
    const router = useRouter();
    const email = ref('');
    const errorInput = ref(false);
    const submitError = ref(null);
    const isSuccessAction = ref(false);

    // life cycle, functions
    const textError = computed(() => {
        if(submitError.value === TYPE_SUBMIT_ERROR.MANDATORY_EMPTY_INPUTS) return TEXT_SUBMIT_ERROR.ALL_INPUTS_MANDATORY;
        else if(submitError.value === TYPE_SUBMIT_ERROR.NOT_SUCCESS_REQUEST) return "Identifiants incorrects. Veuillez réessayer.";
    });

    async function handleSubmit() {
        const allErrorsInputs = getStatesErrorInputs();
        const allMandatoryValInputs = getValuesMandantInputs();
        if(isAnyMandatInputEmpty(allMandatoryValInputs)) {
            submitError.value = TYPE_SUBMIT_ERROR.MANDATORY_EMPTY_INPUTS;
            return;
        }
        if(isAnyInputError(allErrorsInputs)) {
            submitError.value = TYPE_SUBMIT_ERROR.INPUTS_FORMAT_ERRORS;
            return;
        }

        const isSuccessLogin = await sendResetPass(email.value);
        if(!isSuccessLogin) {
            submitError.value = TYPE_SUBMIT_ERROR.NOT_SUCCESS_REQUEST;
            return 
        }
        resetInputs();
        submitError.value = null;
        isSuccessAction.value = true;
    }
    function resetInputs() {
        email.value = '';
    }

    function getStatesErrorInputs() {
        return {
            email: errorInput.value,
        }
    }
 
    function getValuesMandantInputs() {
        return {
            email: email.value,
        }
    }
</script>

