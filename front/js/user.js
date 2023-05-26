document.addEventListener('DOMContentLoaded', function() {
    // Récupérer les données de l'API (simulées ici)
    var user = {
        "id": 2,
        "pseudo": "injected2",
        "age": 20,
        "description": "native 2 injected",
        "photo": "/public/no/path/4321.543.32.png",
        "role": "admin"
    };

    // Insérer les données de l'utilisateur dans les éléments HTML appropriés
    document.getElementById('user-id').textContent = user.id;
    document.getElementById('user-pseudo').textContent = user.pseudo;
    document.getElementById('user-age').textContent = user.age;
    document.getElementById('user-description').textContent = user.description;
    document.getElementById('user-photo').src = user.photo;
    document.getElementById('user-role').textContent = user.role;

    // Supprimer l'utilisateur
    var deleteBtn = document.getElementById('delete-btn');
    deleteBtn.addEventListener('click', function() {
        // Ajoute ici la logique pour supprimer l'utilisateur de l'API
        // Par exemple, tu peux faire une requête AJAX vers l'API pour effectuer la suppression
        console.log("Utilisateur supprimé !");
    });
});
