
    const form = document.getElementById("form-commande");
    const panierInput = document.getElementById("panier-hidden-input");

    form.addEventListener("submit", function (event) {
        const panier = localStorage.getItem("panier");

        if (!panier) {
            alert("Votre panier est vide !");
            event.preventDefault(); // Empêche l'envoi du formulaire
            return;
        }

        try {
            const parsed = JSON.parse(panier);

            if (!Array.isArray(parsed)) {
                throw new Error("Format du panier invalide");
            }

            panierInput.value = panier;
        } catch (e) {
            alert("Erreur dans le panier : " + e.message);
            event.preventDefault();
        }
    });

