export async function getIconByName(name) {
    switch(name) {

        case 'Restaurant' : {
            return await import('@/component/svgs/Restaurant.vue');
        }
        case 'Invisible' : {
            return await import('@/component/svgs/IconInvisible.vue');
        }
        case 'target' : {
            return await import('@/component/svgs/IconTarget.vue');
        }
        case 'balance' : {
            return await import('@/component/svgs/IconBalance.vue');
        }
        case 'Santé' : {
            return await import('@/component/svgs/IconHealth.vue');
        }
        case 'Transport' : {
            return await import('@/component/svgs/IconTransport.vue');
        }
        case 'Cadeau' : {
            return await import('@/component/svgs/IconGift.vue');
        }
        case 'Loisir' : {
            return await import('@/component/svgs/IconHobbie.vue');
        }
        case 'Famille' : {
            return await import('@/component/svgs/IconFamily.vue');
        }
        case 'Alimentation' : {
            return await import('@/component/svgs/IconFood.vue');
        }
        case 'Vestimentaire' : {
            return await import('@/component/svgs/IconClothes.vue');
        }
        case 'Loyer' : {
            return await import ('@/component/svgs/IconHouse.vue');
        }

        case 'Facture' : {
            return await import ('@/component/svgs/IconReceipt.vue');
        }

        case 'Abonnement' : {
            return await import ('@/component/svgs/IconMobile.vue');
        }

        case 'Crédit' : {
            return await import ('@/component/svgs/IconBillet.vue');
        }

        case 'Assurance' : {
            return await import ('@/component/svgs/IconCar.vue');
        }

        case 'Autre' : {
            return await import ('@/component/svgs/IconQuestionMark.vue');
        }

        case 'calculator' : {
            return await import ('@/component/svgs/IconCalculator.vue');
        }

        case 'Search' : {
            return await import ('@/component/svgs/IconSearch.vue');
        }
        
        default : {
            return '';
        }
    }
}