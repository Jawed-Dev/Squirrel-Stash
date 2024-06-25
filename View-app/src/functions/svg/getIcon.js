export async function getIconByName(name) {
    switch(name) {

        case 'Restaurant' : {
            return await import('@/components/svgs/Restaurant.vue');
        }
        // case 'Alimentation' : {
        //     return await import('@/components/svgs/IconPurchaseFood.vue');
        // }
        case 'target' : {
            return await import('@/components/svgs/IconTarget.vue');
        }
        case 'balance' : {
            return await import('@/components/svgs/IconBalance.vue');
        }
        case 'Santé' : {
            return await import('@/components/svgs/IconHealth.vue');
        }
        case 'Transport' : {
            return await import('@/components/svgs/IconTransport.vue');
        }
        case 'Cadeau' : {
            return await import('@/components/svgs/IconGift.vue');
        }
        case 'Loisir' : {
            return await import('@/components/svgs/IconHobbie.vue');
        }
        case 'Famille' : {
            return await import('@/components/svgs/IconFamily.vue');
        }
        case 'Alimentation' : {
            return await import('@/components/svgs/IconFood.vue');
        }
        case 'Vestimentaire' : {
            return await import('@/components/svgs/IconClothes.vue');
        }
        case 'Loyer' : {
            return await import ('@/components/svgs/IconHouse.vue');
        }

        case 'Facture' : {
            return await import ('@/components/svgs/IconReceipt.vue');
        }

        case 'Abonnement' : {
            return await import ('@/components/svgs/IconMobile.vue');
        }

        case 'Crédit' : {
            return await import ('@/components/svgs/IconBillet.vue');
        }

        case 'Assurance' : {
            return await import ('@/components/svgs/IconCar.vue');
        }

        case 'Autre' : {
            return await import ('@/components/svgs/IconQuestionMark.vue');
        }

        case 'calculator' : {
            return await import ('@/components/svgs/IconCalculator.vue');
        }


        default : {
            return "";
        }
    }
}