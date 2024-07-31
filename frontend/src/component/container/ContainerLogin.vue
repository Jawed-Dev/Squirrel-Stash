<template>
    <section class="w-full h-screen flex flex-col items-center justify-center bg-main-gradient 
        font-main-font shadow-black shadow-custom-main overflow-y-auto min-h-[600px] lg:w-1/2">

        <div class="w-full flex flex-col items-center justify-center">
            <div class="min-w-[200px] min-h-[200px]">
                <LogoMain :svg="styleLogo" />
            </div>

            <h1 class="text-2xl text-white">Bienvenue !</h1>

            <form class="overflow-x-auto min-w-[350px] md:w-[45%] lg:w-[60%] xl:w-1/3 xl:min-w-[390px] 2xl:min-w-[420px]" @submit.prevent="handleSubmit()">
                
                <!-- Errors -->
                <div class="relative w-full">
                    <p class="text-sm font-light pt-3 text-red-300">{{ textError }}</p>
                </div>

                <div>
                    <label class="text-white font-light" for="login-mail">Adresse email</label>
                    <InputBase 
                        id="login-mail" 
                        iconName="Email"
                        v-model="email" 
                        v-model:stateError="errorInput.email"
                        type="email"
                        placeholder="exemple.domaine.com"
                        width="w-full"
                        validFormat="email"
                    />
                </div>
    
                <div class="mt-6">
                    <label class="text-white font-light" for="login-pass">Mot de passe</label>
                    <InputBase 
                        id="login-pass" 
                        iconName="Password"
                        v-model="password" 
                        v-model:stateError="errorInput.password"
                        type="password"
                        placeholder="Mot de passe"
                        width="w-full"
                        validFormat="password"
                    />
                </div>
    
                <div class="mt-5 flex flex-col items-center gap-1 justify-between lg:px-2 lg:items-stretch lg:gap-0 xl:px-5 lg:flex-row ">
                    <div class="flex gap-1">
                        <InputCheckbox v-model="confirmCheckbox" type="checkbox" />
                        <p class="txt-main-blue text-sm text-white font-light">Se rappeler de moi</p>
                    </div>
                    <router-link 
                        class="text-main-blue font-light text-sm" 
                        to="/mot-de-passe-oublie">Mot de passe oublié ?
                    </router-link>
                </div>
                
                <div>
                    <button :class="`bg-main-blue shadow-black shadow-custom-main 
                        w-full py-2 rounded-lg text-white mt-5`">Se connecter
                    </button>
                </div>
                
                <div class="mt-5 flex flex-col items-center gap-1 lg:gap-5 justify-center lg:px-0 lg:items-stretch xl:px-5 lg:flex-row">
                    <p class="text-white font-light">Vous n'avez pas de compte ?</p> 
                    <router-link class="text-main-blue font-light  text-sm" to="/inscription" >S'inscrire</router-link>
                </div>

                <div class="mt-5 flex flex-col items-center gap-1 lg:gap-5 justify-center sm:gap-2 sm:px-0 sm:items-center xl:px-5 sm:flex-row">
                    <p class="text-white font-light">Besoin d'aide ?</p> 
                    <p 
                        @click="toggleRules"
                        class="text-main-blue font-light text-sm cursor-pointer">Contactez-nous
                    </p>
                </div>
            </form>
            <TransitionOpacity durationIn="duration-300" durationOut="duration-200" >
                <OverlayContactUs 
                    v-if="isOverlayActive" 
                    v-model="isOverlayActive" width="w-1/2" 
                />
            </TransitionOpacity>       
        </div>
    </section>
</template>


<script setup>
    import { ref, reactive, computed, defineAsyncComponent } from 'vue';
    import { useRouter } from 'vue-router';
    import InputBase from '@/component/input/InputBase.vue';
    import InputCheckbox from '@/component/input/InputCheckbox.vue';
    import { getTokenIfSuccessLogin } from '@/composable/useBackendGetData';
    import { isAnyMandatInputEmpty, isAnyInputError, TYPE_SUBMIT_ERROR, TEXT_SUBMIT_ERROR } from '@/error/useHandleError';
    import LogoMain from '@/component/svgs/LogoMain.vue';
    import { setSvgConfig } from '@/svg/svgConfig';
    import TransitionOpacity from '@/component/transition/TransitionOpacity.vue';
    const OverlayContactUs = defineAsyncComponent(() => import('@/component/overlay/OverlayContactUs.vue'));
            
    // props, variables
    const router = useRouter();
    const email = ref('');
    const password = ref('');
    const confirmCheckbox = ref(false);
    const styleLogo = setSvgConfig({width:'200px'})

    const errorInput = reactive({
        email: false,
        password: false
    }); 
    const submitError = ref(null);
    const isOverlayActive = ref(false);

    const textError = computed(() => {
        if(submitError.value === TYPE_SUBMIT_ERROR.MANDATORY_EMPTY_INPUTS) return TEXT_SUBMIT_ERROR.ALL_INPUTS_MANDATORY;
        else if(submitError.value === TYPE_SUBMIT_ERROR.NOT_SUCCESS_REQUEST) return "La requête a échoué.";
    });

    // life cycle, functions
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
        const isSuccessLogin = await getTokenIfSuccessLogin({
            email: email.value, 
            password: password.value, 
            stayConnected: confirmCheckbox.value
        });
        if(!isSuccessLogin) {
            submitError.value = TYPE_SUBMIT_ERROR.NOT_SUCCESS_REQUEST;
            password.value = '';
            return 
        }
        submitError.value = null;
        router.push('/tableau-de-bord');
        //resetInputs();
    }
    
    function getStatesErrorInputs() {
        return {
            email: errorInput.email,
            password: errorInput.password,
        }
    }
 
    function getValuesMandantInputs() {
        return {
            email: email.value,
            password: password.value
        }
    }

    function toggleRules() {
        isOverlayActive.value = true;
    }

</script>
