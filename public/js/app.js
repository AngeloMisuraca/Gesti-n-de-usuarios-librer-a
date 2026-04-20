const API_BASE = "../backend";
const addButton = document.getElementById("addButton");
const logoutButton = document.getElementById("logoutButton");
const usernameLabel = document.getElementById("usernameLabel");
const bookId = document.getElementById("bookId");
const title = document.getElementById("title");
const author = document.getElementById("author");
const year = document.getElementById("year");
const cardsContainer = document.getElementById("cardsContainer");

window.addEventListener("load", async () => {
  const savedUsername = sessionStorage.getItem("username") || "";
  if (savedUsername) {
    usernameLabel.innerHTML = `Usuario: ${savedUsername}`;
  }

  const session = await fetchJson(`${API_BASE}/session.php`);

  if (!session.logged) {
    sessionStorage.removeItem("username");
    window.location.href = "login.html";
    return;
  }

  const currentUsername = session.username || session.user || savedUsername;
  usernameLabel.innerHTML = currentUsername ? `Usuario: ${currentUsername}` : "Usuario conectado";
  sessionStorage.setItem("username", currentUsername);

  addButton?.addEventListener("click", saveBook);
  logoutButton?.addEventListener("click", logout);

  loadBooks();
});

async function fetchJson(url, options = {}) {
  const response = await fetch(url, options);
  return response.json();
}

async function loadBooks() {
  const books = await fetchJson(`${API_BASE}/getBooks.php`);
  cardsContainer.innerHTML = "";

  books.forEach((book) => {
    const card = document.createElement("div");
    card.className = "card";
    card.innerHTML = `
      <h3>${book.title}</h3>
      <p>${book.author}</p>
      <small>${book.year}</small>
      <button class="dashboard-button" data-edit="${book.id}">Editar</button>
      <button class="dashboard-button" data-delete="${book.id}">Eliminar</button>
    `;
    cardsContainer.appendChild(card);
  });

  document.querySelectorAll("[data-edit]").forEach((button) => {
    button.addEventListener("click", () => fillForm(Number(button.dataset.edit), books));
  });

  document.querySelectorAll("[data-delete]").forEach((button) => {
    button.addEventListener("click", () => removeBook(Number(button.dataset.delete)));
  });
}

function fillForm(id, books) {
  const book = books.find((item) => item.id == id);
  if (!book) return;

  bookId.value = book.id;
  title.value = book.title;
  author.value = book.author;
  year.value = book.year;
}

async function saveBook() {
  const book = {
    id: bookId.value,
    title: title.value.trim(),
    author: author.value.trim(),
    year: year.value
  };

  const url = book.id ? `${API_BASE}/editBook.php` : `${API_BASE}/addBook.php`;
  await fetchJson(url, {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(book)
  });

  bookId.value = "";
  title.value = "";
  author.value = "";
  year.value = "";

  loadBooks();
}

async function removeBook(id) {
  await fetchJson(`${API_BASE}/deleteBook.php`, {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ id })
  });

  loadBooks();
}

async function logout() {
  await fetchJson(`${API_BASE}/logout.php`, { method: "POST" });
  sessionStorage.removeItem("username");
  window.location.href = "login.html";
}
