export function classTransitionHover(request) {
    switch(request) {
        case 'extendHeader' : {
            return 'hover:w-[200px] transition-all duration-500';
        }
        case 'translateY' : {
            //return "transition-transform hover:translate-y-[-1px] duration-500 ";
        }
    }
}