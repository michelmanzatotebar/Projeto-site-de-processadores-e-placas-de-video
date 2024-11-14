let cart = {
    items: [],
    total: 0
};


function openCart() {
    const cartSidebar = document.getElementById('cartSidebar');
    cartSidebar.style.right = '0';
    updateCartDisplay();
}


function closeCart() {
    const cartSidebar = document.getElementById('cartSidebar');
    cartSidebar.style.right = '-400px';
}


function addToCart(product) {
   
    const productId = `${product.category}-${product.brand}-${product.title}`.toLowerCase().replace(/\s+/g, '-');
    
    const existingItem = cart.items.find(item => item.id === productId);
    
    if (existingItem) {
        existingItem.quantity += 1;
    } else {
        cart.items.push({
            ...product,
            id: productId,
            quantity: 1
        });
    }
    
    updateCartTotal();
    updateCartDisplay();
    updateCartCount();
}


function removeFromCart(productId) {
    cart.items = cart.items.filter(item => item.id !== productId);
    updateCartTotal();
    updateCartDisplay();
    updateCartCount();
}


function updateCartTotal() {
    cart.total = cart.items.reduce((total, item) => {
        return total + (item.price * item.quantity);
    }, 0);
}


function updateCartCount() {
    const cartCount = document.querySelector('.cart-count');
    const itemCount = cart.items.reduce((total, item) => total + item.quantity, 0);
    cartCount.textContent = itemCount;
}


function updateCartDisplay() {
    const cartItems = document.getElementById('cartItems');
    const cartTotal = document.getElementById('cartTotal');
    
    cartItems.innerHTML = cart.items.map(item => `
        <div class="cart-item">
            <img src="${item.image}" alt="${item.title}" class="cart-item-image">
            <div class="cart-item-details">
                <h4>${item.title}</h4>
                <p class="cart-item-brand">${item.brand}</p>
                <p class="cart-item-category">${item.category}</p>
                <p>R$ ${item.price.toFixed(2)}</p>
                <div class="quantity">
                    <button onclick="updateQuantity('${item.id}', ${item.quantity - 1})">-</button>
                    <span>${item.quantity}</span>
                    <button onclick="updateQuantity('${item.id}', ${item.quantity + 1})">+</button>
                </div>
            </div>
            <button onclick="removeFromCart('${item.id}')" class="remove-item">×</button>
        </div>
    `).join('');
    
    cartTotal.textContent = `R$ ${cart.total.toFixed(2)}`;
}


function updateQuantity(productId, newQuantity) {
    if (newQuantity < 1) {
        removeFromCart(productId);
        return;
    }
    
    const item = cart.items.find(item => item.id === productId);
    if (item) {
        item.quantity = newQuantity;
        updateCartTotal();
        updateCartDisplay();
        updateCartCount();
    }
}


function proceedToCheckout() {
    if (cart.items.length === 0) {
        alert('Seu carrinho está vazio!');
        return;
    }
    
    localStorage.setItem('cartData', JSON.stringify({
        items: cart.items,
        total: cart.total
    }));
    
    window.location.href = 'tela2.html';
}


document.addEventListener('DOMContentLoaded', () => {
    document.querySelector('.cart-icon').addEventListener('click', openCart);
    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', (e) => {
            const productCard = e.target.closest('.product-card');
            const product = {
                category: productCard.querySelector('.product-category').textContent.trim(),
                brand: productCard.querySelector('.product-brand').textContent.trim(),
                title: productCard.querySelector('.product-title').textContent.trim(),
                price: parseFloat(productCard.querySelector('.product-price').textContent.replace('R$ ', '').replace('.', '').replace(',', '.')),
                image: productCard.querySelector('.product-image').src
            };
            addToCart(product);
        });
    });
});