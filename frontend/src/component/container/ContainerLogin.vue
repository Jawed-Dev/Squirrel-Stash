<template>
    <section class="bg-main-gradient flex flex-col items-center font-main-font w-[50%] justify-center shadow-black shadow-custom-main">
        <h1 class="text-[25px] text-white">Bienvenue !</h1>

        
        
        <form class="mt-[40px]" @submit.prevent="handleSubmit()">
            <!-- Errors -->
            <div class="relative">
                <p class="absolute text-red-400">{{ computedErrors }}</p>
            </div>
            <div class="mt-[30px]">
                <label class="text-white font-light " for="login-mail">Votre adresse email</label>
                <InputBase 
                    id="login-mail" 
                    v-model="email" 
                    extraClass="border-b-2 w-full py-1 text-white font-light mt-[2px]" 
                    placeholder="Entrez votre email"
                />
            </div>

            <div class="mt-[40px]">
                <label class="text-white font-light" for="login-pass">Votre mot de passe</label>
                <InputBase 
                    id="login-pass" 
                    v-model="password" 
                    extraClass="
                    border-b-2 w-full w-full py-1 text-white font-light mt-[2px]"
                    type="password"
                    placeholder="Entrez votre mot de passe"
                />
            </div>
     
            <div class="mt-[40px] flex pt-[6px] px-[10px] justify-between ">
                <div class="flex gap-1">
                    <InputBase :extraClass="''" v-model="confirmCheckbox" type="checkbox" />
                    <a class="txt-main-blue text-[13px] text-white font-light" href="">Se rappeler de moi</a>
                </div>
                <router-link class="text-main-blue font-light text-[13px]" to="/mot-de-passe-oublie">Mot de passe oublié ?</router-link>
            </div>
                
            <ButtonComponent :extraClass="'shadow-black shadow-custom-main w-[389px] py-[6.5px]'" :titleButton="'Se connecter'" />
            
            <div class="flex pt-[28px] gap-9 justify-center">
                <p class="text-white font-light">Vous n'avez pas de compte ?</p> 
                <router-link class="text-main-blue font-light" to="/inscription" >S'inscrire</router-link>
            </div>
        </form>
    </section>
</template>


<script setup>
    import { ref, computed } from 'vue';
    import { useRouter } from 'vue-router';
    import InputBase from '@/component/input/InputBase.vue';
    import ButtonComponent from '@/component/button/ButtonBasic.vue';
    import { getHandleLogin } from '@/composable/useBackendGetData';
    import { verifyLogin } from '@/error/useHandleError';


    // props, variables
    const router = useRouter();
    const email = ref('');
    const password = ref('');
    const confirmCheckbox = ref(false);

    // Errors 
    const stateErrors = ref([]);


    // life cycle, functions

    async function handleSubmit() {
        if(isAnyErrorActive()) return;
        const isSuccessLogin = await getHandleLogin(email.value, password.value);
        if(isSuccessLogin) {
            router.push('/tableau-de-bord');
            resetInputs();
        }
    }

    const computedErrors = computed(() => {
        const isError = verifyLogin({
            email: email.value,
            password: password.value,
            checkbox: confirmCheckbox.value
        });
        if(isError) {
            stateErrors.value = isError;
            return isError[0].message;
        }
        else stateErrors.value = [];
    });


    function resetInputs() {
        email.value = '';
        password.value = '';
        confirmCheckbox.value = false;
    }

    function isAnyErrorActive() {
        return stateErrors.value.length > 0;
    }


</script>

