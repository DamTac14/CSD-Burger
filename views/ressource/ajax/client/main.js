import { showScreen } from "./navigation.js";
import { loadMenu } from "./menu.js";

document.addEventListener("DOMContentLoaded", () => {
  const btnStart = document.getElementById("btn-start");
  const btnConfirmOrder = document.getElementById("btn-confirm-order");
  const menuNav = document.getElementById("menu-navigation");

  let inactivityTimer;

  // Réinitialise l'inactivité
  function resetInactivityTimer() {
    clearTimeout(inactivityTimer);
    inactivityTimer = setTimeout(() => {
      showScreen("screen-home");
    }, 60000); 
  }

  // Affiche l'écran de service lorsque l'utilisateur clique sur "Commencer"
  btnStart.addEventListener("click", () => {
    showScreen("screen-service");
    resetInactivityTimer();
  });

  // Gère le choix du service (à emporter ou sur place)
  document.querySelectorAll(".service-btn").forEach(btn => {
    btn.addEventListener("click", async (event) => {
      const serviceType = event.target.dataset.service;
      console.log(`Service choisi : ${serviceType}`);
      await loadMenu(); // Charge tous les menus par défaut
      showScreen("screen-menu"); // Affiche l'écran du menu
      resetInactivityTimer();
    });
  });

  // Gère le filtrage par catégorie dans le menu
  menuNav.addEventListener("click", (event) => {
    const category = event.target.dataset.category;
    if (category) {
      loadMenu(category); // Charge les items de la catégorie choisie
    }
    resetInactivityTimer();
  });

  // Confirmer la commande
  btnConfirmOrder.addEventListener("click", () => {
    console.log("Commande confirmée !");
    showScreen("screen-confirm");
    resetInactivityTimer();
  });

  // Gère l'inactivité globale
  document.addEventListener("mousemove", resetInactivityTimer);
  document.addEventListener("click", resetInactivityTimer);

  // Minuteur initial
  resetInactivityTimer();
});
