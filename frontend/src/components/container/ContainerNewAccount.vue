<template>
    <section class="w-full h-screen flex flex-col items-center justify-center bg-main-gradient 
        font-main  shadow-main overflow-y-auto min-h-[791px] lg:w-1/2">

        <div class="w-full flex flex-col items-center justify-center">
            <div class="min-w-[200px] min-h-[200px]">
                <LogoMain :svg="styleLogo" />
            </div>

            <form class="overflow-x-auto min-w-[420px] lg:w-[60%] xl:w-1/2 2xl:min-w-[420px]" @submit.prevent="handleSubmit()">    
                <div class="flex flex-col gap-5 sm:gap-0 sm:flex-row justify-center w-full mt-5">
                    <div class="px-2 w-full sm:w-1/2">
                        <label class="text-white font-light" for="input-fname-crt-acc">Votre prénom</label>
                        <InputBase 
                            iconName="Name"
                            v-model="firstName"
                            v-model:stateError="errorInput.firstName"
                            v-model:mandatoryInput="mandatoryInputs.firstName"
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
                            v-model:mandatoryInput="mandatoryInputs.lastName"
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
                        v-model:mandatoryInput="mandatoryInputs.email"
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
                        v-model:mandatoryInput="mandatoryInputs.password"
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
                        v-model:mandatoryInput="mandatoryInputs.confirmPassword"
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
                        <span @click="toggleRules('privacy')" class="text-main-blue cursor-pointer hover:text-sky-200">confidentialité</span> et 
                        <span @click="toggleRules('user')" class="text-main-blue cursor-pointer hover:text-sky-200">d'utilisation</span>.
                    </p>
                </div>
    
                <div class="px-2">
                    <button :class="`bg-main-blue  shadow-main 
                        w-full py-2 rounded-lg text-white mt-5 hover:bg-opacity-50`">S'inscrire
                    </button>
                </div>
    
                <div class="flex pt-5 gap-9 justify-center">
                    <p class="text-white font-light">Vous avez déjà un compte ?</p> 
                    <router-link to="/" class="text-main-blue font-light hover:text-sky-200">Se connecter</router-link>
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
</template>


<script setup>
    import { ref, computed, reactive, defineAsyncComponent } from 'vue';
    import { useRouter } from 'vue-router';
    import InputBase from '@/components/input/InputBase.vue';
    import { createAccount } from '@/composables/useBackendActionData';
    import InputCheckbox from '@/components/input/InputCheckbox.vue';
    import { isAnyMandatoryInputEmpty, isAnyInputError, TYPE_SUBMIT_ERROR, TEXT_SUBMIT_ERROR } from '@/errors/useHandleError';
    import LogoMain from '@/components/svgs/LogoMain.vue';
    import { setSvgConfig } from '@/svg/svgConfig';
    import TransitionOpacity from '@/components/transition/TransitionOpacity.vue';
    import { createToast } from '@/composables/useToastNotification';

    const OverlayPrivacy = defineAsyncComponent(() => import('@/components/overlay/OverlayPrivacy.vue'));
    const OverlayUserRules = defineAsyncComponent(() => import('@/components/overlay/OverlayUserRules.vue'));
    
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
    const mandatoryInputs = reactive({
        firstName: false,
        lastName: false,
        email: false,
        password: false,
        confirmPassword: false
    });
    const isOverlayActive = reactive({
        privacy: false,
        userRules: false,
        contactUs: false
    });


    // life cycle / functions
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
            resetInputsPass();
            return;
        }
        //resetInputs();
        createToast("Votre compte a été créé.", 'success');
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
    function activeErrorMandatInputs() {
        if (!firstName.value) mandatoryInputs.firstName = true;
        if (!lastName.value) mandatoryInputs.lastName = true;
        if (!email.value) mandatoryInputs.email = true;
        if (!password.value) mandatoryInputs.password = true;
        if (!confirmPassword.value) mandatoryInputs.confirmPassword = true;
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