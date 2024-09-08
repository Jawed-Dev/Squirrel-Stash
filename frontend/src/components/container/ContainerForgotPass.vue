<template>
    <section class="w-full h-screen flex flex-col items-center justify-center bg-main-gradient 
        font-main  shadow-main overflow-y-auto min-h-[520px] lg:w-1/2 ">

        <div class="w-full flex flex-col items-center justify-center">

            <div class="min-w-[200px] min-h-[200px]">
                <LogoMain :svg="styleLogo" />
            </div>
            
            <h1 class="text-2xl text-white">Mot de passe oublié</h1>
            
            <form class="overflow-x-auto min-w-[350px] md:w-[45%] lg:w-[60%] xl:w-1/3 xl:min-w-[390px] 2xl:min-w-[400px]" @submit.prevent="handleSubmit()">
                <div class="mt-5">
                    <label class="text-white font-light" for="forgot-mail">Adresse Email</label>
                    <InputBase 
                        iconName="Email"
                        id="forgot-mail" 
                        v-model="email" 
                        v-model:stateError="errorInput"
                        v-model:mandatoryInput="mandatoryInput"
                        placeholder="nom@domaine.com"
                        type="mail"
                        validFormat="email"
                    />
                </div>

                <button :class="`bg-main-blue shadow-main w-full py-2 rounded-lg text-white mt-6 hover:bg-opacity-50`"
                    >Envoyer
                </button>           

                <div class="flex items-center mt-5 gap-1 justify-center">
                    <p class="text-white">></p>
                    <router-link class="text-main-blue font-light flex gap-1 items-center" to="/connexion" >Se connecter</router-link>
                </div>
            </form>

        </div>
    </section>
</template>


<script setup>
    import { ref } from 'vue';
    import InputBase from '@/components/input/InputBase.vue';
    import { sendResetPass } from '@/requests/useBackendAction';
    import { isAnyMandatoryInputEmpty, isAnyInputError,TYPE_SUBMIT_ERROR, TEXT_SUBMIT_ERROR } from '@/errors/useHandleError';
    import { setSvgConfig } from '@/svgUtils/svgConfig';
    import LogoMain from '@/components/svg/LogoMain.vue';
    import { createToast } from '@/composables/useToastNotification';

    // props, variables
    const email = ref('');
    const errorInput = ref(false);
    const mandatoryInput = ref(false);
    const styleLogo = setSvgConfig({width:'200px'});
    
    // life cycle, functions
    async function handleSubmit() {
        const allErrorsInputs = getStatesErrorInputs();
        const allMandatoryValInputs = getValuesMandantInputs();
        if(isAnyMandatoryInputEmpty(allMandatoryValInputs)) {
            activeErrorForMandatInputEmpty();
            createToast(TEXT_SUBMIT_ERROR.MANDATORY_EMPTY_INPUTS, 'error');
            return;
        }
        if(isAnyInputError(allErrorsInputs)) {
            return;
        }

        const isSuccessLogin = await sendResetPass(email.value);
        if(!isSuccessLogin) {
            return 
        }
        resetInputs();
        createToast("Un mail a été envoyé à l'adresse indiquée.", 'success');
    }
    function resetInputs() {
        email.value = '';
    }
    function getStatesErrorInputs() {
        return {
            email: errorInput.value,
        }
    }
    function activeErrorForMandatInputEmpty() {
        if (!email.value) mandatoryInput.value = true;
    }
    function getValuesMandantInputs() {
        return {
            email: email.value,
        }
    }
</script>

