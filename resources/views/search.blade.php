<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotel Booking Search - Zotel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <div class="container mx-auto px-4 py-8 max-w-7xl">
        <!-- Header -->
        <div class="text-center mb-10">
            <h1 class="text-4xl font-bold text-gray-800 mb-2">Zotel Hotel Booking</h1>
            <p class="text-gray-600">Find your perfect stay with our best price guarantee</p>
        </div>
        
        <!-- Search Form -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8 border border-gray-100">
            <form id="searchForm" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-calendar-check mr-2 text-blue-500"></i>Check-in
                    </label>
                    <input type="date" name="check_in" id="check_in" 
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500"
                           required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-calendar-times mr-2 text-blue-500"></i>Check-out
                    </label>
                    <input type="date" name="check_out" id="check_out" 
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500"
                           required>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-users mr-2 text-blue-500"></i>Guests
                    </label>
                    <select name="guests" id="guests" 
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500"
                            required>
                        <option value="1">1 Adult</option>
                        <option value="2" selected>2 Adults</option>
                        <option value="3">3 Adults</option>
                        <option value="4">4 Adults</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-tag mr-2 text-blue-500"></i>Rate Plan
                    </label>
                    <select name="rate_plan" id="rate_plan" 
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500">
                        <option value="">All</option>
                        <option value="EP">EP - Room Only</option>
                        <option value="CP">CP - Breakfast Included</option>
                        <option value="MAP">MAP - All Meals Included</option>
                    </select>
                </div>
                <div class="md:col-span-4">
                    <button type="submit" 
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition duration-200">
                        <i class="fas fa-search mr-2"></i>Search Rooms
                    </button>
                </div>
            </form>
        </div>

        <!-- Loading & Error States -->
        <div id="loading" class="hidden text-center py-12">
            <div class="inline-block animate-spin rounded-full h-12 w-12 border-4 border-blue-600 border-t-transparent"></div>
            <p class="mt-4 text-gray-600">Searching for available rooms...</p>
        </div>

        <div id="error" class="hidden bg-red-50 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded-lg mb-6"></div>

        <!-- Results -->
        <div id="results" class="hidden">
            <div id="roomList" class="space-y-6"></div>
        </div>
    </div>

    <script>
        document.getElementById('searchForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('check_in').min = today;
            
            const formData = {
                check_in: document.getElementById('check_in').value,
                check_out: document.getElementById('check_out').value,
                guests: document.getElementById('guests').value,
                rate_plan: document.getElementById('rate_plan').value
            };

            document.getElementById('loading').classList.remove('hidden');
            document.getElementById('results').classList.add('hidden');
            document.getElementById('error').classList.add('hidden');

            try {
                const params = new URLSearchParams(formData);
                const response = await fetch(`/api/v1/search?${params}`);
                const data = await response.json();

                document.getElementById('loading').classList.add('hidden');

                if (data.success) {
                    displayResults(data.data);
                } else {
                    if(data.status == false){
                        const errors = data.data || {};

                            let message = 
                                errors.check_out || 
                                errors.rate_plan || 
                                errors.guests ||
                                'An error occurred';

                            showError(message);
                    }
                    
                }
            } catch (error) {
                document.getElementById('loading').classList.add('hidden');
                showError('Failed to fetch results. Please try again.');
            }
        });

        function displayResults(data) {
            const roomList = document.getElementById('roomList');
            roomList.innerHTML = '';
            
            if (data.available_rooms.length === 0) {
                roomList.innerHTML = `
                    <div class="text-center py-12 bg-white rounded-lg shadow">
                        <i class="fas fa-bed text-gray-400 text-5xl mb-4"></i>
                        <p class="text-gray-600">No rooms available for selected criteria</p>
                    </div>
                `;
            } else {
                data.available_rooms.forEach(room => {
                    roomList.appendChild(createRoomCard(room));
                });
            }
            
            document.getElementById('results').classList.remove('hidden');
        }

        function createRoomCard(room) {
            const card = document.createElement('div');
            card.className = 'bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition';
            
            const ratePlanBadgeColor = {
                'EP': 'bg-gray-100 text-gray-700',
                'CP': 'bg-green-100 text-green-700',
                'MAP': 'bg-purple-100 text-purple-700'
            }[room.rate_plan.meal_type] || 'bg-blue-100 text-blue-700';
            
            const discountHtml = room.pricing.discounts.length > 0 ? `
                <div class="bg-green-50 p-4 rounded-lg mt-4">
                    <p class="text-green-700 font-medium mb-2 flex items-center">
                        <i class="fas fa-tag mr-2"></i>Discounts Applied:
                    </p>
                    ${room.pricing.discounts.map(d => `
                        <div class="flex justify-between text-sm">
                            <span>${d.name}</span>
                            <span class="font-semibold text-green-600">-${d.percentage}% (Rs.${d.amount})</span>
                        </div>
                    `).join('')}
                    <div class="border-t border-green-200 mt-2 pt-2 flex justify-between font-semibold">
                        <span>Total Discount:</span>
                        <span class="text-green-600">-Rs.${room.pricing.total_discount}</span>
                    </div>
                </div>
            ` : '';
            
            card.innerHTML = `
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <h3 class="text-2xl font-bold text-gray-800">${room.room_type.name}</h3>
                                <span class="px-3 py-1 rounded-full text-sm font-semibold ${ratePlanBadgeColor}">
                                    ${room.rate_plan.name} (${room.rate_plan.meal_type})
                                </span>
                            </div>
                            <p class="text-gray-600">${room.room_type.description}</p>
                            <div class="flex gap-4 mt-2 text-sm">
                                <span class="text-gray-500"><i class="fas fa-user-friends mr-1"></i>Max ${room.room_type.max_adults} adults</span>
                                <span class="text-gray-500"><i class="fas fa-utensils mr-1"></i>${room.rate_plan.description}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-50 p-4 rounded-lg mb-4">
                        <h4 class="font-semibold text-gray-700 mb-2">Price Breakdown (${room.total_nights} nights)</h4>
                        <div class="space-y-1 text-sm">
                            <div class="flex justify-between">
                                <span>Base Room Price:</span>
                                <span>Rs.${room.pricing.base_room_price}</span>
                            </div>
                            ${room.pricing.rate_plan_adjustment !== 0 ? `
                            <div class="flex justify-between text-blue-600">
                                <span>Rate Plan Adjustment (${room.rate_plan.base_price_multiplier}x):</span>
                                <span>+Rs.${room.pricing.rate_plan_adjustment}</span>
                            </div>
                            ` : ''}
                            ${room.pricing.extra_adult_charges > 0 ? `
                            <div class="flex justify-between">
                                <span>Extra Adult Charges:</span>
                                <span>+Rs.${room.pricing.extra_adult_charges}</span>
                            </div>
                            ` : ''}
                            ${room.pricing.meal_charges > 0 ? `
                            <div class="flex justify-between">
                                <span>Meal Charges (${room.guest_count} guests):</span>
                                <span>+Rs.${room.pricing.meal_charges}</span>
                            </div>
                            ` : ''}
                            <div class="border-t border-gray-300 pt-2 mt-2 flex justify-between font-semibold">
                                <span>Subtotal:</span>
                                <span>Rs.${room.pricing.subtotal}</span>
                            </div>
                        </div>
                    </div>
                    
                    ${discountHtml}
                    
                    <div class="mt-4 flex justify-between items-center">
                        <div>
                            <p class="text-sm text-gray-500">Final Price</p>
                            <p class="text-3xl font-bold text-blue-600">Rs.${room.pricing.final_price}</p>
                            ${room.pricing.total_discount > 0 ? `
                            <p class="text-sm text-green-600">You save Rs.${room.pricing.total_discount}</p>
                            ` : ''}
                        </div>
                        <button class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg transition">
                            Select Room
                        </button>
                    </div>
                </div>
            `;
            
            return card;
        }

        function showError(message) {
            const errorDiv = document.getElementById('error');
            errorDiv.innerHTML = `<i class="fas fa-exclamation-triangle mr-2"></i>${message}`;
            errorDiv.classList.remove('hidden');
            setTimeout(() => errorDiv.classList.add('hidden'), 5000);
        }

        // Set default dates
        window.addEventListener('load', () => {
            const today = new Date();
            const tomorrow = new Date(today);
            tomorrow.setDate(tomorrow.getDate() + 1);
            
            document.getElementById('check_in').value = today.toISOString().split('T')[0];
            document.getElementById('check_out').value = tomorrow.toISOString().split('T')[0];
        });
    </script>
</body>
</html>