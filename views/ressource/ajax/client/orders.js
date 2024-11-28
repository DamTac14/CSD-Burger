import { addOrder } from "./api.js";

export function createOrder(items, total) {
  const order = {
    customerName: "Client", // Placeholder
    items,
    total,
  };

  addOrder(order).then(response => {
    if (response) {
      alert("Commande validée !");
    } else {
      alert("Erreur lors de la validation de la commande.");
    }
  });
}
