
import { useToast } from "vue-toastification";
import "vue-toastification/dist/index.css";


export function createToast(text, type = 'info' ) {
    const toast = useToast();
    toast[type](text, {
        position: "bottom-right",
        timeout: 4990,
        closeOnClick: true,
        pauseOnFocusLoss: true,
        pauseOnHover: true,
        draggable: true,
        draggablePercent: 0.6,
        showCloseButtonOnHover: false,
        hideProgressBar: false,
        closeButton: "button",
        icon: true,
        rtl: false,
        bodyClassName: ["custom-class-1"]
          
          
    });
}
