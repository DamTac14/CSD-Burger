import { fetchDishes } from "./api.js";

export async function loadMenu() {
  const menuList = document.getElementById("menu-list");
  const dishes = await fetchDishes();

  menuList.innerHTML = "";
  dishes.forEach(dish => {
    const menuItem = document.createElement("div");
    menuItem.classList.add("menu-item");
    menuItem.innerHTML = `
      <h3>${dish.name}</h3>
      <p>${dish.ingredients.join(", ")}</p>
      <p>Prix : ${dish.price}€</p>
      <button data-id="${dish.id}" class="add-to-order">Ajouter</button>
    `;
    menuList.appendChild(menuItem);
  });
}

// Gestion des boutons de navigation (À emporter / Sur place)
export function setupServiceButtons() {
  const serviceButtons = document.querySelectorAll('.service-btn');
  serviceButtons.forEach(button => {
    button.addEventListener('click', () => {
      showScreen('screen-menu'); // Affiche l'écran menu
    });
  });
}


export function setupCategoryNavigation() {
  const menuNav = document.getElementById("menu-navigation");

  menuNav.addEventListener("click", async (event) => {
    // Vérifie si le clic est sur un bouton ou un de ses enfants
    const button = event.target.closest("button");
    if (!button) return; // Ignore si ce n'est pas un bouton

    const category = button.dataset.category;
    if (category) {
      document.querySelector("#menu-navigation .menu-selected")?.classList.remove("menu-selected");
      button.classList.add("menu-selected");
      await loadMenu(category); // Charge les plats correspondant à la catégorie
    }
  });
}


