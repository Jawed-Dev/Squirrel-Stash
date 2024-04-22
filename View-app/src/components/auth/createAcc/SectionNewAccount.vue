<template>
    <section class="flex flex-col items-center font-main-font w-[50%] justify-center">
        <h1 class="text-black text-[25px]">Formulaire d'inscription</h1>

        <input-basic ref="firstName" :extraClass="'w-[389px] py-[6.5px]'" :titleInput="'Prénom'" />

        <input-basic ref="inputEmailRef" :extraClass="'w-[389px] py-[6.5px]'" :titleInput="'Nom'" />

        <input-basic ref="inputEmailRef" :extraClass="'w-[389px] py-[6.5px]'" :titleInput="'Mot de passe'" />

        <input-basic ref="inputEmailRef" :extraClass="'w-[389px] py-[6.5px]'" :titleInput="'Email'" />

        <div class="flex">
            <input-checkbox :extraClass="''" ref="inputCheckBox" />
            <a class="txt-main-blue font-medium text-[12px]" href="">Accepter les règles et conditions</a>
        </div>

        <button-component :extraClass="'w-[389px] py-[6.5px]'" :titleButton="'S\'inscrire'" />

        <div class="flex pt-[28px] gap-9 justify-center">
            <p class=" font-medium">Vous avez déjà un compte ?</p> 
            <router-link to="/" class="text-main-blue  font-medium">Se connecter</router-link>
        </div>


    </section>
</template>


<script setup>

    import InputBasic from '../../input/InputText.vue';
    import InputCheckbox from '../../input/InputCheckbox.vue';
    import ButtonComponent from '../../button/ButtonBasic.vue';

    import { ref } from 'vue';

    const firstName = ref(null);

    
</script>