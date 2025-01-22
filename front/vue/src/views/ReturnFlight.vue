<template>
	<div class="modal-overlay" @click.self="closeModal">
		<!-- Search box -->
		<div class="modal-content">
			<!-- head -->
			<div class="grid grid-cols-[5fr_1fr] grid-rows-1 p-8">
				<!-- Title -->
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
				<!-- column title -->
				<div class="grid grid-cols-7 gap-4 font-opacity-80 mb-6 pb-2 text-gray-500 border-b-2 border-gray-400">
					<h3> Flight </h3>

					<h3> Departure Date </h3>
					
					<h3>Arrival Date</h3>

					<h3> Remaining places </h3>

					<h3> Plane Name </h3>

					<h3> Price </h3>

					<h3> Status </h3>
				</div>

				<!-- result -->
				<div class="mb-6">
					<div v-for="(flight, index) in sortedFlights" :key="index" class="grid grid-cols-7 gap-4 mb-6">
						<div>{{ flight.name }}</div>
						<div>{{ flight.departureDate }}</div>
						<div>{{ flight.arrivalDate }}</div>
						<div>{{ flight.remainingPlaces }}</div>
						<div>{{ flight.planeName }}</div>
						<div>{{ flight.price }}</div>
						<div 
							:class="{
							'text-green-600 font-bold': flight.status.toLowerCase() === 'available',
							'text-red-600 font-bold': flight.status.toLowerCase() === 'unavailable',
							'text-orange-500 font-bold': flight.status.toLowerCase() === 'flying'
							}"
						>
							{{ formatStatus(flight.status) }}
						</div>
						<button @click="openModal(flight.price)">Réserver</button>
					</div>
				</div>
			</div>
		</div>
		<Reservation v-if="showModal" :price="selectedPrice" @close="closeModal" />
	</div>
</template>

<script>
import axios from 'axios';
import Reservation from './Reservation.vue';

export default {
	data() {
		return {
			showModal: false,
			selectedPrice: 0,
			sortBy: 'price',
			sortOrder: 'asc',
			flights: [],
			confirmedFlight: null
		};
	},
	methods: {
		async getConfirmedFlight() {
			try {
				const response = await axios.get('http://127.0.0.1:8000/api/confirmed-flight'); // peut etre a changer selon la route et le controller
				this.confirmedFlight = response.data;
				this.searchReturnFlights();
			} catch (error) {
				console.error('Error while fetching confirmed flight:', error);
			}
		},
		async searchReturnFlights() {
			try {
				const response = await axios.get('http://127.0.0.1:8000/api/flights');
				this.flights = response.data.filter(flight => {
					return new Date(flight.departureDate) > new Date(this.confirmedFlight.arrivalDate) &&
						   flight.airport_fly_off.city === this.confirmedFlight.airport_landing.city &&
						   flight.airport_landing.city === this.confirmedFlight.airport_fly_off.city;
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
	mounted() {
		this.getConfirmedFlight();
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