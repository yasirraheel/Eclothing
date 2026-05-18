document.addEventListener('DOMContentLoaded', function () {
    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
    const csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : null;

    // Update cart counter
    function updateCartCounter() {
        fetch('/cart/count', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            },
            credentials: 'same-origin'
        })
        .then(response => response.json())
        .then(data => {
            const cartCount = data.count || 0;
            let badge = document.querySelector('.cart-count');
            
            if (cartCount > 0) {
                if (!badge) {
                    badge = document.createElement('span');
                    badge.className = 'cart-count';
                    document.querySelector('.cart-icon').appendChild(badge);
                }
                badge.textContent = cartCount;
            } else if (badge) {
                badge.remove();
            }
        })
        .catch(err => console.error('Failed to update cart counter', err));
    }

    // Show notification
    function showNotification(message) {
        const notification = document.createElement('div');
        notification.textContent = message;
        notification.style.cssText = 'position:fixed;top:80px;right:20px;background:#4CAF50;color:white;padding:15px 20px;border-radius:4px;z-index:9999;box-shadow:0 4px 12px rgba(0,0,0,0.15);';
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.style.opacity = '0';
            notification.style.transition = 'opacity 0.3s';
            setTimeout(() => notification.remove(), 300);
        }, 2000);
    }

    function handleAddToCartForm(form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            const action = form.getAttribute('action');
            const data = new FormData(form);

            fetch(action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: data,
                credentials: 'same-origin'
            }).then(response => {
                if (response.ok) return response.json();
                throw new Error('Network response was not ok');
            }).then(data => {
                // Update cart counter
                updateCartCounter();
                
                // Show success message
                showNotification('Product added to cart!');
                
                // Optional: reload after short delay to show updated cart
                setTimeout(() => {
                    window.location.reload();
                }, 500);
            }).catch(err => {
                console.error('Add to cart failed', err);
                alert('Could not add to cart. Please try again.');
            });
        });
    }

    document.querySelectorAll('form[action*="/cart/add"]').forEach(form => {
        handleAddToCartForm(form);
    });
});
