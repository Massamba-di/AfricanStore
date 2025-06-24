const produits = document.querySelectorAll('figure');

for (const produit of produits) {
    produit.addEventListener('click', function () {
        const dataProduit = {
            id: produit.dataset.id,
            nom: produit.dataset.nom,
            image: produit.dataset.image,
            description:produit.dataset.description,
            prix:produit.dataset.prix,
        };

        localStorage.setItem("produitselectionne", JSON.stringify(dataProduit));

            window.location.href = "{{ path('app_descriptions') }}";
    });
}
