Pour créer une interaction efficace entre un composable (`useText`), un composant (`Input`), et un parent dans Vue 3, tout en permettant au parent de récupérer et de réagir aux valeurs d'un input, vous pouvez adopter une approche structurée qui utilise `v-model`, des événements ou des callbacks, selon le cas d'utilisation et vos préférences. Voici une méthode commune et efficace pour structurer cette interaction :

### Étape 1: Créer le Composable `useText`

Le composable `useText` peut gérer la logique autour de l'input, y compris la mise à jour et la réinitialisation de la valeur de l'input.

```javascript
// useText.js
import { ref } from 'vue';

export function useText(initialValue = '') {
    const text = ref(initialValue);

    function updateText(newValue) {
        text.value = newValue;
    }

    function resetText() {
        text.value = '';
    }

    return { text, updateText, resetText };
}
```

### Étape 2: Créer le Composant `Input`

Ce composant utilisera `useText` pour gérer l'état de l'input et émettra des événements ou utilisera `v-model` pour communiquer avec le parent.

**Option A: Utilisation de `v-model`**

```vue
<template>
    <input :value="text" @input="onInput">
</template>

<script setup>
import { useText } from './composable/useText';
import { defineEmits } from 'vue';

const emit = defineEmits(['update:modelValue']);
const { text, updateText } = useText();

function onInput(event) {
    updateText(event.target.value);
    emit('update:modelValue', text.value);
}
</script>
```

Dans ce cas, vous utilisez `v-model` dans le parent pour lier directement à la propriété `modelValue` que vous définissez dans le composant enfant.

**Option B: Utilisation de callbacks**

Si vous préférez une approche de callback:

```vue
<template>
    <input :value="text" @input="onInput">
</template>

<script setup>
import { useText } from './composable/useText';
import { defineProps } from 'vue';

const props = defineProps({
    onUpdate: Function
});
const { text, updateText } = useText();

function onInput(event) {
    updateText(event.target.value);
    props.onUpdate(text.value);
}
</script>
```

### Étape 3: Utilisation dans le Composant Parent

Voici comment vous pouvez utiliser le composant `Input` dans le parent, selon l'option que vous choisissez:

**Option A: Avec `v-model`**

```vue
<template>
    <InputComponent v-model="inputValue" />
</template>

<script setup>
import InputComponent from './components/InputComponent.vue';
import { ref } from 'vue';

const inputValue = ref('');
</script>
```

**Option B: Avec callback**

```vue
<template>
    <InputComponent :onUpdate="handleUpdate" />
</template>

<script setup>
import InputComponent from './components/InputComponent.vue';
import { ref } from 'vue';

const inputValue = ref('');

function handleUpdate(value) {
    inputValue.value = value;
}
</script>
```

### Conclusion

L'utilisation de `v-model` est très adaptée pour les cas où vous voulez une liaison bidirectionnelle entre le composant enfant et le parent. Elle simplifie la gestion de l'état tout en maintenant une bonne séparation des préoccupations. L'option de callback peut être préférée si vous avez besoin de plus de contrôle sur ce qui se passe lorsque l'input change, ou si vous voulez effectuer des actions supplémentaires au-delà de la simple mise à jour de la valeur.