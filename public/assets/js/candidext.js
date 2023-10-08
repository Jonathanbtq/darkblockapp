// Attendez que le document soit prêt
$(document).ready(function() {
    // Sélectionnez l'élément d'entrée de fichier par son identifiant
    var fileInput = document.getElementById('candidature_form_productImgs');
    
    // Écoutez l'événement de changement de fichier
    fileInput.addEventListener('change', function(event) {
        var files = event.target.files;
        var validExtensions = ['jpg', 'jpeg', 'png']; // Extensions de fichiers autorisées
        
        // Vérifiez chaque fichier sélectionné
        for (var i = 0; i < files.length; i++) {
            var file = files[i];
            var fileName = file.name;
            var fileExtension = fileName.split('.').pop().toLowerCase();
            
            // Vérifiez si l'extension est valide
            if (validExtensions.indexOf(fileExtension) === -1) {
                alert('Vous utilisez un mauvais fichier, veuillez y uploader seulement des images');
                // Réinitialisez l'élément d'entrée de fichier pour supprimer les fichiers sélectionnés
                event.target.value = '';
                return; // Arrêtez la vérification en cas d'erreur
            }
        }
    });
});