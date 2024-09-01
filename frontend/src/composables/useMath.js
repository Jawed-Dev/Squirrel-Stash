export function formatStringToFloat(input) {
    const formattedInput = input.replace(',', '.');
    return parseFloat(formattedInput);
}

export function formatFloatAsString(number) {
    const floatNumber = parseFloat(number);
    if (Math.floor(floatNumber) === floatNumber) {
        return floatNumber.toLocaleString('fr-FR');
    }
    return floatNumber.toLocaleString('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

export function formatFloatAsStringNoEspace(number) {
    const floatNumber = parseFloat(number);
    if (Math.floor(floatNumber) === floatNumber) {
        return floatNumber.toLocaleString('fr-FR').replace(/\s+/g, '');
    }
    return floatNumber.toLocaleString('fr-FR', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).replace(/\s+/g, '');
}