import { showScreen } from "./navigation.js";
import { loadMenu, setupCategoryNavigation } from "./menu.js";

document.addEventListener("DOMContentLoaded", () => {
  const btnStart = document.getElementById("btn-start");
  const cartItems = document.getElementById("cart-items");
  let inactivityTimer;
  let currentScreen = "screen-home"; // Écran actif par défaut
  let cart = []; // Le panier pour stocker les articles

  // Réinitialise l'inactivité
  function resetInactivityTimer() {
    clearTimeout(inactivityTimer);
    inactivityTimer = setTimeout(() => {
      if (currentScreen !== "screen-home") {
        showScreen("screen-home"); // Retourne à l'écran d'accueil après inactivité
      }
    }, 60000); // 1 minute d'inactivité
  }

  // Mémorise l'écran actif et active le minuteur
  function setCurrentScreen(screen) {
    currentScreen = screen;
    showScreen(screen);
    resetInactivityTimer();
  }

  // Commencer la commande
  btnStart.addEventListener("click", () => {
    setCurrentScreen("screen-service");
    btnConfirmOrder.style.display = 'block'; // Affiche le bouton annuler commande
  });

  // Choix du service (À emporter / Sur place)
  document.querySelectorAll(".service-btn").forEach(btn => {
    btn.addEventListener("click", async () => {
      await loadMenu(); // Charge le menu complet
      setCurrentScreen("screen-menu");
    });
  });

  // Ajouter un article au panier
  function addToCart(item) {
    cart.push(item);
    updateCart();
  }

  // Mettre à jour le panier à chaque ajout
  function updateCart() {
    cartItems.innerHTML = ''; // Vide le panier
    cart.forEach(item => {
      const li = document.createElement('li');
      li.textContent = item;
      cartItems.appendChild(li);
    });
  }


  // Revenir en arrière sans annuler
  document.getElementById("btn-back").addEventListener("click", () => {
    setCurrentScreen("screen-menu");
  });

  // Initialiser la navigation par catégorie
  setupCategoryNavigation();

  // Ajouter des articles au panier comme exemple
  // Par exemple, si l'utilisateur choisit un menu
  document.querySelectorAll("#menu-navigation button").forEach(btn => {
    btn.addEventListener("click", () => {
      addToCart(`Article: ${btn.textContent}`); // Ajoute le nom de la catégorie ou autre info pertinente
    });
  });

  const btnCancel = document.getElementById("btn-cancel");
  const btnBack = document.getElementById("btn-back");
  const btnConfirmOrder = document.getElementById("btn-confirm-order");

  // Fonction pour revenir à l'écran d'accueil
  btnCancel.addEventListener("click", () => {
    setCurrentScreen("screen-home"); // Retour à l'écran d'accueil
  });

  // Fonction pour revenir à l'écran précédent
  btnBack.addEventListener("click", () => {
    setCurrentScreen(currentScreen); // Retour à l'écran actuel
  });

  // Affichage du bouton Annuler commande
  btnConfirmOrder.addEventListener("click", () => {
    setCurrentScreen("screen-confirm"); // Afficher l'écran de confirmation
  });

  // Gestion de l'inactivité
  document.addEventListener("mousemove", resetInactivityTimer);
  document.addEventListener("click", resetInactivityTimer);

  // Minuteur initial
  resetInactivityTimer();
});
