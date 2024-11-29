import { fetchDishes, fetchMenus } from "./api.js";
// Tableau global pour stocker les articles du panier
let cart = [];

// Fonction pour ajouter un item au panier
function addToCart(item) {
  // Vérifie si l'item est déjà dans le panier (pour éviter les doublons)
  const existingItem = cart.find(cartItem => cartItem.id === item.id && cartItem.type === item.type);
  if (existingItem) {
    console.log('Item déjà ajouté au panier:', existingItem);
  } else {
    cart.push(item);
    console.log('Article ajouté au panier:', item);
  }

  // Mise à jour de l'affichage du panier (facultatif, pour que l'utilisateur voie l'état du panier)
  updateCartDisplay();
}



function updateCartDisplay() {
  const cartContainer = document.getElementById('cart-container'); // Assure-toi que tu as un élément avec l'ID "cart-container" dans ton HTML
  if (!cartContainer) return;

  cartContainer.innerHTML = ''; // Vide l'affichage actuel

  // Affiche chaque élément du panier
  cart.forEach(item => {
    const cartItem = document.createElement('div');
    cartItem.classList.add('cart-item');
    cartItem.innerHTML = `
      <p>${item.name} - ${item.price}€</p>
    `;
    cartContainer.appendChild(cartItem);
  });

  // Affiche le nombre d'articles dans le panier (facultatif)
  const cartCount = document.getElementById('cart-count');
  if (cartCount) {
    cartCount.textContent = cart.length;
  }

  // Calcul du total
  const total = cart.reduce((acc, item) => acc + parseFloat(item.price), 0).toFixed(2);

  // Affiche le total
  const totalDisplay = document.createElement('div');
  totalDisplay.classList.add('cart-total');
  totalDisplay.innerHTML = `<p>Total: ${total}€</p>`;
  cartContainer.appendChild(totalDisplay);

  // Ajoute un bouton pour valider la commande
  const checkoutButton = document.createElement('button');
  checkoutButton.textContent = "Valider la commande";
  checkoutButton.classList.add('checkout-button');
  checkoutButton.addEventListener('click', () => {
    validateOrder();
  });
  cartContainer.appendChild(checkoutButton);
}

// Fonction pour valider la commande
function validateOrder() {
  if (cart.length === 0) {
    alert("Votre panier est vide !");
    return;
  }

  // Simuler la validation de la commande (envoi vers le serveur, etc.)
  console.log("Commande validée:", cart);

  // Vider le panier après la validation
  cart = [];
  updateCartDisplay(); // Mettre à jour l'affichage du panier (le vider)
  alert("Votre commande a été validée !");
}


// Fonction pour charger les menus
export async function loadMenus() {
  const menuList = document.getElementById("menu-list");

  // Appelle l'API pour récupérer les menus
  const menus = await fetchMenus();
  menuList.innerHTML = ""; // Vider le contenu précédent

  // Affiche chaque menu avec son prix et ses allergènes
  menus.forEach(menu => {
    const allergens = menu.allergens.length > 0 ? menu.allergens.join(", ") : "Aucun";

    const menuItem = document.createElement("div");
    menuItem.classList.add("menu-item");
    menuItem.innerHTML = `
      <img src="../uploads/${menu.image}" alt="${menu.name}">
      <h3>${menu.name}</h3>
      <p>Prix : ${menu.menu_price}€</p>
      <p class='menu-item-allergene'>Allergènes :</br> ${allergens}</p>
      <button data-id="${menu.id}" data-name="${menu.name}" data-price="${menu.menu_price}" class="add-to-order">Sélectionner</button>
    `;
    menuList.appendChild(menuItem);
  });

  // Attacher l'événement "click" sur chaque bouton "Sélectionner"
  attachAddToCartEvent();
}

// Fonction pour ajouter l'événement de sélection au panier
function attachAddToCartEvent() {
  document.querySelectorAll(".add-to-order").forEach(button => {
    button.addEventListener("click", (event) => {
      const id = event.target.dataset.id;
      const name = event.target.dataset.name;
      const price = event.target.dataset.price;
      const type = event.target.closest(".menu-item").classList.contains("menu-item") ? "menu" : "dish"; // Vérifie si c'est un menu ou un plat

      // Ajouter l'article sélectionné au panier
      const item = { id, name, price, type };
      addToCart(item); // Ajouter au panier
    });
  });
}



// Fonction pour charger les plats par catégorie
export async function loadDishesByCategory(category) {
  const menuList = document.getElementById("menu-list");

  // Appelle l'API pour récupérer tous les plats
  const dishes = await fetchDishes();

  // Vérifie si les plats ont bien été récupérés
  if (!dishes || dishes.length === 0) {
    console.log("Aucun plat disponible.");
    menuList.innerHTML = "<p>Aucun plat disponible.</p>";
    return;
  }

  console.log("Dishes récupérés :", dishes); // Afficher les données pour vérification

  menuList.innerHTML = ""; // Vider le contenu précédent

  // Filtrer les plats en fonction de la catégorie
  const filteredDishes = dishes.filter(dish => {
    const dishType = (dish.type && dish.type.trim().toLowerCase()) || ""; // Utiliser une chaîne vide par défaut
    const categoryType = category ? category.trim().toLowerCase() : "";

    console.log(`Dish type: "${dishType}", Category: "${categoryType}"`); // Vérifier les valeurs avant le filtrage

    return dishType === categoryType; // Vérifie que les types correspondent
  });

  console.log("Plats filtrés pour la catégorie", category, ":", filteredDishes); // Vérifier les plats filtrés

  if (filteredDishes.length === 0) {
    menuList.innerHTML = "<p>Aucun plat trouvé dans cette catégorie.</p>";
    return;
  }

  // Afficher les plats filtrés
  filteredDishes.forEach(dish => {
    // Récupérer les ingrédients sous forme de liste
    const ingredients = dish.ingredients ? dish.ingredients.map(ingredient => ingredient.ingredient_name).join(", ") : "Aucun";
    const imageName = dish.name.toLowerCase().replace(/\s+/g, "-") + ".jpg";

    const dishItem = document.createElement("div");
    dishItem.classList.add("menu-item");
    dishItem.innerHTML = `
      <img src="../uploads/${imageName}" alt="${dish.name}" class="dish-image">
      <h3>${dish.name}</h3>
      <p>Ingrédients : ${ingredients}</p>
      <button data-id="${dish.id}" data-name="${dish.name}" data-price="${dish.dish_price}" class="add-to-order">Sélectionner</button>
    `;

    menuList.appendChild(dishItem);
  });

  // Attacher l'événement "click" sur chaque bouton "Sélectionner"
  attachAddToCartEvent();
}


export function setupCategoryNavigation() {
  const menuNav = document.getElementById("menu-navigation");

  menuNav.addEventListener("click", async (event) => {
    const button = event.target.closest("button");
    if (!button) return;

    const category = button.dataset.category;

    // Log pour vérifier que la catégorie est capturée correctement
    console.log("Category clicked:", category);

    // Charge et affiche les menus si "menus" est cliqué
    if (category === "menus") {
      await loadMenus();
    } else if (["burger", "sides", "drinks", "desserts"].includes(category.toLowerCase())) {
      // Charge et affiche les plats de la catégorie correspondante
      await loadDishesByCategory(category);
    } else {
      console.log(`Catégorie non prise en charge pour le moment : ${category}`);
    }

    // Mettre à jour l'état visuel du bouton sélectionné
    document.querySelector("#menu-navigation .menu-selected")?.classList.remove("menu-selected");
    button.classList.add("menu-selected");
  });
}

// Fonction pour afficher les détails d'un menu
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

