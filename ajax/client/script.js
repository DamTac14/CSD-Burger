// Écrans
const screens = document.querySelectorAll(".screen");

// Boutons de navigation
const btnStart = document.getElementById("btn-start");
const btnConfirmOrder = document.getElementById("btn-confirm-order");
const btnCancel = document.getElementById("btn-cancel");
const btnBack = document.getElementById("btn-back");

// Gestion de l'affichage des écrans
function showScreen(screenId) {
  screens.forEach(screen => screen.classList.remove("active"));
  document.getElementById(screenId).classList.add("active");
}

// Écran d'accueil -> Choix du type de service
btnStart.addEventListener("click", () => showScreen("screen-service"));

// Navigation service -> menu
document.querySelectorAll(".service-btn").forEach(btn => {
  btn.addEventListener("click", async () => {
    // Charger les menus dynamiquement via l'API
    const menus = await fetchMenus();
    renderMenuList(menus);
    showScreen("screen-menu");
  });
});

// Confirmer commande
btnConfirmOrder.addEventListener("click", () => showScreen("screen-confirm"));

// Annuler commande
btnCancel.addEventListener("click", () => alert("Commande annulée !"));

// Retourner au menu
btnBack.addEventListener("click", () => showScreen("screen-menu"));

// Fetch menus depuis l'API
async function fetchMenus() {
  try {
    const response = await fetch("/api/menus");
    return await response.json();
  } catch (error) {
    console.error("Erreur lors du chargement des menus :", error);
    return [];
  }
}

// Rendre les menus dynamiquement
function renderMenuList(menus) {
  const menuList = document.getElementById("menu-list");
  menuList.innerHTML = ""; // Réinitialiser le contenu
  menus.forEach(menu => {
    const menuItem = document.createElement("div");
    menuItem.classList.add("menu-item");
    menuItem.innerHTML = `
      <h3>${menu.name}</h3>
      <p>${menu.description}</p>
      <p>Prix : ${menu.price}€</p>
    `;
    menuList.appendChild(menuItem);
  });
}
