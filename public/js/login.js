const loginForm = document.getElementById("loginForm");
const errorMessage = document.getElementById("error");

loginForm?.addEventListener("submit", async (event) => {
  event.preventDefault();
  errorMessage.textContent = "";

  const payload = {
    username: document.getElementById("username").value.trim(),
    password: document.getElementById("password").value
  };

  try {
    const response = await fetch("../backend/login.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(payload)
    });

    const result = await response.json();

    if (result.success) {
      sessionStorage.setItem("username", result.username || payload.username);
      window.location.href = "dashboard.html";
      return;
    }

    errorMessage.textContent = result.message || "No se pudo iniciar sesión";
  } catch (error) {
    errorMessage.textContent = "No se pudo conectar con el servidor";
  }
});
