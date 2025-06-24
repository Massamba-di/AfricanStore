const form = document.getElementById('order-form');
form.addEventListener('submit', function(event) {
    const panier = localStorage.getItem('panier') || '[]';
    document.getElementById('panier_json').value = panier;
});
