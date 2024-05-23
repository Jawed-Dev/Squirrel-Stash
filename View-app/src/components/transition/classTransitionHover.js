export function classTransitionHover(request, config = null) {
    switch(request) {
        case 'extendHeader' : {
            return 'hover:w-[200px] transition-all duration-500';
        }
        case 'translateY' : {
            return "transition-transform hover:translate-y-[-2px] duration-[0.5s]";
        }
    }
}