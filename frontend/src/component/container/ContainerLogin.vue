<template>
    <section class="w-full h-[70vh] lg:h-screen lg:w-1/2 bg-main-gradient flex flex-col items-center 
        font-main-font justify-center shadow-black shadow-custom-main">
        <h1 class="text-[25px] text-white">Bienvenue !</h1>

        <form class="w-[60%] sm:w-1/2 md:w-[70%] xl:w-1/2 xl:min-w-[420px]" @submit.prevent="handleSubmit()">
            
            <!-- Errors -->
            <div class="relative">
                <p class="text-sm font-light pt-3 absolute text-red-300">{{ textError }}</p>
            </div>

            <div class="mt-10">
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

            <div class="mt-5">
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

            <div class="mt-5 flex flex-col items-center gap-1 justify-between lg:items-stretch lg:gap-0 lg:flex-row">
                <div class="flex gap-1">
                    <InputCheckbox v-model="confirmCheckbox" type="checkbox" />
                    <p class="txt-main-blue text-sm text-white font-light">Se rappeler de moi</p>
                </div>
                <router-link 
                    class="text-main-blue font-light text-sm" 
                    to="/mot-de-passe-oublie">Mot de passe oublié ?
                </router-link>
            </div>
            
            <button :class="`bg-main-blue shadow-black shadow-custom-main 
                w-full py-2 rounded-lg text-white mt-5`">Se connecter
            </button>
            
            <div class="mt-5 flex flex-col items-center gap-1 justify-between lg:items-stretch lg:gap-0 lg:flex-row">
                <p class="text-white font-light text">Vous n'avez pas de compte ?</p> 
                <router-link class="text-main-blue font-light" to="/inscription" >S'inscrire</router-link>
            </div>

        </form>
    </section>
</template>


<script setup>
    import { ref, reactive, computed } from 'vue';
    import { useRouter } from 'vue-router';
    import InputBase from '@/component/input/InputBase.vue';
    import ButtonComponent from '@/component/button/ButtonBasic.vue';
    import InputCheckbox from '@/component/input/InputCheckbox.vue';
    import { getTokenIfSuccessLogin } from '@/composable/useBackendGetData';
    import { isAnyMandatInputEmpty, isAnyInputError, TYPE_SUBMIT_ERROR, TEXT_SUBMIT_ERROR } from '@/error/useHandleError';
        
    // props, variables
    const router = useRouter();
    const email = ref('');
    const password = ref('');
    const confirmCheckbox = ref(false);

    const errorInput = reactive({
        email: false,
        password: false
    }); 
    const submitError = ref(null);

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

</script>
