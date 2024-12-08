// Enregistre un utilisateur
async function register(lastname, firstname, email, password) {
    try {
        const response = await fetch('./api/v1.0/auth/register', {
            method: 'POST',
            body: JSON.stringify({
                lastname: lastname,
                firstname: firstname,
                email: email,
                password: password,
            }),
        });

        const data = await response.json();

        return [response, data];
    } catch (error) {
        throw new Error(error);
    }
}

// Connecte un utilisateur
async function login(email, password) {
    try {
        const response = await fetch('./api/v1.0/auth/login', {
            method: 'POST',
            body: JSON.stringify({
                email: email,
                password: password,
            }),
        });

        const data = await response.json();

        return [response, data];
    } catch (error) {
        throw new Error(error);
    }
}

// Déconnecte un utilisateur
async function logout() {
    try {
        const response = await fetch('./api/v1.0/auth/logout', {
            method: 'GET',
        });

        const data = await response.json();

        return [response, data];
    } catch (error) {
        throw new Error(error);
    }
}

// Crée un produit
async function createProduit(name, description, price, creationDate) {
    try {
        const response = await fetch('./api/v1.0/produit/new', {
            method: 'POST',
            body: JSON.stringify({
                nom: name,
                description: description,
                prix: price,
                date_creation: creationDate,
            }),
        });

        const data = await response.json();

        return [response, data];
    } catch (error) {
        throw new Error(error);
    }
}

// Récupère tous les produits
async function getProduits() {
    try {
        const response = await fetch('./api/v1.0/produit/list', {
            method: 'GET',
        });

        const data = await response.json();

        return [response, data];
    } catch (error) {
        throw new Error(error);
    }
}

// Récupére un produit
async function getProduit(id) {
    const response = await fetch(`./api/v1.0/produit/listone/${id}`, {
        method: 'GET',
    });

    const data = await response.json();

    return [response, data];
}

// Modifie un produit
async function updateProduit(id, name, description, price, creationDate) {
    const response = await fetch('./api/v1.0/produit/update', {
        method: 'PUT',
        body: JSON.stringify({
            id: id,
            nom: name,
            description: description,
            prix: price,
            date_creation: creationDate,
        }),
    });

    const data = await response.json();

    return [response, data];
}

// Supprime un produit
async function deleteProduit(id) {
    // Si l'on préfère passer l'id par le body, il faut inverser les commentaires ci-dessous
    const response = await fetch(`./api/v1.0/produit/delete/${id}`, {
        method: 'DELETE',
    });
    // const response = await fetch('./api/v1.0/produit/delete', {
    //     method: 'DELETE',
    //     body: JSON.stringify({
    //         id: id,
    //     }),
    // });

    const data = await response.json();

    return [response, data];
}

// Affiche tous les produits
function displayProduits(produits) {
    readAllDisplay.style.color = 'black';
    readAllDisplay.innerHTML = '';
    produits.forEach((produit) => {
        readAllDisplay.innerHTML += `
            <div class="col-4 p-1">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">${produit.nom}</h5>
                        <h6 class="card-subtitle mb-2 text-body-secondary">${produit.prix} €</h6>
                        <p class="card-text">${produit.description}</p>
                        <h6 class="card-subtitle mb-2 text-body-secondary">${produit.date_creation}</h6>
                    </div>
                </div>
            </div>
        `;
    });
}

// Affiche un produit
function displayProduit(produit) {
    readOneDisplay.style.color = 'black';
    readOneDisplay.innerHTML = `
        <div class="col-4 p-1">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">${produit.nom}</h5>
                    <h6 class="card-subtitle mb-2 text-body-secondary">${produit.prix} €</h6>
                    <p class="card-text">${produit.description}</p>
                    <h6 class="card-subtitle mb-2 text-body-secondary">${produit.date_creation}</h6>
                </div>
            </div>
        </div>
    `;
}

// Affichage un message à l'endroit et de la couleur souhaités
function displayMessage(place, color, message) {
    place.style.color = color;
    place.innerText = message;
}

// Enregistrement
const registerForm = document.getElementById('registerForm');
const registerLastname = document.getElementById('registerLastname');
const registerFirstname = document.getElementById('registerFirstname');
const registerEmail = document.getElementById('registerEmail');
const registerPassword = document.getElementById('registerPassword');
const registerDisplay = document.getElementById('registerDisplay');

registerForm.addEventListener('submit', async (e) => {
    e.preventDefault();

    try {
        const [response, data] = await register(registerLastname.value, registerFirstname.value, registerEmail.value, registerPassword.value);

        if (response.ok) {
            displayMessage(registerDisplay, 'green', data.message);
        } else {
            displayMessage(registerDisplay, 'red', data.message);
        }
    } catch (error) {
        displayMessage(registerDisplay, 'red', error);
    }
});

// Connexion
const loginForm = document.getElementById('loginForm');
const loginEmail = document.getElementById('loginEmail');
const loginPassword = document.getElementById('loginPassword');
const loginDisplay = document.getElementById('loginDisplay');

loginForm.addEventListener('submit', async (e) => {
    e.preventDefault();

    try {
        const [response, data] = await login(loginEmail.value, loginPassword.value);

        if (response.ok) {
            displayMessage(loginDisplay, 'green', data.message);
        } else {
            displayMessage(loginDisplay, 'red', data.message);
        }
    } catch (error) {
        displayMessage(loginDisplay, 'red', error);
    }
});

// Déconnexion
const logoutForm = document.getElementById('logoutForm');
const logoutDisplay = document.getElementById('logoutDisplay');

logoutForm.addEventListener('submit', async (e) => {
    e.preventDefault();

    try {
        const [response, data] = await logout();

        if (response.ok) {
            displayMessage(logoutDisplay, 'green', data.message);
        } else {
            displayMessage(logoutDisplay, 'red', data.message);
        }
    } catch (error) {
        displayMessage(logoutDisplay, 'red', error);
    }
});

// Création
const createForm = document.getElementById('createForm');
const createName = document.getElementById('createName');
const createDescription = document.getElementById('createDescription');
const createPrice = document.getElementById('createPrice');
const createCreationDate = document.getElementById('createCreationDate');
const createDisplay = document.getElementById('createDisplay');

createForm.addEventListener('submit', async (e) => {
    e.preventDefault();

    try {
        const [response, data] = await createProduit(createName.value, createDescription.value, createPrice.value, createCreationDate.value);

        if (response.ok) {
            displayMessage(createDisplay, 'green', data.message);
        } else {
            displayMessage(createDisplay, 'red', data.message);
        }
    } catch (error) {
        displayMessage(createDisplay, 'red', error);
    }
});

// Lire tous
const readAllForm = document.getElementById('readAllForm');
const readAllDisplay = document.getElementById('readAllDisplay');

readAllForm.addEventListener('submit', async (e) => {
    e.preventDefault();

    try {
        const [response, data] = await getProduits();

        if (response.ok) {
            displayProduits(data);
        } else {
            displayMessage(readAllDisplay, 'red', data.message);
        }
    } catch (error) {
        displayMessage(readAllDisplay, 'red', error);
    }
});

// Lire un
const readOneForm = document.getElementById('readOneForm');
const readOneId = document.getElementById('readOneId');
const readOneDisplay = document.getElementById('readOneDisplay');

readOneForm.addEventListener('submit', async (e) => {
    e.preventDefault();

    try {
        const [response, data] = await getProduit(readOneId.value);

        if (response.ok) {
            displayProduit(data);
        } else {
            displayMessage(readOneDisplay, 'red', data.message);
        }
    } catch (error) {
        displayMessage(readOneDisplay, 'red', error);
    }
});

// Modifier un produit
const updateForm = document.getElementById('updateForm');
const updateId = document.getElementById('updateId');
const updateName = document.getElementById('updateName');
const updateDescription = document.getElementById('updateDescription');
const updatePrice = document.getElementById('updatePrice');
const updateCreationDate = document.getElementById('updateCreationDate');
const updateDisplay = document.getElementById('updateDisplay');

updateForm.addEventListener('submit', async (e) => {
    e.preventDefault();

    try {
        const [response, data] = await updateProduit(updateId.value, updateName.value, updateDescription.value, updatePrice.value, updateCreationDate.value);

        if (response.ok) {
            displayMessage(updateDisplay, 'green', data.message);
        } else {
            displayMessage(updateDisplay, 'red', data.message);
        }
    } catch (error) {
        displayMessage(updateDisplay, 'red', error);
    }
});

// Supprimer un produit
const deleteForm = document.getElementById('deleteForm');
const deleteId = document.getElementById('deleteId');
const deleteDisplay = document.getElementById('deleteDisplay');

deleteForm.addEventListener('submit', async (e) => {
    e.preventDefault();

    try {
        const [response, data] = await deleteProduit(deleteId.value);

        if (response.ok) {
            displayMessage(deleteDisplay, 'green', data.message);
        } else {
            displayMessage(deleteDisplay, 'red', data.message);
        }
    } catch (error) {
        displayMessage(deleteDisplay, 'red', error);
    }
});
