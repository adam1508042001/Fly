<template>
	<div class="flex flex-col min-h-screen">
	  	<NavBar />
	  	<div class="grid items-center justify-center grid-cols-1 grid-rows-[150px_100px_auto] bg-[#ffffff] bg-opacity-80 rounded-[30px] mx-10 my-5">
		<!-- head -->
			<div class="grid grid-cols-[5fr_1fr] grid-rows-1 p-8">
				<div class="text-start mb-6">
					<h1 class="text-4xl font-bold text-center text-[#000000]">
						A return flight ?
					</h1>
				</div>

				<!-- Sort by -->
				<div class="flex justify-end items-center w-[300px]">
					<label for="sort" class="block font-bold text-[#000000] w-[80px]">
						Sort by
					</label>
					<select
						id="sort"
						v-model="sortBy"
						class="block w-full px-4 py-2 mt-1 rounded-full border border-gray-300 focus:outline-none focus:ring focus:ring-blue-500 focus:border-blue-500 shadow-sm">
						<option value="price">Price</option>
						<option value="duration">Duration</option>
					</select>
					<button @click="toggleSortOrder" class="ml-2 px-2 py-1 rounded-full bg-[#000000] text-[#ffffff] focus:outline-none focus:ring focus:ring-blue-500 focus:border-blue-500 shadow-sm">
						{{ sortOrder === 'asc' ? '↑' : '↓' }}
					</button>
				</div>
			</div>

			<!-- grid for results -->
			<div class="mt-8 mx-8">
				<div class="grid grid-cols-8 gap-4 font-opacity-80 mb-6 pb-2 text-gray-500 border-b-2 border-gray-400">
					<h3>Flight</h3>
					<h3>Departure Date</h3>
					<h3>Arrival Date</h3>
					<h3>Remaining places</h3>
					<h3>Plane Name</h3>
					<h3>Price</h3>
					<h3>Status</h3>
				</div>
		
				<div class="mb-6">
					<div v-if="sortedFlights.length === 0">No flights found.</div>
					<div v-for="(flight, index) in sortedFlights" :key="index" class="grid grid-cols-8 gap-4 mb-6">
					<div>{{ generatedName(flight) }}</div>
					<div>{{ flight.date_hour_fly_off }}</div>
					<div>{{ flight.date_hour_landing }}</div>
					<div>{{ getPlacesRemaining(flight) || 'Loading...' }}</div>
					<div>{{ flight.plane.model }}</div>
					<div>{{ flight.price || '0€' }}</div>
					<div 
						:class="{
						'text-green-600 font-bold': flight.state?.toLowerCase() === 'scheduled',
						'text-red-600 font-bold': flight.state?.toLowerCase() === 'completed',
						'text-orange-500 font-bold': flight.state?.toLowerCase() === 'delayed',
						'text-gray-500': !flight.state
						}"
					>
						{{ formatState(flight.state) }}
					</div>
					<button 
						class="px-4 py-2 rounded-full bg-[#000000] text-[#ffffff] focus:outline-none focus:ring focus:ring-blue-500 focus:border-blue-500 shadow-sm"
						@click="openModal(flight.price, flight.id_fly)"
						>
						Book
					</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
import NavBar from './NavBar.vue';
import axios from 'axios';

export default {
	components: {
		NavBar
	},
	data() {
		return {
			showModal: false,
			selectedPrice: 0,
			sortBy: 'price',
			sortOrder: 'asc',
			flights: [],
			userBookings: []
		};
	},
	methods: {
		async getUserBookings() {
			try {
				const token = localStorage.getItem('token');
				const response = await axios.get('http://127.0.0.1:8000/api/bookings', {
					headers: {
						Authorization: `Bearer ${token}`
					}
				});
				this.userBookings = response.data.filter(booking => booking.id_client === this.userId);
				this.searchReturnFlights();
			} catch (error) {
				console.error('Error while fetching user bookings:', error);
			}
		},
		async searchReturnFlights() {
			try {
				const token = localStorage.getItem('token');
				const response = await axios.get('http://127.0.0.1:8000/api/flies', {
					headers: {
						Authorization: `Bearer ${token}`
					}
				});
				this.flights = response.data.filter(flight => {
					return this.userBookings.some(booking => 
						new Date(flight.date_hour_fly_off) > new Date(booking.id_fly.date_hour_landing) &&
						flight.airport_fly_off.id_airport === booking.id_fly.airport_landing.id_airport &&
						flight.airport_landing.id_airport === booking.id_fly.airport_fly_off.id_airport
					);
				});
			} catch (error) {
				console.error('Error while fetching return flights:', error);
			}
		},
		formatStatus(status) {
			switch (status.toLowerCase()) {
				case 'available':
					return 'Available';
				case 'unavailable':
					return 'Unavailable';
				case 'flying':
					return 'Flying';
				default:
					return 'Unknown';
			}
		},
		openModal(price) {
			this.showModal = true;
			this.selectedPrice = price;
		},
		closeModal() {
			this.showModal = false;
		},
		toggleSortOrder() {
			this.sortOrder = this.sortOrder === 'asc' ? 'desc' : 'asc';
		}
	},
	computed: {
		sortedFlights() {
			return this.flights.sort((a, b) => {
				let modifier = this.sortOrder === 'asc' ? 1 : -1;
				if (this.sortBy === 'price') {
					return (a.price - b.price) * modifier;
				} else if (this.sortBy === 'duration') {
					let durationA = new Date(a.arrivalDate) - new Date(a.departureDate);
					let durationB = new Date(b.arrivalDate) - new Date(b.departureDate);
					return (durationA - durationB) * modifier;
				}
			});
		}
	},
	async mounted() {
		await this.getUserBookings();
	}
};

</script>

<style scoped>
.modal-overlay {
	position: fixed;
	top: 0;
	left: 0;
	width: 100%;
	height: 100%;
	background: rgba(0, 0, 0, 0.5);
	backdrop-filter: blur(3px);
	display: flex;
	justify-content: center;
	align-items: center;
	z-index: 1000;
}

.modal-content {
	background: white;
	padding: 20px;
	border-radius: 8px;
	width: 80%;
	max-width: 600px;
}
</style>