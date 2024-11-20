//const API_BASE_URL = 'http://localhost:8000';

let order = {
    items: [],
    total: 0,
    email: ''
};

function validateEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

function handleEmailConfirmation() {
    const emailInput = document.getElementById('email');
    const emailStatus = document.getElementById('email-status');
    const confirmOrderButton = document.querySelector('.confirm-order-button');
    const email = emailInput.value.trim();

    if (!email) {
        emailStatus.textContent = 'Por favor, insira um email.';
        emailStatus.className = 'email-status error';
        return;
    }

    if (!validateEmail(email)) {
        emailStatus.textContent = 'Por favor, insira um email válido.';
        emailStatus.className = 'email-status error';
        return;
    }

    order.email = email;
    
    emailStatus.textContent = 'Email confirmado com sucesso!';
    emailStatus.className = 'email-status success';
    
    if (confirmOrderButton) {
        confirmOrderButton.disabled = false;
    }

    console.log('Email salvo no order:', order.email);
}

function loadOrder() {
    try {
        const savedCheckout = localStorage.getItem('checkoutData');
        if (savedCheckout) {
            const parsedCheckout = JSON.parse(savedCheckout);
            
            if (parsedCheckout && Array.isArray(parsedCheckout.items)) {
                order.items = parsedCheckout.items;
                order.total = parsedCheckout.total || 0;
            } else {
                order.items = [];
                order.total = 0;
            }
        }
        console.log('Pedido carregado:', order);
        updateOrderDisplay();
    } catch (error) {
        console.error('Erro ao carregar pedido:', error);
        order.items = [];
        order.total = 0;
    }
}

function updateOrderDisplay() {
    const checkoutItems = document.getElementById('checkout-items');
    const checkoutTotal = document.getElementById('checkout-total');
    
    if (!checkoutItems || !checkoutTotal) {
        console.error('Elementos do checkout não encontrados');
        return;
    }
    
    if (order.items.length === 0) {
        checkoutItems.innerHTML = '<p class="empty-order">Nenhum item no pedido</p>';
        checkoutTotal.textContent = 'R$ 0,00';
        window.location.href = 'tela.html';
        return;
    }
    
    checkoutItems.innerHTML = order.items.map(item => `
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
    
    checkoutTotal.textContent = `R$ ${order.total.toFixed(2)}`;
}

async function handleCheckoutSubmit(event) {
    if (event) {
        event.preventDefault();
    }
    
    if (!order.email) {
        alert('Por favor, confirme seu email antes de prosseguir');
        return;
    }
    
    if (order.items.length === 0) {
        alert('Seu pedido está vazio!');
        return;
    }
    
    try {
        const quantidade = order.items.reduce((total, item) => total + item.quantity, 0);
        
        const pedidoData = {
            id: Date.now(),
            email_cliente: order.email,
            quantidade: quantidade,
            preco_total: order.total,
            data_criacao: new Date().toISOString(),
            items: order.items 
        };

        const localOrders = JSON.parse(localStorage.getItem('localOrders') || '[]');
        localOrders.push(pedidoData);
        localStorage.setItem('localOrders', JSON.stringify(localOrders));

        console.log('Enviando pedido:', pedidoData);
        console.log('URL da requisição:', 'http://localhost:8000/Pedido');

        const response = await fetch('http://localhost:8000/Pedido', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(pedidoData),
        });

        if (response.ok) {
            localStorage.removeItem('cartData');
            
            Swal.fire({
                title: "Enviado!",
                text: "Seu pedido foi enviado com sucesso.",
                icon: "success"
            });
            setTimeout(() => {
                window.location.href = "tela.html";
            }, 2000);
        } else {
            Swal.fire({
                title: "Erro!",
                text: "Seu pedido não foi enviado com sucesso.",
                icon: "error"
            });
            throw new Error(responseData.error || 'Erro ao criar pedido');
        }
        
    } catch (error) {
        console.error('Erro ao finalizar pedido:', error);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    loadOrder();

    const confirmEmailButton = document.getElementById('confirm-email');
    if (confirmEmailButton) {
        confirmEmailButton.addEventListener('click', handleEmailConfirmation);
    }

    const emailInput = document.getElementById('email');
    if (emailInput) {
        emailInput.addEventListener('input', () => {
            const confirmOrderButton = document.querySelector('.confirm-order-button');
            if (confirmOrderButton) {
                confirmOrderButton.disabled = true;
            }
            const emailStatus = document.getElementById('email-status');
            if (emailStatus) {
                emailStatus.textContent = '';
            }
            order.email = '';
        });
    }

    const confirmOrderButton = document.querySelector('.confirm-order-button');
    if (confirmOrderButton) {
        confirmOrderButton.addEventListener('click', handleCheckoutSubmit);
    
        confirmOrderButton.disabled = true;
    }

    const backButton = document.querySelector('.back-button');
    if (backButton) {
        backButton.addEventListener('click', () => {
            window.location.href = 'tela.html';
        });
    }
});