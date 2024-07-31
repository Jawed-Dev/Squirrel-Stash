<template>
    <section class="w-full h-screen flex flex-col items-center justify-center bg-main-gradient 
        font-main-font shadow-black shadow-custom-main overflow-y-auto min-h-[520px] lg:w-1/2 ">

        <div class="w-full flex flex-col items-center justify-center">

            <div class="min-w-[200px] min-h-[200px]">
                <LogoMain :svg="styleLogo" />
            </div>
            
            <h1 class="text-2xl text-white">Mot de passe oublié</h1>
            
            <form class="overflow-x-auto min-w-[350px] md:w-[45%] lg:w-[60%] xl:w-1/3 xl:min-w-[390px] 2xl:min-w-[400px]" @submit.prevent="handleSubmit()">
                <!-- Errors -->
                <div class="relative pb-3">
                    <p class="text-sm font-light text-red-300">{{ textError }}</p>
                </div>
    
                <div class="mt-5">
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

                <div>
                    <button :class="`bg-main-blue shadow-black shadow-custom-main 
                        w-full py-2 rounded-lg text-white mt-5`">Envoyer
                    </button>
                </div>

                <div class="flex items-center mt-[20px] gap-1 justify-center">
                    <p class="text-white">></p>
                    <router-link class="text-main-blue font-light flex gap-1 items-center" to="/connexion" >Se connecter</router-link>
                </div>
            </form>

        </div>
    </section>
    <TransitionPopUp redirection="connexion" duration-in="500" duration-out="500">
        <OverlaySuccessAction text="Un email vous a été envoyé pour modifier votre mot de passe." v-if="isSuccessAction" v-model:overlayActive="isSuccessAction" />
    </TransitionPopUp>
</template>


<script setup>
    import { ref, computed, defineAsyncComponent } from 'vue';
    import { useRouter } from 'vue-router';
    import InputBase from '@/component/input/InputBase.vue';
    import { sendResetPass } from '@/composable/useBackendActionData';
    import { isAnyMandatInputEmpty, isAnyInputError,TYPE_SUBMIT_ERROR, TEXT_SUBMIT_ERROR } from '@/error/useHandleError';
    import TransitionPopUp from '@/component/transition/TransitionPopUp.vue';
    import { setSvgConfig } from '@/svg/svgConfig';
    import LogoMain from '@/component/svgs/LogoMain.vue';
    
    const OverlaySuccessAction = defineAsyncComponent(() => import('@/component/overlay/OverlaySuccessAction.vue'));

    // props, variables
    const router = useRouter();
    const email = ref('');
    const errorInput = ref(false);
    const submitError = ref(null);
    const isSuccessAction = ref(false);
    const styleLogo = setSvgConfig({width:'200px'})
    
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

