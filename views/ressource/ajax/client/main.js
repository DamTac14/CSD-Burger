import { showScreen } from "./navigation.js";
import { loadMenus, setupCategoryNavigation } from "./menu.js";

document.addEventListener("DOMContentLoaded", () => {
  const btnStart = document.getElementById("btn-start");
  const cartItems = document.getElementById("cart-items");
  const bottomOfScreen = document.getElementById("bottom-of-screen");
  const btnConfirmOrder = document.getElementById("btn-confirm-order");

  let inactivityTimer;
  let currentScreen = "screen-home"; // Écran actif par défaut
  let cart = []; // Le panier pour stocker les articles
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

  // Ajoute un article au panier
  function addToCart(item) {
    cart.push(item);
    updateCart();
  }

  // Met à jour l'affichage du panier
  function updateCart() {
    const cartEmpty = document.getElementById("cart-empty");

    if (cart.length > 0) {
      cartEmpty.classList.add("hidden");
      cartItems.classList.remove("hidden");
      cartItems.innerHTML = ''; // Vide le contenu existant

      // Ajoute chaque article dans le panier
      cart.forEach(item => {
        const li = document.createElement("div");
        li.textContent = item;
        li.classList.add("cart-item");
        cartItems.appendChild(li);
      });
    } else {
      cartEmpty.classList.remove("hidden"); // Affiche le message "Panier vide"
      cartItems.classList.add("hidden"); // Masque les éléments du panier
      cartItems.innerHTML = '';
    }
  }

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

  // Ajouter des articles au panier depuis le menu
  document.querySelectorAll("#menu-navigation button").forEach(btn => {
    btn.addEventListener("click", () => {
      addToCart(`Article: ${btn.textContent}`); // Exemple d'article ajouté
    });
  });

  // Gestion de l'inactivité
  document.addEventListener("mousemove", resetInactivityTimer);
  document.addEventListener("click", resetInactivityTimer);

  // Initialise la navigation par catégorie
  setupCategoryNavigation();

  // Lance le minuteur initial
  resetInactivityTimer();
});