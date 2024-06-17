
<template>
    <section class="bg-main-gradient flex flex-col items-center font-main-font w-[50%] justify-center">
        <h1 class="text-black text-[25px]">Bienvenue !</h1>
        <p class="text-custom-gray  text-[14px]">dazaazdadaazaz</p>

        <form @submit.prevent="handleSubmit">

            <InputBase v-model="inputEmail" extraClass="w-full py-[20px] text-white font-light" />
            <InputBase v-model="inputPass" extraClass="w-full py-[20px] text-white font-light"/>
     
            <div class="flex pt-[6px] px-[10px] justify-between">
                <div class="flex">
                    <input-checkbox :extraClass="''" ref="inputCheckBox" />
                    <a class="txt-main-blue font-medium text-[12px]" href="">Se rappeler de moi</a>
                </div>
                <a  class="text-main-blue font-medium text-[12px]" href="">Mot de passe oublié ?</a>
            </div>
                
            <button-component :extraClass="'w-[389px] py-[6.5px]'" :titleButton="'Se connecter'" />
            

            <div class="flex pt-[28px] gap-9 justify-center">
                <p class=" font-medium">Vous n'avez pas de compte ?</p> 
                <router-link to="/inscription" class="text-main-blue font-medium">S'inscrire</router-link>
            </div>
        </form>
    </section>
</template>


<script setup>
    import { ref, reactive } from 'vue';

    import InputBase from '@/components/input/InputBase.vue';
    import ButtonComponent from '../../button/ButtonBasic.vue';
    import inputCheckbox from '../../input/InputCheckbox.vue';
    import { useRouter } from 'vue-router';
    import useFetchForm from '@/composables/useFetchForm';
    const router = useRouter();

    const inputEmail = ref(null);
    const inputPass = ref(null);
    const inputCheckBox = ref(null);

    async function handleSubmit() {
        //alert(inputEmail.value);

        const localToken = localStorage.getItem('jwt');
        const dataLogin = {
            'email': inputEmail.value,
            'password': inputPass.value
        }
        const dataHandleLogin = await useFetchForm('formLogin', 'POST', dataLogin, localToken);
        const isSucessLogin = dataHandleLogin.tokenJwt;

        console.log(dataHandleLogin);

        inputEmail.value = '';
        inputPass.value = '';

        if(isSucessLogin) {
            localStorage.setItem('authToken', dataHandleLogin.tokenJwt);
            router.push('/tableau-de-bord');
        }
    }

    

</script>

