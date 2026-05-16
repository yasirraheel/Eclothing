document.addEventListener('DOMContentLoaded', function () {
    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
    const csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : null;

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
                if (response.ok) return response.text();
                throw new Error('Network response was not ok');
            }).then(() => {
                // Simple user feedback: reload so cart count updates
                // You can replace this with a nicer toast or incremental update
                window.location.reload();
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
