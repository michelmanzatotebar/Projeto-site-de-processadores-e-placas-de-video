//const API_BASE_URL = 'http://localhost:8000';

async function loadOrders() {
    try {
        const localOrders = JSON.parse(localStorage.getItem('localOrders') || '[]');
        console.log('Pedidos encontrados:', localOrders);
        let serverOrders = [];
        try {
            const response = await fetch('http://localhost:8000/Pedido');
            if (response.ok) {
                serverOrders = await response.json();
            }
        } catch (error) {
            console.log('Não foi possível carregar pedidos', error);
        }
        const allOrders = [...localOrders, ...serverOrders];
        displayOrders(allOrders);
        
    } catch (error) {
        console.error('Erro ao carregar pedidos:', error);
        alert('Erro ao carregar seus pedidos. Tente novamente.');
    }
}

async function deleteOrder(orderId) {
    console.log('Tentando deletar pedido:', orderId);
    
    if (!confirm('Tem certeza que deseja cancelar este pedido?')) {
        return;
    }
    
    try {
        const localOrders = JSON.parse(localStorage.getItem('localOrders') || '[]');
    
        const idToCompare = !isNaN(orderId) ? Number(orderId) : orderId;
        
        const localOrder = localOrders.find(order => order.id === idToCompare);
        
        if (localOrder) {
            const updatedOrders = localOrders.filter(order => order.id !== idToCompare);
            localStorage.setItem('localOrders', JSON.stringify(updatedOrders));
            loadOrders();
            return;
        }
        
        const response = await fetch('http://localhost:8000/Pedido/${orderId}', {
            method: 'DELETE'
        });
        
        if (!response.ok) {
            throw new Error('Erro ao excluir pedido');
        }
        
        loadOrders();
        
    } catch (error) {
        console.error('Erro ao excluir pedido:', error);
        alert('Erro ao excluir pedido. Tente novamente.');
    }
}

function displayOrders(orders) {
    const ordersContainer = document.getElementById('orders-container');
    
    if (!ordersContainer) {
        console.error('Container de pedidos não encontrado');
        return;
    }
    
    if (!orders || orders.length === 0) {
        ordersContainer.innerHTML = '<p class="no-orders">Você ainda não tem pedidos.</p>';
        return;
    }
    
    ordersContainer.innerHTML = orders.map(order => `
        <div class="order-card" data-order-id="${order.id}">
            <div class="order-header">
                <h3>Pedido de ${order.email_cliente}</h3>
                <span class="order-date">${new Date(order.data_criacao).toLocaleDateString()}</span>
            </div>
            <div class="order-details">
                <p>Email cadastrado: ${order.email_cliente}</p>
                <p>Quantidade de itens: ${order.quantidade}</p>
                <p>Total: R$ ${Number(order.preco_total).toFixed(2)}</p>
                ${order.items ? `
                    <div class="order-items">
                        <h4>Itens do pedido:</h4>
                        ${order.items.map(item => `
                            <div class="order-item">
                                <img src="${item.image}" alt="${item.title}" class="order-item-image" style="width: 50px; height: 50px; object-fit: cover; margin-right: 10px;">
                                <div class="order-item-info">
                                    <p><strong>${item.title}</strong></p>
                                    <p>Quantidade: ${item.quantity}x</p>
                                    <p>Preço unitário: R$ ${item.price.toFixed(2)}</p>
                                    <p>Total: R$ ${(item.price * item.quantity).toFixed(2)}</p>
                                </div>
                            </div>
                        `).join('')}
                    </div>
                ` : ''}
            </div>
            <div class="order-actions">
                <button onclick="deleteOrder('${order.id}')" class="delete-order">Cancelar Pedido</button>
            </div>
        </div>
    `).join('');
}

document.addEventListener('DOMContentLoaded', () => {
    console.log('Página carregada, iniciando carregamento de pedidos...');
    loadOrders();
});