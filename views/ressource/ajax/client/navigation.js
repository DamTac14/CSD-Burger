const screens = document.querySelectorAll(".screen");

// Change d'écran
export function showScreen(screenId) {
  const screens = document.querySelectorAll(".screen");
  screens.forEach((screen) => {
    if (screen.id === screenId) {
      screen.classList.add("active");
      screen.classList.remove("hidden");
    } else {
      screen.classList.remove("active");
      screen.classList.add("hidden");
    }
  });
}
