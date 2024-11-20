
let cart = {
    items: [],
    total: 0
};


function loadCart() {
    try {
        const savedCart = localStorage.getItem('cartData');
        if (savedCart) {
            const parsedCart = JSON.parse(savedCart);
           
            if (parsedCart && Array.isArray(parsedCart.items)) {
                cart.items = parsedCart.items;
                cart.total = parsedCart.total || 0;
            } else {
               
                cart.items = [];
                cart.total = 0;
            }
        }
        console.log('Carrinho carregado:', cart);
        updateCartDisplay();
        updateCartCount();
    } catch (error) {
        console.error('Erro ao carregar carrinho:', error);
        
        cart.items = [];
        cart.total = 0;
        localStorage.removeItem('cartData');
    }
}

function saveCart() {
    try {
        localStorage.setItem('cartData', JSON.stringify(cart));
        console.log('Carrinho salvo:', cart);
    } catch (error) {
        console.error('Erro ao salvar carrinho:', error);
    }
}

function clearCart() {
    cart.items = [];
    cart.total = 0;
    saveCart();
    updateCartDisplay();
    updateCartCount();
    console.log('Carrinho limpo');
}

function openCart() {
    const cartSidebar = document.getElementById('cartSidebar');
    if (cartSidebar) {
        cartSidebar.style.right = '0';
        updateCartDisplay();
    }
}

function closeCart() {
    const cartSidebar = document.getElementById('cartSidebar');
    if (cartSidebar) {
        cartSidebar.style.right = '-400px';
    }
}

function updateCartTotal() {
    cart.total = cart.items.reduce((total, item) => {
        return total + (item.price * item.quantity);
    }, 0);
    console.log('Total atualizado:', cart.total);
}

function updateCartCount() {
    const cartCount = document.querySelector('.cart-count');
    if (cartCount) {
        const itemCount = cart.items.reduce((total, item) => total + item.quantity, 0);
        cartCount.textContent = itemCount;
        console.log('Contador atualizado:', itemCount);
    }
}

function updateCartDisplay() {
    const cartItems = document.getElementById('cartItems');
    const cartTotal = document.getElementById('cartTotal');
    
    if (!cartItems || !cartTotal) {
        console.error('Elementos do carrinho não encontrados');
        return;
    }
    
    if (cart.items.length === 0) {
        cartItems.innerHTML = '<p class="empty-cart">Seu carrinho está vazio</p>';
        cartTotal.textContent = 'R$ 0,00';
        return;
    }
    
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
    console.log('Display atualizado', { items: cart.items.length, total: cart.total });
}

async function addToCart(product) {
    console.log('Tentando adicionar produto:', product);
    
    if (!product || !product.id) {
        console.error('Produto inválido:', product);
        return;
    }
    
    try {
        const productId = `${product.category}-${product.brand}-${product.title}`
            .toLowerCase()
            .replace(/\s+/g, '-');
        
        const existingItem = cart.items.find(item => item.id === productId);
        
        if (existingItem) {
            existingItem.quantity += 1;
            console.log('Quantidade atualizada para item existente:', existingItem);
        } else {
            cart.items.push({
                ...product,
                id: productId,
                quantity: 1
            });
            console.log('Novo item adicionado ao carrinho');
        }
        
       
        updateCartTotal();
        updateCartDisplay();
        updateCartCount();
        saveCart();
        
    } catch (error) {
        console.error('Erro ao adicionar ao carrinho:', error);
        alert('Erro ao adicionar produto ao carrinho. Tente novamente.');
    }
}

async function removeFromCart(productId) {
    console.log('Tentando remover produto:', productId);
    
    try {
        cart.items = cart.items.filter(item => item.id !== productId);
        
        updateCartTotal();
        updateCartDisplay();
        updateCartCount();
        saveCart();
        
        console.log('Produto removido com sucesso');
        
    } catch (error) {
        console.error('Erro ao remover do carrinho:', error);
        alert('Erro ao remover produto do carrinho. Tente novamente.');
    }
}

async function updateQuantity(productId, newQuantity) {
    console.log('Atualizando quantidade:', { productId, newQuantity });
    
    try {
        if (newQuantity < 1) {
            await removeFromCart(productId);
            return;
        }
        
        const item = cart.items.find(item => item.id === productId);
        if (item) {
            item.quantity = newQuantity;
            
            updateCartTotal();
            updateCartDisplay();
            updateCartCount();
            saveCart();
            
            console.log('Quantidade atualizada com sucesso');
        }
    } catch (error) {
        console.error('Erro ao atualizar quantidade:', error);
        alert('Erro ao atualizar quantidade. Tente novamente.');
    }
}

async function proceedToCheckout() {
    if (cart.items.length === 0) {
        alert('Seu carrinho está vazio!');
        return;
    }
    
    try {
        const checkoutData = {
            items: cart.items,
            total: cart.total
        };
        
        localStorage.setItem('checkoutData', JSON.stringify(checkoutData));
        window.location.href = 'tela2.html';
        
    } catch (error) {
        console.error('Erro ao processar checkout:', error);
        alert('Erro ao processar pedido. Tente novamente.');
    }
}

document.addEventListener('DOMContentLoaded', () => {
    console.log('Inicializando carrinho...');
    
    loadCart();

    const cartIcon = document.querySelector('.cart-icon');
    if (cartIcon) {
        cartIcon.addEventListener('click', openCart);
        console.log('Listener do ícone do carrinho configurado');
    }
    
    const closeCartButton = document.querySelector('.close-cart');
    if (closeCartButton) {
        closeCartButton.addEventListener('click', closeCart);
        console.log('Listener do botão fechar configurado');
    }

    const checkoutButton = document.querySelector('.checkout-button');
    if (checkoutButton) {
        checkoutButton.addEventListener('click', proceedToCheckout);
        console.log('Listener do botão checkout configurado');
    }
    
    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', (e) => {
            const productCard = e.target.closest('.product-card');
            if (!productCard) {
                console.error('Card do produto não encontrado');
                return;
            }

            try {
                const product = {
                    id: productCard.dataset.productId,
                    category: productCard.querySelector('.product-category').textContent.trim(),
                    brand: productCard.querySelector('.product-brand').textContent.trim(),
                    title: productCard.querySelector('.product-title').textContent.trim(),
                    price: parseFloat(productCard.querySelector('.product-price').textContent
                        .replace('R$ ', '')
                        .replace('.', '')
                        .replace(',', '.')),
                    image: productCard.querySelector('.product-image').src
                };
                
                console.log('Dados do produto coletados:', product);
                addToCart(product);
                
            } catch (error) {
                console.error('Erro ao coletar dados do produto:', error);
            }
        });
    });
    
    console.log('Inicialização concluída');
});