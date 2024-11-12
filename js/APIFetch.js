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

        console.log(response);

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
