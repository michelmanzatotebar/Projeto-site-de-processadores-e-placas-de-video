
function filterProducts() {
    const selectedCategory = document.getElementById('categoryFilter').value;
    const selectedBrand = document.getElementById('brandFilter').value;
    
    const products = document.querySelectorAll('.product-card');
    let visibleCount = 0;
    
    products.forEach(product => {
        const category = product.querySelector('.product-category').textContent.trim();
        const brand = product.querySelector('.product-brand').textContent.trim();
        
        
        const matchesCategory = selectedCategory === 'all' || category === selectedCategory;
        const matchesBrand = selectedBrand === 'all' || brand === selectedBrand;
        
        
        if (matchesCategory && matchesBrand) {
            product.style.display = '';  
            visibleCount++;
        } else {
            product.style.display = 'none';
        }
    });
    
    let noResultsMessage = document.querySelector('.no-results');
    
    if (visibleCount === 0 && selectedCategory !== 'all' || selectedBrand !== 'all') {
    
        if (!noResultsMessage) {
            noResultsMessage = document.createElement('div');
            noResultsMessage.className = 'no-results';
            noResultsMessage.textContent = 'Nenhum produto encontrado com os filtros selecionados.';
            document.querySelector('.products-grid').appendChild(noResultsMessage);
        }
    } else {
        
        if (noResultsMessage) {
            noResultsMessage.remove();
        }
    }
}

function resetFilters() {
    document.getElementById('categoryFilter').value = 'all';
    document.getElementById('brandFilter').value = 'all';
    filterProducts();
}

document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('categoryFilter').addEventListener('change', filterProducts);
    document.getElementById('brandFilter').addEventListener('change', filterProducts);
    

    const filterSection = document.querySelector('.filters');
    const resetButton = document.createElement('button');
    resetButton.textContent = 'Limpar Filtros';
    resetButton.className = 'reset-filters';
    resetButton.onclick = resetFilters;
    filterSection.appendChild(resetButton);
});