const API_BASE = "../backend";
const formSection = document.getElementById("formSection");
const addButton = document.getElementById("addButton");
const logoutButton = document.getElementById("logoutButton");
const bookId = document.getElementById("bookId");
const title = document.getElementById("title");
const author = document.getElementById("author");
const year = document.getElementById("year");

window.addEventListener("load", async () => {
  const session = await checkSession();

  if (!session.logged) {
    window.location.href = "login.html";
    return;
  }

  if (addButton) {
    addButton.addEventListener("click", saveBook);
  }

  if (logoutButton) {
    logoutButton.addEventListener("click", logout);
  }

  getBooks();
});

async function getBooks() {
  const res = await fetch(`${API_BASE}/getBooks.php`);
  const data = await res.json();
  populateCards(data);
}

async function addBook(book) {
  await fetch(`${API_BASE}/addBook.php`, {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(book)
  });
}

async function editBook(book) {
  await fetch(`${API_BASE}/editBook.php`, {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify(book)
  });
}

async function deleteBook(id) {
  await fetch(`${API_BASE}/deleteBook.php`, {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ id })
  });

  getBooks();
}

async function checkSession() {
  const res = await fetch(`${API_BASE}/session.php`);
  return res.json();
}

async function logout() {
  await fetch(`${API_BASE}/logout.php`, { method: "POST" });
  window.location.href = "login.html";
}

function populateCards(books) {
  const container = document.getElementById("cardsContainer");
  container.innerHTML = "";

  books.forEach((book) => {
    const card = document.createElement("div");
    card.className = "card";

    card.innerHTML = `
      <h3>${book.title}</h3>
      <p>${book.author}</p>
      <small>${book.year}</small>
      <button onclick='fillForm(${JSON.stringify(book)})'>Editar</button>
      <button onclick='deleteBook(${book.id})'>Eliminar</button>
    `;

    container.appendChild(card);
  });
}

function fillForm(book) {
  formSection.classList.remove("hidden");
  bookId.value = book.id;
  title.value = book.title;
  author.value = book.author;
  year.value = book.year;
}

async function saveBook() {
  const book = {
    id: bookId.value,
    title: title.value,
    author: author.value,
    year: year.value
  };

  if (book.id) {
    await editBook(book);
  } else {
    await addBook(book);
  }

  bookId.value = "";
  title.value = "";
  author.value = "";
  year.value = "";

  getBooks();
}
