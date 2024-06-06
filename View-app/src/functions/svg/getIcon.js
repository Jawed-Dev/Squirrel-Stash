export async function getIconByName(name) {
    switch(name) {

        case 'restaurant' : {
            return await import('../../components/svgs/Restaurant.vue');
        }
        case 'purchaseFood' : {
            return await import('../../components/svgs/IconPurchaseFood.vue');
        }
        case 'target' : {
            return await import('../../components/svgs/IconTarget.vue');
        }
        case 'balance' : {
            return await import('../../components/svgs/IconBalance.vue');
        }
        case 'health' : {
            return await import('../../components/svgs/IconHealth.vue');
        }
        case 'transport' : {
            return await import('../../components/svgs/IconTransport.vue');
        }
        case 'gift' : {
            return await import('../../components/svgs/IconGift.vue');
        }
        case 'hobbie' : {
            return await import('../../components/svgs/IconHobbie.vue');
        }
        case 'family' : {
            return await import('../../components/svgs/IconFamily.vue');
        }
        case 'food' : {
            return await import('../../components/svgs/IconFood.vue');
        }
        case 'clothes' : {
            return await import('../../components/svgs/IconClothes.vue');
        }
        case 'house' : {
            return await import ('../../components/svgs/IconHouse.vue');
        }
        case 'receipt' : {
            return await import ('../../components/svgs/IconReceipt.vue');
        }

        case 'mobile' : {
            return await import ('../../components/svgs/IconMobile.vue');
        }

        case 'billet' : {
            return await import ('../../components/svgs/IconBillet.vue');
        }

        case 'car' : {
            return await import ('../../components/svgs/IconCar.vue');
        }

        case 'questionMark' : {
            return await import ('../../components/svgs/IconQuestionMark.vue');
        }

        case 'calculator' : {
            return await import ('../../components/svgs/IconCalculator.vue');
        }


        default : {
            return "";
        }
    }
}