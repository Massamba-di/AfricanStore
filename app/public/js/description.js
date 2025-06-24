document.addEventListener("DOMContentLoaded", function () {
    const produitData = JSON.parse(localStorage.getItem("produitselectionne"));

    if (produitData) {
        document.querySelector("#nom-produit").textContent = produitData.nom;
        document.querySelector("#image-produit").src = produitData.image;
        document.querySelector("#image-produit").alt = produitData.nom;

        if (produitData.description) {
            document.querySelector("#description-produit").textContent = produitData.description;
        }
        if (produitData.prix) {
            document.querySelector("#prix-produit").textContent = produitData.prix;
        }

        const button = document.querySelector(".btnAjout");
        if (button) {
            button.addEventListener('click', function (event) {
                event.preventDefault(); 
                let panier = JSON.parse(localStorage.getItem("panier")) || [];

                const produitAjoute = {
                    id: Date.now(),
                    nom: produitData.nom,
                    image: produitData.image,
                    prix: produitData.prix,
                    quantity: 1
                };

                if (produitData.description) {
                    produitAjoute.description = produitData.description;
                }

                const tailleSelectionnee = document.querySelector('input[name="taille"]:checked');
                if (tailleSelectionnee) {
                    produitAjoute.taille = tailleSelectionnee.id;
                }

                panier.push(produitAjoute);
                localStorage.setItem('panier', JSON.stringify(panier));

                alert('Produit ajouté au panier !');

           const url = this.dataset.cartUrl;
          console.log("Redirection vers :", url); 
          window.location.href = url;


            });
        }
    } else {
        document.body.innerHTML = "<p>Aucun produit trouvé.</p>";
    }
});
