import { onMounted, onBeforeUnmount, ref } from 'vue';

export default function useClickOutside(callback, triggerClass) {

    //const elementRef = ref(null);

    const handleClickOutside = (event) => {
        // if (elementRef.value && !event.target.closest(triggerClass)) {
        if (!event.target.closest(triggerClass)) {
            callback();
        }
    };

    onMounted(() => {
        document.addEventListener('click', handleClickOutside, true);
    });

    onBeforeUnmount(() => {
        document.removeEventListener('click', handleClickOutside, true);
    });

   // return { elementRef };
}