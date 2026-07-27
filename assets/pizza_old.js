// --- Avant factorisation ---
// gestion du panier de pizzas dans le formulaire de réservation sur la page /pizzas

const addButton = document.getElementById('addPizza');
const pizza = document.getElementById('pizza');
const quantity = document.getElementById('quantity');
const cartBody = document.getElementById('cartBody');
const capacityAlert = document.getElementById('capacityAlert');
let nbItems = 0;
let cartTotal = document.getElementById('cartTotal');
let totalQuantity = 0;
let cartTotalQuantity = document.getElementById('cartTotalQuantity');
const maxQuantity = 5; // To be replaced with the booking slot max quantity

addButton.addEventListener('click', () => {
    const option = pizza.options[pizza.selectedIndex];
    const name = option.dataset.name;
    const price = parseFloat(option.dataset.price);
    let qty = parseInt(quantity.value);
    const total = price * qty;
    const row = document.createElement('tr');

    // Check if the pizza is already in the cart
    const rows = cartBody.querySelectorAll('tr');
    rows.forEach(existingRow => {
        const existingName = existingRow.cells[0].textContent;
        if (existingName === name) {
            let loopQty = qty;
            const increaseButton = existingRow.querySelector('.btnIncrease');
            while(totalQuantity < maxQuantity && loopQty > 0) {
                increaseButton.click(); // Simulate a click on the increase button
                loopQty--;
                if (loopQty <= 0) {
                    break; // Exit the loop if qty is zero or less
                }
            }
            // return; // Exit the function if the pizza is already in the cart
        }
    });

    // Check if qty is available
    if (totalQuantity + qty > maxQuantity) {
        qty = maxQuantity - totalQuantity;
        alert(`La quantité demandée dépasse la capacité disponible pour ce créneau horaire. La quantité a été ajustée à ${qty}.`);
    }

    row.innerHTML = `
        <td>${name}</td>
        <td>${qty}</td>
        <td>${price.toFixed(2)} €</td>
        <td>${total.toFixed(2)} €</td>
        <td class="d-flex align-items-center gap-1">
            <button type="button" class="btn p-0 border-0 bg-transparent btnDecrease">
                <i class="bi bi-dash-circle" width="32" height="32" role="img" aria-label="Bootstrap"></i>
            </button>
            <button type="button" class="btn p-0 border-0 bg-transparent btnIncrease">
                <i class="bi bi-plus-circle" width="32" height="32" role="img" aria-label="Bootstrap"></i>
            </button>
            <button type="button" class="btn btn-danger btn-sm btnRemove">Supprimer</button>
        </td>
    `;

    // Clear the "no items" message if this is the first item and add the new item
    if (nbItems === 0) {
        cartBody.innerHTML = '';
    }
    cartBody.appendChild(row);

    // Update the total and the items count
    cartTotal.textContent = (parseFloat(cartTotal.textContent) + total).toFixed(2);
    if (totalQuantity + qty === maxQuantity) {
        const increaseButtons = document.querySelectorAll('.btnIncrease');
        increaseButtons.forEach(button => {
            button.hidden = true;
        });
        addButton.hidden = true;
        capacityAlert.classList.remove('d-none'); // Hide the capacity alert
    }
    nbItems++;
    totalQuantity += qty;
    cartTotalQuantity.textContent = totalQuantity;

    // Add event listener to the remove button
    const removeButton = row.querySelector('button.btnRemove');
    removeButton.addEventListener('click', () => {

        const rowTotal = parseFloat(row.cells[3].textContent);
        // Update the total and the items count
        cartTotal.textContent = (parseFloat(cartTotal.textContent) - rowTotal).toFixed(2);
        nbItems--;
        totalQuantity -= parseInt(row.cells[1].textContent);
        cartTotalQuantity.textContent = totalQuantity;

        // Remove the item and add the "no items" message if no items are left
        cartBody.removeChild(row);
        if (nbItems === 0) {
            cartBody.innerHTML = '<tr><td colspan="6" class="text-muted">Aucune pizza ajoutée.</td></tr>';
        }
        const increaseButtons = document.querySelectorAll('.btnIncrease');
        increaseButtons.forEach(button => {
            button.hidden = false;
        });
        addButton.hidden = false;
        capacityAlert.classList.add('d-none'); // Show the capacity alert
    });

    // Add event listener to the increase button
    const increaseButton = row.querySelector('button.btnIncrease');
    increaseButton.addEventListener('click', () => {
        const currentQty = parseInt(row.cells[1].textContent);
        const newQty = currentQty + 1;
        row.cells[1].textContent = newQty;
        const unitPrice = parseFloat(row.cells[2].textContent);
        const newTotal = parseFloat(row.cells[2].textContent) * newQty;
        row.cells[3].textContent = newTotal.toFixed(2) + ' €';
        const rowTotal = parseFloat(row.cells[3].textContent);
        // Update the total and the items count
        cartTotal.textContent = (parseFloat(cartTotal.textContent) + unitPrice).toFixed(2);
        totalQuantity++;
        cartTotalQuantity.textContent = totalQuantity;

        if (totalQuantity >= maxQuantity) {
            const increaseButtons = document.querySelectorAll('.btnIncrease');
            increaseButtons.forEach(button => {
                button.hidden = true;
            });
            addButton.hidden = true;
            capacityAlert.classList.remove('d-none'); // Hide the capacity alert
        }
        console.log(`Ajout`);
    });

    // Add event listener to the decrease button
    const decreaseButton = row.querySelector('button.btnDecrease');
    decreaseButton.addEventListener('click', () => {
        const currentQty = parseInt(row.cells[1].textContent);
        const newQty = currentQty - 1;
        row.cells[1].textContent = newQty;
        const unitPrice = parseFloat(row.cells[2].textContent);
        const newTotal = parseFloat(row.cells[2].textContent) * newQty;
        row.cells[3].textContent = newTotal.toFixed(2) + ' €';
        const rowTotal = parseFloat(row.cells[3].textContent);
        // Update the total and the items count
        cartTotal.textContent = (parseFloat(cartTotal.textContent) - unitPrice).toFixed(2);
        totalQuantity--;
        cartTotalQuantity.textContent = totalQuantity;

        if (totalQuantity < maxQuantity) {
            const increaseButtons = document.querySelectorAll('.btnIncrease');
            increaseButtons.forEach(button => {
                button.hidden = false;
            });
            addButton.hidden = false;
            capacityAlert.classList.add('d-none'); // Show the capacity alert
        }
        if (newQty === 0) {
            nbItems--;

            // Remove the item and add the "no items" message if no items are left
            cartBody.removeChild(row);
            if (nbItems === 0) {
                cartBody.innerHTML = '<tr><td colspan="6" class="text-muted">Aucune pizza ajoutée.</td></tr>';
            }
        }
    });

});

