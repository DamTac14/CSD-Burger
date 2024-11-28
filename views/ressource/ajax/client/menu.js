import { fetchDishes, fetchMenus } from "./api.js";
export async function loadMenus() {
  const menuList = document.getElementById("menu-list");

  // Appelle l'API pour récupérer les menus
  const menus = await fetchMenus();
  menuList.innerHTML = ""; // Vide le contenu précédent

  // Affiche chaque menu avec son prix et ses allergènes
  menus.forEach(menu => {
    const menuItem = document.createElement("div");
    menuItem.classList.add("menu-item");
    menuItem.innerHTML = `
      <h3>${menu.name}</h3>
      <p>Prix : ${menu.menu_price}€</p>
      <p>Allergènes : ${menu.allergens.join(", ")}</p>
      <button data-id="${menu.id}" class="add-to-order">Sélectionner</button>
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

export async function loadMenuDetails(menuId) {
  const menuDetailsContainer = document.getElementById("menu-details");

  // Appelle l'API pour récupérer les plats associés au menu
  const menuWithDishes = await fetch(`/CSDBurger/CSD-Burger/api/menu/${menuId}/dishes`);
  const dishes = await menuWithDishes.json();

  menuDetailsContainer.innerHTML = ""; // Vide le contenu précédent

  // Affiche le menu avec ses plats
  const menuInfo = document.createElement("div");
  menuInfo.classList.add("menu-info");
  menuInfo.innerHTML = `
    <h2>${dishes[0].menu_name}</h2>
    <img src="${dishes[0].menu_image}" alt="${dishes[0].menu_name}" />
    <h3>Plats :</h3>
  `;

  // Affiche les plats du menu
  const dishesList = document.createElement("ul");
  dishes.forEach(dish => {
    const dishItem = document.createElement("li");
    dishItem.textContent = `${dish.dish_name} - Ingrédients: ${dish.ingredients}`;
    dishesList.appendChild(dishItem);
  });

  menuInfo.appendChild(dishesList);
  menuDetailsContainer.appendChild(menuInfo);
}
