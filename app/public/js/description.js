const button = document.querySelector(".btnAjout");
        if (button) {
            button.addEventListener('click', function (event) {
                event.preventDefault();
                let panier = JSON.parse(localStorage.getItem("panier")) || [];

                const produitAjoute = {
                    id: parseInt(this.dataset.id),
                    nom: document.getElementById('nom-produit').textContent,
                    image: document.getElementById('image-produit').getAttribute('src'),
                    prix: parseFloat(document.getElementById('prix-produit').textContent),
                    description: document.getElementById('description-produit').textContent,
                    quantity: 1,

                };



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

    } else {
        document.body.innerHTML = "<p>Aucun produit trouvé.</p>";
    }

