<template>
    <section class="relative rounded-[3px] w-full text-white overflow-hidden bg-main-gradient  
        shadow-black shadow-custom-main mt-[20px]">
        <h2 class="absolute w-full mt-5 shadow-black shadow-custom-main bg-gradient-x-blue p-2 
        font-light flex justify-start text-[18px] text-white">Édition de mot de passe</h2>
        
        <div class="gradient-border overflow-hidden">
            <form class="flex flex-col mt-24" @submit.prevent="handleSubmit()">
                <div class="pl-10 w-1/2 pr-12"> 
                    <div class="flex flex-col justify-center w-1/2">
                        <label class="text-white font-light text-[16px]" for="input-old-pass">Mot de passe actuel</label>
                        <InputBase 
                            unicode="🔒"
                            id="input-old-pass" 
                            v-model="inputsPass.oldPass" 
                            extraClass="" 
                            placeholder="Non défini"
                            type="password"
                        />
                    </div>
                    
                </div>
                <div class="pl-10 mt-12 w-1/2">
                    <div class="flex gap-12">
                        <div class="flex flex-col w-1/2">
                            <label class="text-white font-light text-[16px]" for="input-confirm-pass">Nouveau mot de passe</label>
                            <InputBase 
                                unicode="🔒"
                                id="input-confirm-pass" 
                                v-model="inputsPass.newPass" 
                                extraClass="" 
                                placeholder="Non défini"
                                type="password"
                            />
                        </div>

                        <div class="pl-10 flex flex-col justify-between w-1/2">
                            <label class="text-white font-light text-[16px]" for="input-pass">Force du mot de passe</label>
                            <div v-if="inputsPass.newPass.length > 0" class="flex flex-col w-full ">
                                <p class="text font-light">{{textLevelPass}}</p>
                                <div id="strength-level-pass" class="flex items-center gap-1 w-full ">
                                    <p v-if="strengthLevelPass > 0" v-for="i in strengthLevelPass" :key="i" :class="`flex items-end border-b-2 ${colorBorderLevelPass} w-[33%]`"></p>
                                    <p v-else class="flex items-end border-b-2 border-red-600 w-1/3"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pl-10 mt-12 w-1/2 pr-12">
                    <div class="flex flex-col w-1/2">
                        <label class="text-white font-light text-[16px]" for="input-confirm-newpass">Confirmation du mot de passe</label>
                        <InputBase 
                            unicode="🔒"
                            id="input-confirm-newpass" 
                            v-model="inputsPass.confirmNewPass" 
                            extraClass="" 
                            placeholder="Non défini"
                            type="password"
                        />
                    </div>
                </div>

                <div class="w-full flex items-center justify-center relative my-5">
                    <div class="flex justify-center shadow-black shadow-custom-main w-1/5">
                        <button class=
                            "text-[17px] w-full rounded-sm py-2 bg-gradient-blue 
                            rounded-br-[3px] font-light">Éditer
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </section>
</template>


<script setup>
    import { reactive, computed } from 'vue';
    import InputBase from '@/component/input/InputBase.vue';
    import { updatePasswordByUserId } from '@/composable/useBackendActionData';

    const inputsPass = reactive({
        oldPass: '',
        newPass: '',
        confirmNewPass: ''
    });

    const strengthLevelPass = computed(() => {
        let levelPass = 0;
        if(inputsPass.newPass.length >= 4) levelPass++;
        if (/[A-Z]/.test(inputsPass.newPass) && /\d/.test(inputsPass.newPass)) levelPass++;
        if (/[^A-Za-z0-9]/.test(inputsPass.newPass)) levelPass++;
        return levelPass;
    });

    const colorBorderLevelPass = computed(() => {
        if(strengthLevelPass.value === 0) return "border-white";
        else if(strengthLevelPass.value === 1) return "border-red-600";
        else if(strengthLevelPass.value === 2) return "border-orange-400";
        else if(strengthLevelPass.value === 3) return "border-green-600";
    });

    const textLevelPass = computed(() => {
        if(strengthLevelPass.value === 0) return "Mauvais";
        else if(strengthLevelPass.value === 1) return "Mauvais";
        else if(strengthLevelPass.value === 2) return "Bon";
        else if(strengthLevelPass.value === 3) return "Excellent";
    });


    async function handleSubmit(request) {
        await updatePasswordByUserId({ oldPass: inputsPass.oldPass, newPass: inputsPass.newPass });
        clearInputsPass();
    }

    function clearInputsPass() {
        inputsPass.oldPass = '';
        inputsPass.newPass = '';
        inputsPass.confirmNewPass = '';
    }
    
</script>