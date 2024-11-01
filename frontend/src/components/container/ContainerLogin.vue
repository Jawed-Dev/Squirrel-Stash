<template>
    <section class="bg-main-gradient w-full flex flex-col items-center 
        justify-center h-screen font-main overflow-y-auto 
        min-h-[600px] lg:w-1/2 relative shadow-main">

        <div class="w-full flex flex-col items-center justify-center bg-opacity-0">

            <LogoMain :svg="{width:'200px'}" />

            <form @submit.prevent="handleLogin"
                class="overflow-x-auto min-w-[350px] md:w-[45%] lg:w-[60%] xl:w-1/3 
                xl:min-w-[390px] 2xl:min-w-[420px]">
                <div>
                    <label class="text-white font-light" for="login-mail">Adresse email</label>
                    <InputBase 
                        id="login-mail" 
                        iconName="Email"
                        v-model="inputs.email" 
                        v-model:stateError="errorInputs.email"
                        v-model:mandatoryInput="mandatoryInputs.email"
                        type="email"
                        placeholder="nom@domaine.com"
                        validFormat="email"
                    />
                </div>
    
                <div class="mt-6">
                    <label class="text-white font-light" for="login-pass">Mot de passe</label>
                    <InputBase 
                        id="login-pass" 
                        iconName="Password"
                        v-model="inputs.password" 
                        v-model:stateError="errorInputs.password"
                        v-model:mandatoryInput="mandatoryInputs.password"
                        type="password"
                        placeholder="Mot de passe"
                        validFormat="password"
                    />
                </div>
    
                <div class="mt-6 flex items-center gap-1 justify-between px-2
                            lg:px-2 lg:gap-0 xl:px-5 lg:flex-row">
                            
                    <div class="flex gap-2">
                        <InputCheckbox v-model="inputs.confirmCheckbox" type="checkbox" />
                        <p class="txt-main-blue text-sm text-white font-light">Se rappeler de moi</p>
                    </div>
                    <router-link 
                        class="text-main-blue font-light text-sm hover:text-sky-200" 
                        to="/mot-de-passe-oublie">Mot de passe oublié ?
                    </router-link>
                </div>
                
                <div>
                    <button :class="`bg-main-blue  shadow-main 
                        w-full py-2 rounded-lg text-white mt-5 hover:bg-opacity-50`">Se connecter
                    </button>
                </div>
                
                <div class="mt-5 flex flex-col items-center gap-1 lg:gap-5 justify-center lg:px-0 lg:items-stretch xl:px-5 lg:flex-row">
                    <p class="text-white font-light">Vous n'avez pas de compte ?</p> 
                    <router-link class="text-main-blue font-light text-sm hover:text-sky-200" to="/inscription" >S'inscrire</router-link>
                </div>

                <div class="mt-5 flex flex-col items-center gap-1 lg:gap-5 justify-center sm:gap-2 sm:px-0 sm:items-center xl:px-5 sm:flex-row">
                    <p class="text-white font-light">Besoin d'aide ?</p> 
                    <p 
                        @click="toggleRules"
                        class="text-main-blue font-light text-sm cursor-pointer hover:text-sky-200">Contactez-nous
                    </p>
                </div>
            </form>
            <TransitionOpacity durationIn="duration-300" durationOut="duration-200" >
                <OverlayContactUs 
                    v-if="isOverlayPrivacyActive" 
                    v-model="isOverlayPrivacyActive" width="w-1/2" 
                />
            </TransitionOpacity>       
        </div>
    </section>
</template>


<script setup>
    import { ref, reactive, defineAsyncComponent } from 'vue';
    import { useRouter } from 'vue-router';
    import InputBase from '@/components/input/InputBase.vue';
    import InputCheckbox from '@/components/input/InputCheckbox.vue';
    import { getTokenIfSuccessLogin } from '@/requests/useBackendGetData';
    import { isAnyMandatoryInputEmpty, isAnyInputError, TEXT_SUBMIT_ERROR } from '@/errors/useHandleError';
    import LogoMain from '@/components/svg/LogoMain.vue';
    import TransitionOpacity from '@/components/transition/TransitionOpacity.vue';
    const OverlayContactUs = defineAsyncComponent(() => import('@/components/overlay/OverlayContactUs.vue'));
    import { createToast } from '@/composables/useToastNotification';
                
    // props, variables
    const router = useRouter();
    const isOverlayPrivacyActive = ref(false);

    const inputs = reactive({
        email: '',
        password: '',
        confirmCheckbox: false
    });
    const mandatoryInputs = reactive({
        email: false,
        password: false
    });

    // handle errors
    const errorInputs = reactive({
        email: false,
        password: false
    }); 


    // life cycle, functions
    async function handleLogin() {

        const stateErrorsInputs = getStatesErrorInputs();
        const stateMandatoryInputs = getValuesMandantInputs();
        if(isAnyMandatoryInputEmpty(stateMandatoryInputs)) {
            activeErrorMandatInputs();
            createToast(TEXT_SUBMIT_ERROR.MANDATORY_EMPTY_INPUTS, 'error');
            return;
        }

        else if(isAnyInputError(stateErrorsInputs)) {
            return;
        }

        const isSuccessLogin = await getTokenIfSuccessLogin({
            email: inputs.email, 
            password: inputs.password, 
            stayConnected: inputs.confirmCheckbox
        });

        if(!isSuccessLogin) {
            createToast("Vos identifiants sont incorrects.", 'error');
            inputs.password = '';
            return;
        }

        // success login
        createToast('Vous vous êtes connecté avec succès.', 'success');
        router.push('/tableau-de-bord');
    }
    
    function getStatesErrorInputs() {
        return {
            email: errorInputs.email,
            password: errorInputs.password,
        }
    }

    function activeErrorMandatInputs() {
        if (!inputs.email) mandatoryInputs.email = true;
        if (!inputs.password) mandatoryInputs.password = true;
    }
 
    function getValuesMandantInputs() {
        return {
            email: inputs.email,
            password: inputs.password
        }
    }

    function toggleRules() {
        isOverlayPrivacyActive.value = true;
    }

</script>
