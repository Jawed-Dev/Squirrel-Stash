<template>
    <section class="bg-main-gradient flex flex-col items-center font-main-font w-[50%] justify-center shadow-black shadow-custom-main">
        <h1 class="text-[25px] text-white">Mot de passe oublié</h1>

        
        
        <form class="mt-[40px] w-[20vw]" @submit.prevent="handleSubmit()">
            <!-- Errors -->
            <div class="relative">
                <p class="absolute text-red-400">{{ computedErrors }}</p>
            </div>
            <div class="mt-[30px] w-">
                <label class="text-white font-light text-[17px]" for="input-pass">Entrez votre mot de passe</label>
                <InputBase 
                    id="input-pass" 
                    v-model="password" 
                    extraClass="border-b-2 w-full py-1 text-white font-light mt-[2px]" 
                    placeholder="Email"
                    type="password"
                />
            </div>
            <div class="mt-[30px]">
                <label class="text-white font-light text-[17px]" for="input-confirm-pass">Confirmez votre mot de passe</label>
                <InputBase 
                    id="input-confirm-pass" 
                    v-model="confirmPassword" 
                    extraClass="border-b-2 w-full py-1 text-white font-light mt-[2px]" 
                    placeholder="Email"
                    type="password"
                />
            </div>
                
            <ButtonComponent :extraClass="'shadow-black shadow-custom-main w-full py-[6.5px] mt-[40px]'" :titleButton="'Modifier'" />
            
            <div class="flex mt-[20px] gap-9 justify-center">
                <p class="text-white font-light">Retour à la connexion</p> 
                <router-link class="text-main-blue font-light" to="/connexion" >Se connecter</router-link>
            </div>
        </form>
        
    </section>
</template>


<script setup>
    import { ref, computed, onMounted } from 'vue';
    import { useRouter, useRoute } from 'vue-router';
    import InputBase from '@/component/input/InputBase.vue';
    import ButtonComponent from '@/component/button/ButtonBasic.vue';
    import { updatePassword } from '@/composable/useBackendSetData';
    import { verifyForgotPass } from '@/error/useHandleError';


    // props, variables
    const router = useRouter();
    const route = useRoute();
    const password = ref('');
    const confirmPassword = ref('');

    // Errors 
    const stateErrors = ref([]);

    // life cycle, functions
    async function handleSubmit() {
        if(isAnyErrorActive()) return;
        const isSuccessRequest = await updatePassword({
            resetPassToken: getTokenResetPass(),
            password: password.value
        });
        if(isSuccessRequest) {
            //router.push('/connexion');
            resetInputs();
        }
    }

    const computedErrors = computed(() => {
        // const isError = verifyForgotPass({
        //     email: email.value,
        // });
        // if(isError) {
        //     stateErrors.value = isError;
        //     return isError[0].message;
        // }
        // else stateErrors.value = [];
    });


    function resetInputs() {
        password.value = '';
        confirmPassword.value = '';
    }

    function isAnyErrorActive() {
        return stateErrors.value.length > 0;
    }

    function getTokenResetPass() {
        return route.query.token;
    }


</script>

