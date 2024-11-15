function getCartData() {
    const cartData = localStorage.getItem('cartData');
    return cartData ? JSON.parse(cartData) : null;
}

function renderOrderItems(items) {
    const checkoutItems = document.getElementById('checkout-items');
    checkoutItems.innerHTML = items.map(item => `
        <div class="checkout-item">
            <img src="${item.image}" alt="${item.title}" class="checkout-item-image">
            <div class="checkout-item-details">
                <h4>${item.title}</h4>
                <p class="checkout-item-brand">${item.brand}</p>
                <p class="checkout-item-price">R$ ${item.price.toFixed(2)} x ${item.quantity}</p>
            </div>
            <div class="checkout-item-total">
                R$ ${(item.price * item.quantity).toFixed(2)}
            </div>
        </div>
    `).join('');

    const total = items.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    document.getElementById('checkout-total').textContent = `R$ ${total.toFixed(2)}`;
}

function handleCheckoutSubmit(event) {
    event.preventDefault();
    
    const cartData = getCartData();
    if (!cartData || !cartData.items || cartData.items.length === 0) {
        alert('Carrinho vazio!');
        return;
    }

    const email = document.getElementById('email').value;
    if (!email) {
        alert('Por favor, insira seu email');
        return;
    }

    
    const quantidade = cartData.items.reduce((total, item) => total + item.quantity, 0);
    
    
    const precoTotal = cartData.total;

    const pedidoData = {
        email_cliente: email,
        quantidade: quantidade,
        preco_total: precoTotal
    };

   
    console.log('Dados do pedido:', pedidoData);
    
  
    localStorage.removeItem('cartData');
    
    alert('Pedido realizado com sucesso!');
    window.location.href = 'tela.html';
}

document.addEventListener('DOMContentLoaded', () => {
    const cartData = getCartData();
    
    if (!cartData || !cartData.items || cartData.items.length === 0) {
        window.location.href = 'tela.html';
        return;
    }
    
    renderOrderItems(cartData.items);
    
    document.getElementById('checkout-form').addEventListener('submit', handleCheckoutSubmit);
});