<template>
    <section class="w-full flex flex-col items-center justify-center bg-gradient-green text-white
        h-[30vh] lg:w-1/2 lg:h-screen">

        <LogoMain />

        
    </section>
</template>


<script setup>

    import { ref, onUnmounted} from 'vue';
    import LogoMain from '@/component/svgs/LogoMain.vue';
    

    // props, variables
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
        if(currentSlide.value > 1000) currentSlide.value = 0;
        currentSlide.value = (currentSlide.value + 1) % MAX_SLIDE;
    }

    function currentIconFocus(id) {
        return (currentSlide.value === id) ?  togglePathIcon[1].pathIconFull : togglePathIcon[0].pathIconEmpty;
    }

    
</script>