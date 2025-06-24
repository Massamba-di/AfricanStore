let panier = JSON.parse(localStorage.getItem("panier")) || [];

function afficherPanier() {
    const panierContainer = document.querySelector("#panier-container");
    const totalGeneralElement = document.querySelector("#totalGeneral");
    const template = document.querySelector("#produit-template");
    
    panierContainer.innerHTML = "";

    if (panier.length === 0) {
        panierContainer.innerHTML = "<p>Votre panier est vide.</p>";
        totalGeneralElement.textContent = "0.00 €";
        return;
    }

    let totalGeneral = 0;

    panier.forEach((produit, index) => {
        const clone = template.content.cloneNode(true);

        const prixNumber = parseFloat(produit.prix.replace(/[^\d,.-]/g, '').replace(',', '.')) || 0;
        const totalProduit = prixNumber * produit.quantite;
        totalGeneral += totalProduit;

        const produitElement = clone.querySelector(".produit-panier");
        produitElement.dataset.index = index;

        clone.querySelector(".img-produit").src = produit.image;
        clone.querySelector(".img-produit").alt = produit.nom;
        clone.querySelector(".nom-produit").textContent = produit.nom;
         clone.querySelector(".taille-produit").textContent = produit.taille;
        clone.querySelector(".prix-produit").textContent = prixNumber.toFixed(2) + " €";
        clone.querySelector(".quantite-input").value = produit.quantite;
        clone.querySelector(".total-produit").textContent = totalProduit.toFixed(2) + " €";

        panierContainer.appendChild(clone);
    });

    totalGeneralElement.textContent = totalGeneral.toFixed(2) + " €";
    ajouterEcouteursEvenements();
}

function ajouterEcouteursEvenements() {
    document.querySelectorAll(".quantite-input").forEach(input => {
        input.addEventListener("change", function () {
            const index = parseInt(this.closest(".produit-panier").dataset.index);
            const nouvelleQuantite = parseInt(this.value);

            if (isNaN(nouvelleQuantite) || nouvelleQuantite < 1) {
                alert("Veuillez entrer une quantité valide (min. 1)");
                this.value = panier[index].quantite;
                return;
            }

            panier[index].quantite = nouvelleQuantite;
            localStorage.setItem("panier", JSON.stringify(panier));
            afficherPanier();
        });
    });

    document.querySelectorAll(".btn-supprimer").forEach(bouton => {
        bouton.addEventListener("click", function () {
            const index = parseInt(this.closest(".produit-panier").dataset.index);
            panier.splice(index, 1);
            localStorage.setItem("panier", JSON.stringify(panier));
            afficherPanier();
        });
    });

    const commanderBtn = document.querySelector("#commander-btn");
        if (commanderBtn) {
        commanderBtn.addEventListener("click", function () {
            const confirmation = confirm("Voulez-vous vraiment passer à la commande ?");
            if (confirmation) {
                alert("Commande validée !");
                const panier = JSON.parse(localStorage.getItem("panier")) || [];

                        // Par exemple, tu peux enregistrer ce panier dans localStorage sous une autre clé
                        localStorage.setItem("commande_en_cours", JSON.stringify(panier));
                const url = this.dataset.orderUrl;
                         console.log("Redirection vers :", url);
                         window.location.href = url;
            }
        });
    }
    
}

document.addEventListener("DOMContentLoaded", afficherPanier);
