const isLocalhost = window.location.hostname === "localhost";
const API_BASE_URL = isLocalhost
  ? "/CSDBurger/CSD-Burger/api" // URL pour le développement local
  : "/api"; // URL pour le serveur distant

export async function fetchMenus() {
  const url = `${API_BASE_URL}/menus`; // Route API pour récupérer les menus

  try {
    const response = await fetch(url, {
      method: "GET",
      headers: {
        "Content-Type": "application/json",
      },
    });

    if (!response.ok) {
      throw new Error(`Erreur lors de la récupération des menus : ${response.statusText}`);
    }

    return await response.json(); // Retourne les menus au format JSON
  } catch (error) {
    console.error("Erreur dans fetchMenus :", error);
    return [];
  }
}

export async function fetchDishes() {
  const response = await fetch(`${API_BASE_URL}/dishes`);
  
  if (!response.ok) {
    console.error("Erreur lors de la récupération des plats:", response.status);
    return [];
  }

  const data = await response.json();
  console.log("Données des plats récupérées:", data); // Affiche les plats pour déboguer
  return data;
}

export async function addDish(dish) {
  const response = await fetch(`${API_BASE_URL}/dishes`, {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(dish),
  });
  return response.ok ? await response.json() : null;
}

// Commandes
export async function fetchOrders() {
  const response = await fetch(`${API_BASE_URL}/orders`);
  return response.ok ? await response.json() : [];
}

export async function addOrder(order) {
  const response = await fetch(`${API_BASE_URL}/orders`, {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(order),
  });
  return response.ok ? await response.json() : null;
}
