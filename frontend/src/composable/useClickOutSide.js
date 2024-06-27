import { onMounted, onBeforeUnmount, ref } from 'vue';

export default function useClickOutside(triggerClass, isMenuActiveRef, callback) {
    function handleClickOutside(event) {
        // Vérifie si le menu est actif et si le clic est en dehors
        if (isMenuActiveRef.value && !event.target.closest(triggerClass)) {
            callback();
        }
    }

    onMounted(() => {
        // Ajoute l'écouteur une seule fois au montage du composant
        document.addEventListener('click', handleClickOutside, true);
    });

    onBeforeUnmount(() => {
        // Retire l'écouteur quand le composant est démonté
        document.removeEventListener('click', handleClickOutside, true);
    });
}