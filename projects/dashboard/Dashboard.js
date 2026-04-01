let currentSection = 'home';
let isTransitioning = false;

const sectionColors = {
    home: '#40a99b',
    items: '#45ae9f',
    order: '#4bb3a4',
    pricelist: '#369b8e',
    about: '#5fc3b4'
};

function showSection(sectionId, event) {
    if (sectionId === currentSection || isTransitioning) return;

    const current = document.getElementById(currentSection);
    const next = document.getElementById(sectionId);
    if (!next || !current) return;

    isTransitioning = true;

    document.querySelectorAll('.navbar nav a[data-section]')
        .forEach(link => link.classList.remove('active'));

    const activeLink = event?.currentTarget?.dataset?.section
        ? event.currentTarget
        : document.querySelector(`.navbar nav a[data-section="${sectionId}"]`);

    if (activeLink) activeLink.classList.add('active');

    const nextColor = sectionColors[sectionId] || '#40a99b';
    document.body.style.backgroundColor = nextColor;
    document.querySelector('.navbar').style.backgroundColor = nextColor;

    next.classList.remove('active');
    next.style.transition = 'none';
    next.style.transform = 'translateX(100%) scale(0.95)';
    next.style.opacity = '0';

    void next.offsetWidth;

    current.style.transform = 'translateX(-100%) scale(0.95)';
    current.style.opacity = '0';

    next.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
    next.classList.add('active');
    next.style.transform = 'translateX(0) scale(1)';
    next.style.opacity = '1';

    setTimeout(() => {
        current.classList.remove('active');
        current.style.transform = 'translateX(100%) scale(0.95)';
        current.style.opacity = '0';

        currentSection = sectionId;
        isTransitioning = false;
    }, 500);
}

// ✅ CHECKOUT LOGIC (SEPARATE)

const modal = document.getElementById("checkout-modal");
const span = document.querySelector(".close-btn");
const paymentSelect = document.getElementById("payment");

// Optional QR container (if you add one later)
const qrContainer = document.getElementById("qr-container");



span.onclick = () => {
    modal.style.display = "none";
};

window.onclick = (event) => {
    if (event.target == modal) {
        modal.style.display = "none";
    }
};

// Optional: QR logic
if (paymentSelect) {
    paymentSelect.onchange = () => {
        const value = paymentSelect.value;

        if (qrContainer) {
            qrContainer.style.display =
                (value === "GCash" || value === "Maya") ? "block" : "none";
        }
    };
}

function openCheckout() {
    const modal = document.getElementById("checkout-modal");

    if (orders.length === 0) {
        alert("No items in your order!");
        return;
    }

    modal.style.display = "flex";
}

function closeCheckout() {
    document.getElementById("checkout-modal").style.display = "none";
}

function confirmCheckout() {
    let address = document.getElementById("address").value;
    let payment = document.getElementById("payment").value;

    if (address === "" || payment === "") {
        alert("Please fill out all fields!");
        return;
    }

    alert("Order placed successfully!\n\nAddress: " + address + "\nPayment: " + payment);

    // reset cart
    orders = [];
    showOrders();

    closeCheckout();
}