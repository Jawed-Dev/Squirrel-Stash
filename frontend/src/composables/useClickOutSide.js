import { onMounted, onBeforeUnmount } from 'vue';

export default function useClickOutside(triggerClass, isMenuActiveRef, callback) {
    function handleClickOutside(event) {
        if (isMenuActiveRef.value && !event.target.closest(triggerClass)) {
            callback();
        }
    }
    onMounted(() => {
        document.addEventListener('mousedown', handleClickOutside, true);
    });

    onBeforeUnmount(() => {
        document.removeEventListener('mousedown', handleClickOutside, true);
    });
}