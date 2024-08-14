<template>
    <section class="w-full h-screen flex flex-col items-center justify-center bg-main-gradient 
        font-main  shadow-main overflow-y-auto min-h-[791px] lg:w-1/2">

        <div class="w-full flex flex-col items-center justify-center">
            <div class="min-w-[200px] min-h-[200px]">
                <LogoMain :svg="styleLogo" />
            </div>

            <form class="overflow-x-auto min-w-[420px] lg:w-[60%] xl:w-1/2 2xl:min-w-[420px]" @submit.prevent="handleSubmit()">
                <!-- <h1 class="text-white text-[25px] text-center">Inscription</h1> -->
                <!-- Errors -->
                <div class="relative w-full">
                    <p class="text-sm font-light pt-3 text-red-300">{{ textError }}</p>
                </div>
    
                <div class="flex flex-col gap-5 sm:gap-0 sm:flex-row justify-center w-full mt-5">
                    <div class="px-2 w-full sm:w-1/2">
                        <label class="text-white font-light" for="input-fname-crt-acc">Votre prénom</label>
                        <InputBase 
                            iconName="Name"
                            v-model="firstName"
                            v-model:stateError="errorInput.firstName"
                            id="input-fname-crt-acc"
                            placeholder="Prénom"
                            validFormat="firstName"
                        />
                    </div>
    
                    <div class="px-2 w-full sm:w-1/2">
                        <label class="text-white font-light" for="input-lname-crt-acc">Votre nom</label>
                        <InputBase 
                            iconName="Name"
                            v-model="lastName"
                            v-model:stateError="errorInput.lastName"
                            id="input-lname-crt-acc"
                            placeholder="Nom"
                            validFormat="lastName"
                        />
                    </div>
        
                    
                </div>
    
                <div class="mt-5 px-2">
                    <label class="text-white font-light" for="input-email-crt-acc">Votre email</label>
                    <InputBase 
                        iconName="Email"
                        v-model="email" 
                        v-model:stateError="errorInput.email"
                        id="input-email-crt-acc"
                        type="email"
                        placeholder="exemple.domaine.com"
                        validFormat="email"
                        
                    />
                </div>
    
                <div class="mt-5 px-2">
                    <label class="text-white font-light" for="input-pass-crt-acc">Votre mot de passe</label>
                    <InputBase 
                        iconName="Password"
                        v-model="password"
                        v-model:stateError="errorInput.password"
                        id="input-pass-crt-acc"
                        placeholder="Mot de passe"
                        type="password"
                        validFormat="password"
                    />
                </div>
    
                <div class="mt-5 px-2">
                    <label class="text-white font-light " for="input-cpass-crt-acc">Confirmer votre mot de passe </label>
                    <InputBase 
                        iconName="Password"
                        v-model="confirmPassword"
                        v-model:stateError="errorInput.confirmPassword"
                        id="input-cpass-crt-acc"
                        placeholder="Mot de passe"
                        type="password"
                        validFormat="password"
                    />
                </div>
    
                <div class="flex justify-center mt-5 gap-1">
                    <InputCheckbox v-model="confirmCheckbox" type="checkbox" />
                    <p class="txt-main-blue text-[14px] text-white font-light" >
                        Accepter les règles de 
                        <span @click="toggleRules('privacy')" class="text-main-blue cursor-pointer">confidentialité</span> et 
                        <span @click="toggleRules('user')" class="text-main-blue cursor-pointer">d'utilisation</span>.
                    </p>
                </div>
    
                <div class="px-2">
                    <button :class="`bg-main-blue  shadow-main 
                        w-full py-2 rounded-lg text-white mt-5`">S'inscrire
                    </button>
                </div>
    
                <div class="flex pt-5 gap-9 justify-center">
                    <p class="text-white font-light">Vous avez déjà un compte ?</p> 
                    <router-link to="/" class="text-main-blue font-light">Se connecter</router-link>
                </div>
            </form>
            <TransitionOpacity durationIn="duration-300" durationOut="duration-200" >
                <OverlayPrivacy 
                    v-if="isOverlayActive.privacy" 
                    v-model="isOverlayActive.privacy" width="w-1/2" 
                />
                <OverlayUserRules 
                    v-if="isOverlayActive.userRules" 
                    v-model="isOverlayActive.userRules" width="w-1/2" 
                />
            </TransitionOpacity>       
        </div>
    </section>

    <TransitionPopUp duration-in="500" duration-out="500">
        <OverlaySuccessAction redirect="connexion" text="Votre compte a été créé." v-if="isSuccessAction" v-model:overlayActive="isSuccessAction" />
    </TransitionPopUp>
</template>


<script setup>
    import { ref, computed, reactive, defineAsyncComponent } from 'vue';
    import { useRouter } from 'vue-router';
    import InputBase from '@/component/input/InputBase.vue';
    import { createAccount } from '@/composable/useBackendActionData';
    import InputCheckbox from '@/component/input/InputCheckbox.vue';
    import TransitionPopUp from '@/component/transition/TransitionPopUp.vue';
    import { isAnyMandatoryInputEmpty, isAnyInputError, TYPE_SUBMIT_ERROR, TEXT_SUBMIT_ERROR } from '@/error/useHandleError';
    import LogoMain from '@/component/svgs/LogoMain.vue';
    import { setSvgConfig } from '@/svg/svgConfig';
    import TransitionOpacity from '@/component/transition/TransitionOpacity.vue';

    const OverlaySuccessAction = defineAsyncComponent(() => import('@/component/overlay/OverlaySuccessAction.vue'));
    const OverlayPrivacy = defineAsyncComponent(() => import('@/component/overlay/OverlayPrivacy.vue'));
    const OverlayUserRules = defineAsyncComponent(() => import('@/component/overlay/OverlayUserRules.vue'));
    
    // props, variables ...
    const router = useRouter();
    const firstName = ref('');
    const lastName = ref('');
    const email = ref('');
    const password = ref('');
    const confirmPassword = ref('');
    const confirmCheckbox = ref(false);
    const styleLogo = setSvgConfig({width:'200px'})

    const errorInput = reactive({
        firstName: false,
        lastName: false,
        email: false,
        password: false,
        confirmPassword: false
    }); 
    const submitError = ref(null);
    const isSuccessAction = ref(false);
    const isOverlayActive = reactive({
        privacy: false,
        userRules: false,
        contactUs: false
    });


    // life cycle / functions
    const textError = computed(() => {
        if(submitError.value === TYPE_SUBMIT_ERROR.MANDATORY_EMPTY_INPUTS) return TEXT_SUBMIT_ERROR.ALL_INPUTS_MANDATORY;
        else if(submitError.value === TYPE_SUBMIT_ERROR.NOT_SUCCESS_REQUEST) return "La requête a échoué.";
        else if(submitError.value === TYPE_SUBMIT_ERROR.CONFIRM_PASS_ERROR) return TEXT_SUBMIT_ERROR.CONFIRM_PASS_ERROR;
    });

    async function handleSubmit() {
        const allErrorsInputs = getStatesErrorInputs();
        const allMandatoryValInputs = getValuesMandantInputs();
        if(isAnyMandatoryInputEmpty(allMandatoryValInputs)) {
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
        isSuccessAction.value = true;
        //router.push('/connexion');
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

    function toggleRules(request) {
        switch(request) {
            case 'privacy' : {
                isOverlayActive.privacy = true;
                break;
            }
            case 'user' : {
                isOverlayActive.userRules = true;
                break;
            }
        }
    }

    
</script>