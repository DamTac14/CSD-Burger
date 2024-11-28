import { fetchDishes, fetchMenus } from "./api.js";

export async function loadMenus() {
  const menuList = document.getElementById("menu-list");

  // Appelle l'API pour récupérer les menus
  const menus = await fetchMenus();
  menuList.innerHTML = ""; // Vide le contenu précédent

  // Affiche chaque menu
  menus.forEach(menu => {
    const menuItem = document.createElement("div");
    menuItem.classList.add("menu-item");
    menuItem.innerHTML = `
      <h3>${menu.name}</h3>
      <p>${menu.description}</p>
      <p>Prix : ${menu.price}€</p>
      <button data-id="${menu.id}" class="add-to-order">Ajouter</button>
    `;
    menuList.appendChild(menuItem);
  });
}
export function setupCategoryNavigation() {
  const menuNav = document.getElementById("menu-navigation");

  menuNav.addEventListener("click", async (event) => {
    const button = event.target.closest("button");
    if (!button) return;

    const category = button.dataset.category;

    if (category === "menus") {
      // Charge et affiche uniquement les menus
      await loadMenus();
    } else {
      console.log(`Catégorie non prise en charge pour le moment : ${category}`);
    }

    // Mettre à jour l'état visuel du bouton sélectionné
    document.querySelector("#menu-navigation .menu-selected")?.classList.remove("menu-selected");
    button.classList.add("menu-selected");
  });
}
