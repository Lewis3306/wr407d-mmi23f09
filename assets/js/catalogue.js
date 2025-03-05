document.querySelectorAll('.ajoutVoiture').forEach(function(button) {
    button.addEventListener('click', function() {
        let id = button.getAttribute('data-id');
        let nom = button.getAttribute('data-nom');
        let prix = button.getAttribute('data-prix');
        ajouterVoiture(id, titre, description, prix);
    });
});

function ajouterVoiture(id, titre, description, prix) {
    let catalogue = JSON.parse(localStorage.getItem('catalogue')) || [];
    let voiture = catalogue.find(j => j.id === id);

    if (voiture) {
        voiture.quantite++;
    } else {
        catalogue.push({ id: id,  titre: titre, description: description,prix: parseFloat(prix), quantite: 1 });
    }

    localStorage.setItem('catalogue', JSON.stringify(catalogue));
    afficherCatalogue();
}