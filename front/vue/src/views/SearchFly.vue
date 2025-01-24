<template>
	<div class="flex flex-col min-h-screen">
		<!-- Navbar -->
		<NavBar />
		<!-- Search box -->
		<div class="grid items-center justify-center grid-cols-1 grid-rows-[150px_100px_auto] bg-[#ffffff] bg-opacity-80 rounded-[30px] mx-10 my-5">
			<!-- head -->
			<div class="grid grid-cols-[5fr_1fr] grid-rows-1 p-8">
				<!-- Title -->
				<div class="text-start mb-6">
					<h1 class="text-4xl font-bold text-center text-[#000000]">
						Search for a flight
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
						<option value="departure">Departure</option>
						<option value="arrival">Arrival</option>
					</select>
					<button @click="toggleSortOrder" class="ml-2 px-2 py-1 rounded-full bg-[#000000] text-[#ffffff] focus:outline-none focus:ring focus:ring-blue-500 focus:border-blue-500 shadow-sm">
						{{ sortOrder === 'asc' ? '↑' : '↓' }}
					</button>
				</div>
			</div>

			<!-- field and list Departure, arrival and search button -->
			<div class="grid grid-cols-[250px_250px_100px] gap-4 p-8 w-[900px]">
				<!-- Departure -->
				<div>
					<input
						type="text"
						id="departure"
						placeholder="Departure"
						v-model="departureQuery"
						@focus="showDepartureDropdown = true"
						@blur="hideDropdown('departure')"
						@input="filterFlights('departure')"
						class="mt-1 block w-full px-4 py-2 rounded-full border border-gray-300 focus:outline-none focus:ring focus:ring-blue-500 focus:border-blue-500 shadow-sm"
					/>
					<ul v-if="showDepartureDropdown" class="absolute bg-white border border-gray-300 rounded-md shadow-lg">
						<li
							v-for="(flight, index) in filteredDepartureFlights"
							:key="index"
							@mousedown.prevent="selectFlight('departure', flight.city)"
							class="px-4 py-2 cursor-pointer hover:bg-gray-200"
						>
							{{ flight.city }}
						</li>
					</ul>
				</div>

				<!-- Arrival -->
				<div>
					<input
						type="text"
						id="arrival"
						placeholder="Arrival"
						v-model="arrivalQuery"
						@focus="showArrivalDropdown = true"
						@blur="hideDropdown('arrival')"
						@input="filterFlights('arrival')"
						class="mt-1 block w-full px-4 py-2 rounded-full border border-gray-300 focus:outline-none focus:ring focus:ring-blue-500 focus:border-blue-500 shadow-sm"
					/>
					<ul v-if="showArrivalDropdown" class="absolute bg-white border border-gray-300 rounded-md shadow-lg">
						<li
							v-for="(flight, index) in filteredArrivalFlights"
							:key="index"
							@mousedown.prevent="selectFlight('arrival', flight.city)"
							class="px-4 py-2 cursor-pointer hover:bg-gray-200"
						>
							{{ flight.city }}
						</li>
					</ul>
				</div>
				
				<!-- Search button -->
				<div class="flex justify-center items-center">
					<button
						@click="searchFlights"
						class="px-4 py-2 mt-1 rounded-full bg-[#000000] text-[#ffffff] focus:outline-none focus:ring focus:ring-blue-500 focus:border-blue-500 shadow-sm"
					>
						Search
					</button>
				</div>
			</div>

			<!-- grid for results -->
			<div class="mt-8 mx-8">
				<!-- column title -->
				<div class="grid grid-cols-7 gap-4 font-opacity-80 mb-6 pb-2 text-gray-500 border-b-2 border-gray-400">
					<h3> Flight </h3>

					<h3> Departure Date </h3>
					
					<h3> Arrival Date </h3>

					<h3> Remaining places </h3>

					<h3> Plane Name </h3>

					<h3> Price </h3>

					<h3> Status </h3>
				</div>

				<!-- result -->
				<div class="mb-6">
					<div v-for="(flight, index) in sortedFlights" :key="index" class="grid grid-cols-7 gap-4 mb-6">
						<div>{{ generateFlightName(flight) }}</div>
						<div>{{ flight.date_hour_fly_off }}</div>
						<div>{{ flight.date_hour_landing }}</div>
						<div>{{ calculateRemainingPlaces(flight) }}</div>
						<div>{{ flight.plane.model }}</div>
						<div>{{ formatPrice(flight.price) }}</div>
						<div 
							:class="{
							'text-green-600 font-bold': flight.state && flight.state.toLowerCase() === 'scheduled',
							'text-red-600 font-bold': flight.state && flight.state.toLowerCase() === 'completed',
							'text-orange-500 font-bold': flight.state && flight.state.toLowerCase() === 'delayed'
							}"
						>
							{{ formatState(flight.state) }}
						</div>
						<div v-if="flight.state.toLowerCase() !== 'completed'" class="col-span-7 flex justify-end">
							<button @click="openModal(flight.price)" class="px-4 py-2 rounded-full bg-[#000000] text-[#ffffff] focus:outline-none focus:ring focus:ring-blue-500 focus:border-blue-500 shadow-sm">
								Réserver
							</button>
						</div>
					</div>
				</div>
			</div>
		</div>
		<Reservation v-if="showModal" :price="selectedPrice" @close="closeModal" @confirm="confirmReservation" />
	</div>
</template>

<script>
import axios from 'axios';
import NavBar from './NavBar.vue';
import Reservation from './Reservation.vue';

export default {
  components: {
	NavBar,
	Reservation
  },
  data() {
	return {
		departureQuery: '',
		showDepartureDropdown: false,
		filteredDepartureFlights: [],
		arrivalQuery: '',
		showArrivalDropdown: false,
		filteredArrivalFlights: [],
		sortBy: 'price',
		sortOrder: 'asc',
		flights: [],
		bookings: [],
		showModal: false,
		selectedPrice: 0
	};
  },
  computed: {
	sortedFlights() {
		return this.flights.sort((a, b) => {
			let modifier = this.sortOrder === 'asc' ? 1 : -1;
			if (this.sortBy === 'price') {
				return (a.price - b.price) * modifier;
			} else if (this.sortBy === 'duration') {
				let durationA = new Date(a.date_hour_landing) - new Date(a.date_hour_fly_off);
				let durationB = new Date(b.date_hour_landing) - new Date(b.date_hour_fly_off);
				return (durationA - durationB) * modifier;
			} else if (this.sortBy === 'departure') {
				return (new Date(a.date_hour_fly_off) - new Date(b.date_hour_fly_off)) * modifier;
			} else if (this.sortBy === 'arrival') {
				return (new Date(a.date_hour_landing) - new Date(b.date_hour_landing)) * modifier;
			}
		});
	}
  },
  methods: {
	formatPrice(price) {
	  return price ? price + ' €' : '0.00 €';
	},
	formatState(state) {
	  return state ? state.charAt(0).toUpperCase() + state.slice(1).toLowerCase() : '';
	},
	filterFlights(type) {
		if (type === 'departure') {
			const uniqueCities = new Set();
			this.filteredDepartureFlights = this.flights
				.filter(flight => flight.airport_fly_off && flight.airport_fly_off.city && flight.airport_fly_off.city.toLowerCase().startsWith(this.departureQuery.toLowerCase()))
				.map(flight => {
					const city = flight.airport_fly_off.city;
					if (!uniqueCities.has(city)) {
						uniqueCities.add(city);
						return { city };
					}
				})
				.filter(Boolean);
			console.log('Filtered Departure Flights:', this.filteredDepartureFlights);
		}

		if (type === 'arrival') {
			const uniqueCities = new Set();
			this.filteredArrivalFlights = this.flights
				.filter(flight => flight.airport_landing && flight.airport_landing.city && flight.airport_landing.city.toLowerCase().startsWith(this.arrivalQuery.toLowerCase()))
				.map(flight => {
					const city = flight.airport_landing.city;
					if (!uniqueCities.has(city)) {
						uniqueCities.add(city);
						return { city };
					}
				})
				.filter(Boolean);
			console.log('Filtered Arrival Flights:', this.filteredArrivalFlights);
		}
	},
	selectFlight(type, city) {
		if (type === 'departure') {
			this.departureQuery = city;
			this.showDepartureDropdown = false;
		}

		if (type === 'arrival') {
			this.arrivalQuery = city;
			this.showArrivalDropdown = false;
		}
	},
	hideDropdown(type) {
		setTimeout(() => {
			if (type === 'departure') {
				this.showDepartureDropdown = false;
			}

			if (type === 'arrival') {
				this.showArrivalDropdown = false;
			}
		}, 200);
	},
	toggleSortOrder() {
		this.sortOrder = this.sortOrder === 'asc' ? 'desc' : 'asc';
	},
	async searchFlights() {
		try {
			console.log('Departure Query:', this.departureQuery);
			console.log('Arrival Query:', this.arrivalQuery);

			const response = await axios.get('http://127.0.0.1:8000/api/flies');
			this.flights = response.data.filter(flight => {
				console.log('Search Flight:', flight);
				const departureMatch = flight.airport_fly_off && flight.airport_fly_off.city && flight.airport_fly_off.city.toLowerCase().includes(this.departureQuery.toLowerCase());
				const arrivalMatch = flight.airport_landing && flight.airport_landing.city && flight.airport_landing.city.toLowerCase().includes(this.arrivalQuery.toLowerCase());
				return departureMatch && arrivalMatch;
			});
			await this.fetchBookings();
			console.log('Filtered Flights:', this.flights);
		} catch (error) {
			console.error('Erreur lors de la récupération des vols:', error);
		}
	},
	async fetchBookings() {
		try {
			const response = await axios.get('http://127.0.0.1:8000/api/bookings');
			this.bookings = response.data;
			console.log('Bookings:', this.bookings);
		} catch (error) {
			console.error('Erreur lors de la récupération des réservations:', error);
		}
	},
	calculateRemainingPlaces(flight) {
		const bookedSeats = this.bookings.filter(booking => booking.id_fly === flight.id).length;
		return flight.plane.max_place - bookedSeats;
	},
	generateFlightName(flight) {
		if (flight.airport_fly_off && flight.airport_landing) {
			return `${flight.airport_fly_off.city} ${flight.airport_fly_off.name} -> ${flight.airport_landing.city} ${flight.airport_landing.name}`;
		}
		return 'Unknown Flight';
	},
	openModal(price) {
		this.selectedPrice = price;
		this.showModal = true;
	},
	closeModal() {
		this.showModal = false;
	},
	async confirmReservation(flight) {
		try {
			// Enregistrer la réservation dans la base de données
			const bookingResponse = await axios.post('http://127.0.0.1:8000/api/bookings', {
				id_fly: flight.id,
				// Ajoutez d'autres champs nécessaires pour la réservation
			});
			console.log('Booking Response:', bookingResponse.data);

			// Envoyer un email de confirmation
			const emailResponse = await axios.post('http://127.0.0.1:8000/api/send-email', {
				to: 'client@example.com', // Remplacez par l'adresse email du client
				subject: 'Confirmation de réservation',
				body: `Votre réservation pour le vol ${this.generateFlightName(flight)} a été confirmée.`,
			});
			console.log('Email Response:', emailResponse.data);

			alert('Réservation confirmée et email envoyé.');
		} catch (error) {
			console.error('Erreur lors de la confirmation de la réservation:', error);
			alert('Erreur lors de la confirmation de la réservation.');
		}
	}
  },
  async mounted() {
	await this.searchFlights();
  },
};
</script>


<style>
html,
body {
  height: 100%;
  margin: 0;
  font-family: 'Inter', sans-serif;
}
</style>