import { showScreen } from "./navigation.js";
import { loadMenu, setupCategoryNavigation } from "./menu.js";

document.addEventListener("DOMContentLoaded", () => {
  const btnStart = document.getElementById("btn-start");
  const btnConfirmOrder = document.getElementById("btn-confirm-order");
  let inactivityTimer;

  // Réinitialise l'inactivité
  function resetInactivityTimer() {
    clearTimeout(inactivityTimer);
    inactivityTimer = setTimeout(() => {
      showScreen("screen-home"); // Retourne à l'écran d'accueil après 60s
    }, 60000);
  }

  // Commencer la commande
  btnStart.addEventListener("click", () => {
    showScreen("screen-service");
    resetInactivityTimer();
  });

  // Choix du service (À emporter / Sur place)
  document.querySelectorAll(".service-btn").forEach(btn => {
    btn.addEventListener("click", async () => {
      await loadMenu(); // Charge le menu complet
      showScreen("screen-menu");
      resetInactivityTimer();
    });
  });

  // Confirmation de commande
  btnConfirmOrder.addEventListener("click", () => {
    console.log("Commande annulée !");
    showScreen("screen-home");
    resetInactivityTimer();
  });

  // Initialiser la navigation par catégorie
  setupCategoryNavigation();

  // Gestion de l'inactivité
  document.addEventListener("mousemove", resetInactivityTimer);
  document.addEventListener("click", resetInactivityTimer);

  // Minuteur initial
  resetInactivityTimer();
});
