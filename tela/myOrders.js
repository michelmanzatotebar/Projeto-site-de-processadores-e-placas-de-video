const API_BASE_URL = '/Projeto-site-de-processadores-e-placas-de-video/public';

async function loadOrders() {
    try {
        const response = await fetch(`${API_BASE_URL}/Pedido`);
        if (!response.ok) {
            throw new Error('Erro ao carregar pedidos');
        }
        
        const orders = await response.json();
        displayOrders(orders);
        updateOrdersCount(orders.length);
        
    } catch (error) {
        console.error('Erro ao carregar pedidos:', error);
        alert('Erro ao carregar seus pedidos. Tente novamente.');
    }
}

function displayOrders(orders) {
    const ordersContainer = document.getElementById('orders-container');
    
    if (!ordersContainer) {
        console.error('Container de pedidos não encontrado');
        return;
    }
    
    if (orders.length === 0) {
        ordersContainer.innerHTML = '<p class="no-orders">Você ainda não tem pedidos.</p>';
        return;
    }
    
    ordersContainer.innerHTML = orders.map(order => `
        <div class="order-card" data-order-id="${order.ID}">
            <div class="order-header">
                <h3>Pedido #${order.ID}</h3>
                <span class="order-date">${new Date(order.data_criacao).toLocaleDateString()}</span>
            </div>
            <div class="order-details">
                <p>Quantidade de itens: ${order.quantidade}</p>
                <p>Total: R$ ${order.preco_total.toFixed(2)}</p>
            </div>
            <div class="order-actions">
                <button onclick="deleteOrder(${order.ID})" class="delete-order">Excluir</button>
            </div>
        </div>
    `).join('');
}

async function updateOrdersCount() {
    try {
        const response = await fetch(`${API_BASE_URL}/Pedido`);
        if (!response.ok) {
            throw new Error('Erro ao carregar pedidos');
        }
        
        const orders = await response.json();
        const ordersCount = orders.length;
        
        const countElement = document.querySelector('.orders-count');
        if (countElement) {
            countElement.textContent = ordersCount;
            countElement.style.display = ordersCount > 0 ? 'flex' : 'none';
        }
        
    } catch (error) {
        console.error('Erro ao atualizar contagem de pedidos:', error);
    }
}

async function deleteOrder(orderId) {
    if (!confirm('Tem certeza que deseja excluir este pedido?')) {
        return;
    }
    
    try {
        const response = await fetch(`${API_BASE_URL}/Pedido/${orderId}`, {
            method: 'DELETE'
        });
        
        if (!response.ok) {
            throw new Error('Erro ao excluir pedido');
        }
        
        // Recarregar a lista de pedidos
        loadOrders();
        
    } catch (error) {
        console.error('Erro ao excluir pedido:', error);
        alert('Erro ao excluir pedido. Tente novamente.');
    }
}

// Event Listener para a página de pedidos
document.addEventListener('DOMContentLoaded', () => {
    // Verificar se estamos na página de pedidos
    const ordersContainer = document.getElementById('orders-container');
    if (ordersContainer) {
        loadOrders();
    }
    
    // Atualizar contagem no botão "Meus Pedidos", se existir
    const ordersButton = document.querySelector('.my-orders-btn');
    if (ordersButton) {
        loadOrders();
    }
});

// Atualização periódica opcional
setInterval(() => {
    const ordersContainer = document.getElementById('orders-container');
    if (ordersContainer || document.querySelector('.my-orders-btn')) {
        loadOrders();
    }
}, 60000); // Atualiza a cada minuto