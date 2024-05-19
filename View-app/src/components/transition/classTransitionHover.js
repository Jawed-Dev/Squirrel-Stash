export function classTransitionHover(request, config = null) {
    switch(request) {
        case 'extendHeader' : {
            return 'hover:w-[200px] transition-all duration-700';
        }
        case 'translateY' : {
            return "transition-transform hover:translate-y-[-5px] duration-[0.5s]";
        }
    }
}