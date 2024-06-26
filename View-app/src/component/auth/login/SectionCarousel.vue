<template>
    <section class="w-[50%] flex flex-col items-center justify-center bg-main-bg text-white">

        <figure class="w-[300px] overflow-hidden">
            <transition
                name="fade-slide"
                mode="out-in"
                
                enter-active-class="transition-all duration-[1s] ease-out"

                enter-from-class="opacity-0 translate-x-[50px]"
                enter-to-class="opacity-100 translate-x-0"

                leave-active-class="transition-all duration-[1s] ease-out"
                leave-to-class="opacity-0 translate-x-[-50px]"
                >
            <img 
                :key="listImg[currentSlide].id"
                :src="currentImgSlide(currentSlide)"
                class="w-full shadow-xl rounded-xl"
                alt="Dynamic Image"
            >
            </transition>
        </figure>

        <div class="flex mt-5">
            <div v-for="(img, index) of listImg" :key="index">
                <img class="w-[20px]" 
                :src="currentIconFocus(index)" alt="">
                <!-- <carrousel /> -->
            </div>
        </div>
        

        <div class="w-[300px] px-0 flex flex-col text-center mt-[20px]">
            <h2 class="font-medium text-[25px]"> {{currentTitleSlide(currentSlide)}} </h2>
            <p class="text-[13px] font-light mt-[15px]"> {{currentTextSlide(currentSlide)}} </p>
        </div>

        
    </section>
</template>


<script setup>

    import { ref, onUnmounted} from 'vue';

    const currentSlide = ref(0);

    const listText = [
        { titleText1: 'Faire des économies', text1 : 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Ipsam saepe dolore harum esse, aliquam ratione repellendus '},
        { titleText2: 'Apanyan', text2 : 'Ipsam saepe dolore harum esse, aliquam ratione repellendus '},
        { titleText3: 'Notre fonctionnalité', text3 : 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Ipsam saepe dolore harum esse, aliquam ratione repellendus '} 
    ]

    const listImg = [
        { pathImg1:'/imgs/img1-carrousel.avif', id: 0 },
        { pathImg2:'/imgs/img1-carrousel.avif', id: 1 },
        { pathImg3:'/imgs/img1-carrousel.avif', id: 2 }
    ];

    // max slide
    const MAX_SLIDE = listImg.length;

    const togglePathIcon = [
        { pathIconEmpty: '/imgs/noun-circle.svg' },
        { pathIconFull: '/imgs/noun-circle-full.svg' },
    ];

    let timerId = createTimerCarrousel(5000);

    //console.log(timerId);
    
    // life cycle
    onUnmounted(() => {
        clearInterval(timerId);
    });

    // functions
    function currentImgSlide(id) {
        const propertyName = `pathImg${id + 1}`;
        return listImg[id][propertyName];
    }

    function currentTitleSlide(id) {
        const propertyName = `titleText${id+1}`;
        return listText[id][propertyName];
    }

    function currentTextSlide(id) {
        const propertyName = `text${id+1}`;
        return listText[id][propertyName];
    }

    function createTimerCarrousel(intervalMs) {
        return setInterval( ()=> {
            nextSlide();
        }, intervalMs );
    }

    function nextSlide() {
        currentSlide.value = (currentSlide.value + 1) % MAX_SLIDE;
    }

    function currentIconFocus(id) {
        return (currentSlide.value === id) ?  togglePathIcon[1].pathIconFull : togglePathIcon[0].pathIconEmpty;
    }

    
</script>