<template>
    <TransitionOpacity>
        <div v-if="isMenuUserActive" class="rounded-[3px] overflow-hidden shadow-black shadow-custom-main flex flex-col">
            <div class="bg-main-gradient p-1 gradient-border text-white px-3">
                <p>Utilisateur</p>
                <p>Utilisateur</p>
                <p>Utilisateur</p>
                <p>Utilisateur</p>
                <p>Utilisateur</p>
                <p>Utilisateur</p>
                <!-- <router-link to="/utilisateur">Utilisateur</router-link>
                <router-link to="/utilisateur">Confidentialité</router-link>
                <router-link to="/utilisateur">Règles d'utilisateurs</router-link>
                <router-link to="/utilisateur">Support et aide</router-link>
                <router-link to="/utilisateur">Version 1.0</router-link>
                <router-link to="/utilisateur">Déconnexion</router-link> -->
            </div>
        </div>
    </TransitionOpacity>
</template>


<script setup>
    import { useRouter } from 'vue-router';
    import TransitionOpacity from '@/component/transition/TransitionOpacity.vue'; 
    const isMenuUserActive = defineModel();

    // props, variables
    const router = useRouter();
</script>
