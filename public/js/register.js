const registerForm = document.getElementById("registerForm");
const registerMessage = document.getElementById("message");

registerForm?.addEventListener("submit", async (event) => {
  event.preventDefault();
  registerMessage.textContent = "";

  const payload = {
    username: document.getElementById("username").value.trim(),
    password: document.getElementById("password").value
  };

  try {
    const response = await fetch("../backend/register.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(payload)
    });

    const result = await response.json();

    if (result.success) {
      registerMessage.textContent = "Registro completado. Redirigiendo al login...";
      setTimeout(() => {
        window.location.href = "login.html";
      }, 1200);
      return;
    }

    registerMessage.textContent = result.message || "No se pudo completar el registro";
  } catch (error) {
    registerMessage.textContent = "No se pudo conectar con el servidor";
  }
});
