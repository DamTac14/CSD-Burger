import { fetchDishes } from "./api.js";

export async function loadMenu(category) {
  const menuList = document.getElementById("menu-list");

  // Fonction pour récupérer les plats en fonction de la catégorie
  const dishes = await fetchDishes(); // Ici, tu peux adapter cette fonction pour qu'elle prenne en compte la catégorie
  menuList.innerHTML = ""; // Vide le contenu précédent

  // Filtrer les plats en fonction de la catégorie
  const filteredDishes = dishes.filter(dish => dish.category === category);

  // Afficher les plats filtrés dans la liste
  filteredDishes.forEach(dish => {
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
export function setupCategoryNavigation() {
  const menuNav = document.getElementById("menu-navigation");

  menuNav.addEventListener("click", async (event) => {
    const button = event.target.closest("button");
    if (!button) return; // Ignore si ce n'est pas un bouton

    const category = button.dataset.category;

    if (category) {
      // Changer la classe de sélection
      document.querySelector("#menu-navigation .menu-selected")?.classList.remove("menu-selected");
      button.classList.add("menu-selected");

      // Charger le menu en fonction de la catégorie sélectionnée
      if (category === "menus") {
        await loadMenu();  // Charger les menus uniquement
      } else {
        // Ajouter le comportement pour d'autres catégories si nécessaire
        console.log(`Catégorie sélectionnée : ${category}`);
      }
    }
  });
}
