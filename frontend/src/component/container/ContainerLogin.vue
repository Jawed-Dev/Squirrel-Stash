<template>
    <section class="bg-main-gradient flex flex-col items-center font-main-font w-[50%] justify-center shadow-black shadow-custom-main">
        <h1 class="text-[25px] text-white">Bienvenue !</h1>

        <form class="w-2/5" @submit.prevent="handleSubmit()">
            <!-- Errors -->
            <div class="relative">
                <p class="text-sm font-light pt-3 absolute text-red-300">{{ textError }}</p>
            </div>

            <div class="mt-[50px]">
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

            <div class="mt-[30px]">
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
     
            <div class="mt-[30px] flex pt-[6px] px-[10px] justify-between ">
                <div class="flex gap-1">
                    <InputCheckbox :extraClass="''" v-model="confirmCheckbox" type="checkbox" />
                    <a class="txt-main-blue text-[13px] text-white font-light" href="">Se rappeler de moi</a>
                </div>
                <router-link class="text-main-blue font-light text-[13px]" to="/mot-de-passe-oublie">Mot de passe oublié ?</router-link>
            </div>
            
            <ButtonComponent :extraClass="'shadow-black shadow-custom-main w-full py-[6.5px]'" :titleButton="'Se connecter'" />
            
            <div class="flex pt-[28px] gap-9 justify-center">
                <p class="text-white font-light">Vous n'avez pas de compte ?</p> 
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
    import { getHandleLogin } from '@/composable/useBackendGetData';
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
        const isSuccessLogin = await getHandleLogin({
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
