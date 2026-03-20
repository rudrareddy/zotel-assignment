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
            <form id="searchForm" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div class="md:col-span-1">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-calendar-check mr-2 text-blue-500"></i>Check-in
                    </label>
                    <input type="date" name="check_in" id="check_in" 
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           required>
                </div>
                <div class="md:col-span-1">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-calendar-times mr-2 text-blue-500"></i>Check-out
                    </label>
                    <input type="date" name="check_out" id="check_out" 
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           required>
                </div>
                <div class="md:col-span-1">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-users mr-2 text-blue-500"></i>Guests
                    </label>
                    <select name="guests" id="guests" 
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required>
                        <option value="1">1 Guest</option>
                        <option value="2" selected>2 Guests</option>
                        <option value="3">3 Guests</option>
                         <option value="4">4 Guests</option>
                    </select>
                </div>
                <div class="md:col-span-1">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-utensils mr-2 text-blue-500"></i>Meal Plan
                    </label>
                    <select name="meal_plan" id="meal_plan" 
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required>
                        <option value="RO">Room Only</option>
                        <option value="BB">Breakfast Included</option>
                        
                    </select>
                </div>
                <div class="md:col-span-1 flex items-end">
                    <button type="submit" 
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition duration-200 flex items-center justify-center gap-2">
                        <i class="fas fa-search"></i>
                        Search Rooms
                    </button>
                </div>
            </form>
        </div>

        <!-- Loading Indicator -->
        <div id="loading" class="hidden text-center py-12">
            <div class="inline-block animate-spin rounded-full h-12 w-12 border-4 border-blue-600 border-t-transparent"></div>
            <p class="mt-4 text-gray-600 text-lg">Searching for available rooms...</p>
        </div>

        <!-- Error Message -->
        <div id="error" class="hidden bg-red-50 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded-lg mb-6">
        </div>

        <!-- Results Container -->
        <div id="results" class="hidden">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">
                    <i class="fas fa-hotel mr-2 text-blue-600"></i>
                    Available Rooms
                </h2>
                <div id="searchSummary" class="text-gray-600 bg-blue-50 px-4 py-2 rounded-lg">
                </div>
            </div>
            <div id="roomList" class="grid grid-cols-1 md:grid-cols-2 gap-6">
            </div>
        </div>
    </div>

    <script>
        document.getElementById('searchForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            // Set min date to today
            const today = new Date().toISOString().split('T')[0];
            document.getElementById('check_in').min = today;
            
            const formData = {
                check_in: document.getElementById('check_in').value,
                check_out: document.getElementById('check_out').value,
                guests: document.getElementById('guests').value,
                meal_plan: document.getElementById('meal_plan').value
            };

            // Show loading, hide results and error
            document.getElementById('loading').classList.remove('hidden');
            document.getElementById('results').classList.add('hidden');
            document.getElementById('error').classList.add('hidden');
            document.getElementById('roomList').innerHTML = '';

            try {
                const params = new URLSearchParams(formData);
                const response = await fetch(`/api/v1/search?${params}`);
                const data = await response.json();

                document.getElementById('loading').classList.add('hidden');

                if (data.success) {
                    displayResults(data.data);
                } else {
                    console.log(data);
                    if (data.status === false) {
                        Object.keys(data.data).forEach(field => {
                            if (Array.isArray(data.data[field])) {
                                showError(data.data[field][0]);
                            }
                        });
                    }
                    
                }
            } catch (error) {
                document.getElementById('loading').classList.add('hidden');
                showError('Failed to fetch results. Please check your connection and try again.');
                console.error('Search error:', error);
            }
        });

        function displayResults(data) {
            const resultsDiv = document.getElementById('results');
            const roomList = document.getElementById('roomList');
            const searchSummary = document.getElementById('searchSummary');
            
            // Update search summary
            searchSummary.innerHTML = `
                <i class="fas fa-calendar-alt mr-2"></i>
                ${data.search_criteria.check_in} to ${data.search_criteria.check_out} 
                | ${data.search_criteria.nights} nights 
                | ${data.search_criteria.guests} guest(s)
                | ${data.search_criteria.meal_plan === 'BB' ? 'Breakfast Included' : 'Room Only'}
            `;
            
            roomList.innerHTML = '';
            
            if (data.available_rooms.length === 0) {
                roomList.innerHTML = `
                    <div class="col-span-2 text-center py-12 bg-white rounded-lg shadow">
                        <i class="fas fa-bed text-gray-400 text-5xl mb-4"></i>
                        <p class="text-gray-600 text-lg">No rooms available for selected criteria</p>
                        <p class="text-gray-500 mt-2">Try different dates or guest count</p>
                    </div>
                `;
            } else {
                data.available_rooms.forEach(room => {
                    const roomCard = createRoomCard(room);
                    roomList.appendChild(roomCard);
                });
            }
            
            resultsDiv.classList.remove('hidden');
        }

        function createRoomCard(room) {
            const card = document.createElement('div');
            card.className = 'bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition duration-300 border border-gray-100';
            
            const discountHtml = room.pricing.discounts.length > 0 ? `
                <div class="bg-green-50 p-4 border-t border-green-100">
                    <p class="text-green-700 font-medium mb-2 flex items-center">
                        <i class="fas fa-tag mr-2"></i>
                        Discounts Applied:
                    </p>
                    ${room.pricing.discounts.map(d => `
                        <div class="flex justify-between text-sm text-green-600 mb-1">
                            <span>${d.name}</span>
                            <span class="font-semibold">-Rs.${d.amount}</span>
                        </div>
                    `).join('')}
                </div>
            ` : '';
            
            const mealPlanHtml = room.meal_plan ? `
                <div class="flex justify-between text-sm text-gray-600 mb-1">
                    <span>${room.meal_plan.name} (${room.guest_count} guests × ${room.total_nights} nights):</span>
                    <span class="font-semibold">Rs.${room.meal_plan.total_price}</span>
                </div>
            ` : '';
            
            card.innerHTML = `
                <div class="p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-xl font-bold text-gray-800">${room.room_type.name}</h3>
                            <p class="text-gray-600 text-sm mt-1">${room.room_type.description}</p>
                        </div>
                        <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-3 py-1 rounded-full">
                            Max ${room.room_type.max_adults} guests
                        </span>
                    </div>
                    
                    <div class="border-t border-b border-gray-100 py-4 mb-4">
                        <div class="flex justify-between text-sm text-gray-600 mb-2">
                            <span>Room charges (${room.total_nights} nights):</span>
                            <span class="font-semibold">Rs.${room.pricing.price_breakdown.room_charges}</span>
                        </div>
                        <div class="flex justify-between text-sm text-gray-600 mb-2">
                            <span>Extra guest charges:</span>
                            <span class="font-semibold">Rs.${room.pricing.price_breakdown.extra_guest_charges}</span>
                        </div>
                        ${mealPlanHtml}
                        <div class="flex justify-between text-sm font-medium text-gray-700 mt-2 pt-2 border-t border-gray-100">
                            <span>Subtotal:</span>
                            <span>Rs.${room.pricing.base_price}</span>
                        </div>
                    </div>
                    
                    ${discountHtml}
                    
                    <div class="mt-4 flex justify-between items-center">
                        <div>
                            <p class="text-sm text-gray-500">Final Price</p>
                            <p class="text-2xl font-bold text-blue-600">Rs.${room.pricing.final_price}</p>
                        </div>
                        <button class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded-lg transition duration-200">
                            Select Room
                        </button>
                    </div>
                    
                    <div class="mt-4 text-xs text-gray-400 flex items-center">
                        <i class="fas fa-check-circle text-green-500 mr-1"></i>
                        Instant confirmation
                    </div>
                </div>
            `;
            
            return card;
        }

        let errorTimeout;

function showError(message) {
    const errorDiv = document.getElementById('error');

    // Clear previous timeout (important)
    if (errorTimeout) {
        clearTimeout(errorTimeout);
    }

    // Support multiple messages
    if (Array.isArray(message)) {
        errorDiv.innerHTML = message.map(msg => 
            `<div><i class="fas fa-exclamation-triangle mr-2"></i>${msg}</div>`
        ).join('');
    } else {
        errorDiv.innerHTML = `<i class="fas fa-exclamation-triangle mr-2"></i>${message}`;
    }

    // Show error
    errorDiv.classList.remove('hidden');
    errorDiv.classList.add('opacity-100');

    // Auto-hide after 5 seconds
    errorTimeout = setTimeout(() => {
        errorDiv.classList.add('hidden');
        errorDiv.classList.remove('opacity-100');
    }, 5000);
}

        // Set default dates (today and tomorrow)
        window.addEventListener('load', () => {
            const today = new Date();
            const tomorrow = new Date(today);
            tomorrow.setDate(tomorrow.getDate() + 1);
            const nextWeek = new Date(today);
            nextWeek.setDate(nextWeek.getDate() + 7);
            
            document.getElementById('check_in').value = today.toISOString().split('T')[0];
            document.getElementById('check_out').value = tomorrow.toISOString().split('T')[0];
        });
    </script>
</body>
</html>