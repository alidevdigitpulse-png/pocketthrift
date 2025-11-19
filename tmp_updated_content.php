if (offer.discount) {
    var lowerDiscount = toLowerCase(offer.discount);
    if (isNumeric(offer.discount)) {
        var discountValue = parseFloat(offer.discount);
        discountElement.textContent = Math.abs(discountValue).toString();
        discountTextElement.textContent = '% OFF';
    } else if (lowerDiscount.includes('free shipping') || lowerDiscount.includes('kostenloser ver') || lowerDiscount.includes('spedizione grat')) {
        discountElement.textContent = 'FREE';
        discountTextElement.textContent = 'SHIPPING';
    } else if (lowerDiscount.includes('free delivery')) {
        discountElement.textContent = 'FREE';
        discountTextElement.textContent = 'DELIVERY';
    } else if (lowerDiscount.includes('sign up') || lowerDiscount.includes('meldung') || lowerDiscount.includes('iscrizione') || lowerDiscount.includes('melden sie sich')) {
        discountElement.textContent = 'SIGN UP';
        discountTextElement.textContent = 'OFFER';
    } else if (lowerDiscount.includes('up to') || lowerDiscount.includes('bis zu') || lowerDiscount.includes('fino al')) {
        discountElement.textContent = offer.discount;
        discountTextElement.textContent = 'OFFER';
    } else {
        discountElement.textContent = offer.discount;
        discountTextElement.textContent = 'OFFER';
    }
} else {
    discountElement.textContent = 'SPECIAL';
    discountTextElement.textContent = 'OFFER';
}