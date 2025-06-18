const accountType = document.getElementById('accountType');
// On récupère l’élément HTML avec l’id (accountType), c’est-à-dire le menu déroulant le "select". On stocke cet élément dans une variable nommée accountType.
const vendeurFields = document.getElementById('vendeurFields');
// On récupère un élément HTML avec l’id vendeurFields (la div contenant les champs spéciaux pour les vendeurs). 



accountType.addEventListener('change', () => {
  if (accountType.value === 'vendeur') {
    vendeurFields.style.display = 'block';
  } else {
    vendeurFields.style.display = 'none';
  }
});
// Si l’utilisateur choisit « vendeur » : On affiche le bloc vendeurFields (display: block) 
// Sinon :
// On cache ce bloc (display: none)

document.getElementById('profileForm').addEventListener('submit', function (e) { 
// On récupère le formulaire (id = profileForm)
// On dit : « Quand ce formulaire est soumis (bouton cliqué) … »
  e.preventDefault();
// On empêche le rechargement de la page ou l’envoi réel du formulaire (car ici on fait une simulation).

  alert('Profil créé ! (simulation)');
  // Ici tu peux enregistrer les données ou les envoyer vers un backend
});






