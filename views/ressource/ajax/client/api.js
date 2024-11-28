const API_BASE_URL = "/api";

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
