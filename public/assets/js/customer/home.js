        let cart = [];
        let notiDropdownOpen = false;

        async function fetchNotifications() {
            try {
                const res = await fetch(APP_URL + '/notifications/get');
                const result = await res.json();
                if (result.success) {
                    const badge = document.getElementById('notiBadge');
                    if (result.unreadCount > 0) {
                        badge.innerText = result.unreadCount;
                        badge.style.display = 'block';
                    } else {
                        badge.style.display = 'none';
                    }

                    const list = document.getElementById('notiList');
                    if (result.notifications.length === 0) {
                        list.innerHTML = '<p style="text-align:center; color:var(--text-dim); padding: 2rem;">No notifications yet.</p>';
                        return;
                    }

                    list.innerHTML = result.notifications.map(n => `
                        <div class="noti-item ${n.is_read == 0 ? 'unread' : ''}" onclick="markNotiRead(${n.id}, event)">
                            <div class="noti-icon">
                                <i class="fas fa-${n.type === 'order' ? 'shopping-bag' : (n.type === 'system' ? 'gift' : 'bell')}"></i>
                            </div>
                            <div class="noti-content">
                                <p class="noti-title">${n.title}</p>
                                <p class="noti-msg">${n.message}</p>
                                <span class="noti-time">${new Date(n.created_at).toLocaleString()}</span>
                            </div>
                        </div>
                    `).join('');
                }
            } catch (e) {
                console.error("Failed to fetch notifications", e);
            }
        }

        function toggleNotiDropdown() {
            notiDropdownOpen = !notiDropdownOpen;
            const dropdown = document.getElementById('notiDropdown');
            if (notiDropdownOpen) {
                dropdown.classList.add('show');
                fetchNotifications();
            } else {
                dropdown.classList.remove('show');
            }
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function (e) {
            const container = document.querySelector('.noti-dropdown-container');
            if (container && !container.contains(e.target) && notiDropdownOpen) {
                toggleNotiDropdown();
            }
        });

        async function markNotiRead(id, e) {
            if (e) e.stopPropagation();
            try {
                const res = await fetch(APP_URL + '/notifications/read', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id: id })
                });
                const result = await res.json();
                if (result.success) {
                    fetchNotifications(); // refresh list
                }
            } catch (e) { console.error(e); }
        }

        async function markAllNotiRead(e) {
            if (e) e.stopPropagation();
            try {
                const res = await fetch(APP_URL + '/notifications/read', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id: 'all' })
                });
                const result = await res.json();
                if (result.success) {
                    fetchNotifications();
                }
            } catch (e) { console.error(e); }
        }

        // Initial fetch
        document.addEventListener('DOMContentLoaded', fetchNotifications);

        function addToCart(id, name, price, points) {
            if (!IS_LOGGED_IN) {
                alert('Please login to order items.');
                window.location.href = APP_URL + '/login';
                return;
            }

            const existing = cart.find(item => item.id === id);
            if (existing) {
                existing.quantity++;
            } else {
                cart.push({ id, name, price, points, quantity: 1 });
            }
            updateCartUI();

            // Notification effect
            const badge = document.querySelector('.cart-icon .badge');
            badge.style.transform = 'scale(1.5)';
            setTimeout(() => badge.style.transform = 'scale(1)', 200);
        }

        function updateCartUI() {
            const count = cart.reduce((acc, item) => acc + item.quantity, 0);
            const _badge = document.getElementById('cartBadge');
            if (count > 0) {
                _badge.innerText = count;
                _badge.style.display = 'block';
            } else {
                _badge.style.display = 'none';
            }

            const list = document.getElementById('cartItemsList');
            let html = '';
            let totalAmount = 0;
            let totalPoints = 0;

            cart.forEach((item, index) => {
                const subtotal = item.price * item.quantity;
                const points = item.points * item.quantity;
                totalAmount += subtotal;
                totalPoints += points;

                html += `
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.2rem;">
                        <div style="flex: 1;">
                            <h4 style="margin:0; font-size: 1rem;">${item.name}</h4>
                            <small style="color: var(--text-dim);">${item.price.toLocaleString()} Ks</small>
                        </div>
                        
                        <div style="display: flex; align-items: center; gap: 0.8rem; margin: 0 1rem;">
                            <button class="qty-btn" onclick="updateQuantity(${index}, -1)">
                                <i class="fas fa-minus" style="font-size: 0.8rem;"></i>
                            </button>
                            <span class="qty-display">${item.quantity}</span>
                            <button class="qty-btn" onclick="updateQuantity(${index}, 1)">
                                <i class="fas fa-plus" style="font-size: 0.8rem;"></i>
                            </button>
                        </div>

                        <div style="text-align: right; min-width: 80px;">
                            <div style="font-weight: 700; color: var(--primary);">${subtotal.toLocaleString()} Ks</div>
                            <small style="color: #2ecc71;">+${points} Pts</small>
                        </div>
                        <button onclick="removeFromCart(${index})" style="background:none; border:none; color:#e74c3c; cursor:pointer; margin-left:15px; font-size: 1.1rem;">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                `;
            });

            list.innerHTML = html || '<p style="text-align:center; color:var(--text-dim);">Your cart is empty.</p>';
            if(document.getElementById('totalAmount')) document.getElementById('totalAmount').innerText = totalAmount.toLocaleString() + ' Ks';
            if(document.getElementById('totalPoints')) document.getElementById('totalPoints').innerText = totalPoints + ' Pts';
        }

        function removeFromCart(index) {
            cart.splice(index, 1);
            updateCartUI();
        }

        function updateQuantity(index, change) {
            if (cart[index].quantity + change > 0) {
                cart[index].quantity += change;
            } else {
                removeFromCart(index);
            }
            updateCartUI();
        }

        function toggleCart() {
            const modal = document.getElementById('cartModal');
            if(modal) modal.style.display = (modal.style.display === 'flex') ? 'none' : 'flex';
        }

        if(document.querySelector('.cart-icon')) {
            document.querySelector('.cart-icon').onclick = toggleCart;
        }

        let selectedPaymentMethod = 'Cash';

        function selectPaymentMethod(method) {
            selectedPaymentMethod = method;
            document.querySelectorAll('.method-btn').forEach(btn => {
                if (btn.innerText === method) {
                    btn.classList.add('active');
                } else {
                    btn.classList.remove('active');
                }
            });
        }

        function showPaymentModal() {
            if (cart.length === 0) return;

            const totalAmount = cart.reduce((acc, item) => acc + (item.price * item.quantity), 0);

            // Build receipt items list
            const receiptItemsHtml = cart.map(item => `
                <tr>
                    <td>${item.name}</td>
                    <td class="center">x${item.quantity}</td>
                    <td class="right">${(item.price * item.quantity).toLocaleString()} KS</td>
                </tr>
            `).join('');

            document.getElementById('receiptItems').innerHTML = receiptItemsHtml;
            document.getElementById('receiptSubtotal').innerText = totalAmount.toLocaleString() + ' KS';
            document.getElementById('receiptTotalPayable').innerText = totalAmount.toLocaleString() + ' KS';

            // Set current date and time
            const now = new Date();
            const dateStr = now.toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' }).replace(/ /g, ' ');
            const timeStr = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
            document.getElementById('receiptDate').innerText = `Date: ${dateStr}, ${timeStr}`;

            document.getElementById('cartModal').style.display = 'none';
            document.getElementById('paymentModal').style.display = 'flex';
        }

        function goBackToCart() {
            document.getElementById('paymentModal').style.display = 'none';
            document.getElementById('cartModal').style.display = 'flex';
        }

        async function processPayment() {
            if (cart.length === 0) return;

            const btn = document.getElementById('confirmPaymentBtn');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';

            const totalPrice = cart.reduce((acc, item) => acc + (item.price * item.quantity), 0);
            const totalPoints = cart.reduce((acc, item) => acc + (item.points * item.quantity), 0);

            try {
                const response = await fetch(APP_URL + '/checkout', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        items: cart,
                        totalPrice: totalPrice,
                        totalPoints: totalPoints
                    })
                });

                const result = await response.json();
                if (result.success) {
                    document.getElementById('paymentModal').style.display = 'none';
                    document.getElementById('successModal').style.display = 'flex';

                    fetchNotifications(); // Fetch latest notifications from DB

                    if (result.new_points !== undefined) {
                        const pointDisplay = document.querySelector('.show_point p');
                        if (pointDisplay) {
                            pointDisplay.innerText = result.new_points + ' Points';
                        }
                    }

                    cart = [];
                    updateCartUI();
                } else {
                    alert(result.message);
                }
            } catch (error) {
                alert('An error occurred. Please try again.');
            } finally {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-credit-card"></i> Pay Now';
            }
        }
        // Category Filter Logic
        document.addEventListener('DOMContentLoaded', () => {
            const filterBtns = document.querySelectorAll('.filter-btn');
            const cards = document.querySelectorAll('.product-card');

            function showDefault() {
                let categoryCounts = {};
                cards.forEach(card => {
                    const cat = card.getAttribute('data-category');
                    categoryCounts[cat] = (categoryCounts[cat] || 0) + 1;

                    if (categoryCounts[cat] <= 6) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            }

            // Initialize default view
            showDefault();

            filterBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    // Update active state
                    filterBtns.forEach(b => b.classList.remove('active'));
                    btn.classList.add('active');

                    const filter = btn.getAttribute('data-filter');

                    if (filter === 'all') {
                        showDefault();
                    } else {
                        cards.forEach(card => {
                            if (card.getAttribute('data-category') === filter) {
                                card.style.display = 'block';
                            } else {
                                card.style.display = 'none';
                            }
                        });
                    }
                });
            });
        });
