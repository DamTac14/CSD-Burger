import { showScreen } from "./navigation.js";
import { loadMenus, setupCategoryNavigation, loadDishesByCategory } from "./menu.js";

document.addEventListener("DOMContentLoaded", () => {
  const btnStart = document.getElementById("btn-start");
  const cartItems = document.getElementById("cart-items");
  const bottomOfScreen = document.getElementById("bottom-of-screen");
  const btnConfirmOrder = document.getElementById("btn-confirm-order");

  let inactivityTimer;
  let currentScreen = "screen-home"; // Écran actif par défaut
  let serviceChoice = null; // Variable pour mémoriser le choix du service (null, "emporter", "sur place")

  const btnCancel = document.getElementById("btn-cancel");
  const btnBack = document.getElementById("btn-back");

  // Gérer le clic sur le bouton "Oui" (annuler la commande)
  btnCancel.addEventListener("click", () => {
    cart = []; // Vider le panier
    serviceChoice = null; // Réinitialiser le choix du service
    updateCart(); // Mettre à jour l'affichage du panier (panier vide)
    setCurrentScreen("screen-home"); // Retourner à l'écran d'accueil
  });

  // Gérer le clic sur le bouton "Non" (retourner à la commande)
  btnBack.addEventListener("click", () => {
    setCurrentScreen("screen-menu"); // Retourner à l'écran du menu
  });

  // Réinitialise le minuteur d'inactivité
  function resetInactivityTimer() {
    clearTimeout(inactivityTimer);
    inactivityTimer = setTimeout(() => {
      if (currentScreen !== "screen-home") {
        setCurrentScreen("screen-home"); // Retourne à l'écran d'accueil après inactivité
      }
    }, 60000); // 1 minute d'inactivité
  }

  // Définit l'écran actif et gère les états associés
  function setCurrentScreen(screen) {
    currentScreen = screen;
    showScreen(screen);

    // Gérer l'affichage de la barre en bas
    if (screen === "screen-menu") {
      bottomOfScreen.classList.remove("hidden"); // Affiche la barre
    } else {
      bottomOfScreen.classList.add("hidden"); // Masque la barre
    }

    resetInactivityTimer(); // Redémarre le minuteur
  }



  // Ajouter un article à partir du menu ou des plats
  // Sélectionner les boutons avec la classe "add-to-order"
  document.querySelectorAll(".add-to-order").forEach(button => {
    button.addEventListener("click", (event) => {
      const id = event.target.dataset.id;
      const name = event.target.dataset.name;
      const price = event.target.dataset.price;

      // Vérifier que toutes les données nécessaires existent
      if (id && name && price) {
        const item = { id, name, price, type: event.target.classList.contains("menu-item") ? "menu" : "dish" };
        addToCart(item); // Ajouter l'item au panier
      } else {
        console.error("Données manquantes pour l'élément.");
      }
    });
  });

  // Gestion du clic sur "Annuler commande"
  btnConfirmOrder.addEventListener("click", () => {
    setCurrentScreen("screen-confirm"); // Passe à l'écran de confirmation
  });

  // Commencer la commande
  btnStart.addEventListener("click", () => {
    // Si un choix de service a déjà été effectué, on passe directement à l'écran du menu
    if (serviceChoice) {
      setCurrentScreen("screen-menu");
    } else {
      setCurrentScreen("screen-service"); // Sinon, on va à l'écran de sélection du service
    }
  });

  // Choix du service (À emporter / Sur place)
  document.querySelectorAll(".service-btn").forEach(btn => {
    btn.addEventListener("click", async () => {
      // Si un choix a déjà été fait, on ne demande plus
      if (!serviceChoice) {
        serviceChoice = btn.dataset.service; // Sauvegarder le choix du service
        console.log(`Service choisi : ${serviceChoice}`); // Pour le débogage
      }

      await loadMenus(); // Charge le menu complet
      setCurrentScreen("screen-menu"); // Affiche le menu après avoir fait le choix
    });
  });

  // Gestion de l'inactivité
  document.addEventListener("mousemove", resetInactivityTimer);
  document.addEventListener("click", resetInactivityTimer);

  // Initialise la navigation par catégorie
  setupCategoryNavigation();

  // Lance le minuteur initial
  resetInactivityTimer();

  
// Fonction pour valider la commande
async function validateOrder() {
  if (cart.length === 0) {
    alert("Votre panier est vide !");
    return;
  }

  // Préparer les données pour l'API
  const order = {
    number: `ORD-${Date.now()}`, // Numéro de commande unique (généré ici à titre d'exemple)
    items: cart.map(item => ({
      id: item.id,
      name: item.name,
      price: item.price,
      type: item.type,
    })),
    status: "pending", // Statut initial de la commande
    orderDate: new Date().toISOString(), // Date au format ISO
    takeAway: false, // Exemple : modifiable en fonction des préférences utilisateur
  };

  try {
    // Envoi de la commande à l'API
    const result = await addOrder(order);

    if (result && result.success) {
      alert("Votre commande a été validée !");
      console.log("Commande créée :", result);

      // Vider le panier après validation
      cart = [];
      updateCartDisplay(); // Mettre à jour l'affichage
    } else {
      alert("Erreur lors de la validation de la commande.");
      console.error("Erreur API :", result);
    }
  } catch (error) {
    console.error("Erreur lors de l'envoi de la commande :", error);
    alert("Une erreur s'est produite.");
  }
}

});
