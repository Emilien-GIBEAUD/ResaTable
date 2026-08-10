// Pizzas cart management in the booking form on the page /pizzas
const addButton = document.getElementById('addPizza');
const bookingButton = document.getElementById('addBooking');
const pizza = document.getElementById('pizza');
const quantity = document.getElementById('quantity');
const cartBody = document.getElementById('cartBody');
const noItems = document.getElementById('noItems');
const capacityAlert = document.getElementById('capacityAlert');
const capacityExceededAlert = document.getElementById('capacityExceededAlert');
let nbItems = 0;
let cartTotal = document.getElementById('cartTotal');
let totalQuantity = 0;
let cartTotalQuantity = document.getElementById('cartTotalQuantity');
let maxQuantity = 0; // To be replaced with the booking slot max quantity

const serviceSelect = document.getElementById("serviceDate");
const slotSelect = document.getElementById("serviceSlot");

serviceSelect.addEventListener("change", async function () {
    const response = await fetch(`/slot/serviceSlots/${this.value}/json`);
    const slots = await response.json();

    slotSelect.innerHTML = "";
    slots.forEach(slot => {
        const availableCapacity = slot.availableCapacity;
        if (availableCapacity > 0) {
            const option = document.createElement("option");
            option.value = slot.id;
            option.dataset.availableCapacity = availableCapacity;
            option.textContent = `${slot.startTime} - ${slot.endTime} (${availableCapacity} ${availableCapacity > 1 ? 'pizzas restantes' : 'place restante'})`;
            slotSelect.appendChild(option);
        }
    });

    slotSelect.dispatchEvent(new Event("change"));
});

slotSelect.addEventListener("change", function () {
    const selectedOption = this.options[this.selectedIndex];
    maxQuantity = parseInt(selectedOption.dataset.availableCapacity);
    updateCapacityStatus();
    // Adapt the cart if the total quantity exceeds the new maxQuantity
    if (totalQuantity > maxQuantity) {
        alert(`La capacité du créneau selectionné est dépassée, veuillez ajuster votre panier ou bien sélectionner un autre créneau pour pouvoir placer votre réservation.`);
    }
});

serviceSelect.dispatchEvent(new Event("change"));

addButton.addEventListener('click', () => {
    const option = pizza.options[pizza.selectedIndex];
    const name = option.dataset.name;
    const price = parseFloat(option.dataset.price);
    let qty = parseInt(quantity.value);
    let totalToAdd = price * qty;
    const row = document.createElement('tr');

    // Check if the pizza is already in the cart and decrease qty if necessay
    const rows = cartBody.querySelectorAll('tr');
    for (const existingRow of rows) {
        if (existingRow.cells[0].textContent === name) {    // Check availabilty and update qty
            console.log("Ajout d'un item existant");
            if (totalQuantity + qty > maxQuantity) {
                qty = maxQuantity - totalQuantity;
                alert(`La quantité demandée dépasse la capacité disponible pour ce créneau horaire. La quantité a été ajustée à ${qty}.`);
            }
            for (let i = 0; i < qty; i++) {     // add qty
                increaseItem(existingRow);
            }
            return; // Exit the addButton listener
        }
    }

    // Check if qty is available, if not => update qty and totalToAdd
    if (totalQuantity + qty > maxQuantity) {
        qty = maxQuantity - totalQuantity;
        totalToAdd = price * qty;
        alert(`La quantité demandée dépasse la capacité disponible pour ce créneau horaire. La quantité a été ajustée à ${qty}.`);
    }

    row.innerHTML = `
        <td>${name}</td>
        <td class="quantity">${qty}</td>
        <td class="price">${price.toFixed(2)} €</td>
        <td class="total">${totalToAdd.toFixed(2)} €</td>
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

    cartBody.appendChild(row);

    // Update the total and the items count
    cartTotal.textContent = (parseFloat(cartTotal.textContent) + totalToAdd).toFixed(2);
    nbItems++;
    totalQuantity += qty;
    cartTotalQuantity.textContent = totalQuantity;

    updateCapacityStatus();
    updateCartStatus(1,row);

    // Add event listener to the remove button
    const removeButton = row.querySelector('button.btnRemove');
    removeButton.addEventListener('click', () => removeItem(row));

    // Add event listener to the increase button
    const increaseButton = row.querySelector('button.btnIncrease');
    increaseButton.addEventListener('click', () => increaseItem(row));

    // Add event listener to the decrease button
    const decreaseButton = row.querySelector('button.btnDecrease');
    decreaseButton.addEventListener('click', () => decreaseItem(row));

});

function removeItem(row) {
    const rowTotal = parseFloat(row.querySelector('.total').textContent);
    // Update the total and the items count
    cartTotal.textContent = (parseFloat(cartTotal.textContent) - rowTotal).toFixed(2);
    totalQuantity -= parseInt(row.querySelector('.quantity').textContent);
    cartTotalQuantity.textContent = totalQuantity;

    updateCapacityStatus()
    updateCartStatus(0, row)
}

function increaseItem(row) {
    const currentQty = parseInt(row.querySelector('.quantity').textContent);
    const newQty = currentQty + 1;
    row.querySelector('.quantity').textContent = newQty;
    const unitPrice = parseFloat(row.querySelector('.price').textContent);
    const newTotal = parseFloat(row.querySelector('.price').textContent) * newQty;
    row.querySelector('.total').textContent = newTotal.toFixed(2) + ' €';
    // Update the total and the items count
    cartTotal.textContent = (parseFloat(cartTotal.textContent) + unitPrice).toFixed(2);
    totalQuantity++;
    cartTotalQuantity.textContent = totalQuantity;

    updateCapacityStatus();
}

function decreaseItem(row) {
    const currentQty = parseInt(row.querySelector('.quantity').textContent);
    const newQty = currentQty - 1;
    row.querySelector('.quantity').textContent = newQty;
    const unitPrice = parseFloat(row.querySelector('.price').textContent);
    const newTotal = parseFloat(row.querySelector('.price').textContent) * newQty;
    row.querySelector('.total').textContent = newTotal.toFixed(2) + ' €';
    // Update the total and the items count
    cartTotal.textContent = (parseFloat(cartTotal.textContent) - unitPrice).toFixed(2);
    totalQuantity--;
    cartTotalQuantity.textContent = totalQuantity;

    updateCapacityStatus();
    updateCartStatus(newQty,row);
}

function updateCapacityStatus() {
    // Default state
    bookingButton.hidden = false;
    addButton.hidden = false;
    capacityAlert.classList.add('d-none');
    capacityExceededAlert.classList.add('d-none');

    const increaseButtons = document.querySelectorAll('.btnIncrease');
    increaseButtons.forEach(button => {
        button.hidden = false;
    });
    
    if (totalQuantity === maxQuantity) {
        increaseButtons.forEach(button => {
            button.hidden = true;
        });
        addButton.hidden = true;
        capacityAlert.classList.remove('d-none');
    }

    if (totalQuantity > maxQuantity) {
        increaseButtons.forEach(button => {
            button.hidden = true;
        });
        addButton.hidden = true;
        bookingButton.hidden = true;
        capacityAlert.classList.add('d-none');
        capacityExceededAlert.classList.remove('d-none');
    }
}

function updateCartStatus(newQty, row) {
    if (newQty === 0) {
        nbItems--;

        // Remove the item and add the "no items" message if no items are left
        cartBody.removeChild(row);
        if (nbItems === 0) {
            // cartBody.innerHTML = '<tr><td colspan="6" class="text-muted">Aucune pizza ajoutée.</td></tr>';
            noItems.classList.remove("d-none")
        }
    }
    if (nbItems > 0) {
        noItems.classList.add("d-none");
    }
}

