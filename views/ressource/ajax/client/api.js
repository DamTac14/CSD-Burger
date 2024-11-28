const API_BASE_URL = "/api";
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


// Plats
export async function fetchDishes() {
  const response = await fetch(`${API_BASE_URL}/dishes`);
  return response.ok ? await response.json() : [];
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
