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
