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
							@mousedown.prevent="selectFlight('departure', flight.city + ' - ' + flight.name)"
							class="px-4 py-2 cursor-pointer hover:bg-gray-200"
						>
							{{ flight.city }} - {{ flight.name }}
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
							@mousedown.prevent="selectFlight('arrival', flight.city + ' - ' + flight.name)"
							class="px-4 py-2 cursor-pointer hover:bg-gray-200"
						>
							{{ flight.city }} - {{ flight.name }}
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
					<div v-if="sortedFlights.length === 0">Aucun vol trouvé.</div>
					<div v-for="(flight, index) in sortedFlights" :key="index" class="grid grid-cols-7 gap-4 mb-6">
						<div>{{ generatedName(flight) }}</div>
						<div>{{ flight.date_hour_fly_off }}</div>
						<div>{{ flight.date_hour_landing }}</div>
						<div>{{ getPlacesRemaining(flight) }}</div>
						<div>{{ flight.plane.model }}</div>
						<div>{{ flight.price || '0€' }}</div>
						<div 
						:class="[
									{
										'text-green-600 font-bold': flight.state?.toLowerCase() === 'scheduled',
										'text-red-600 font-bold': flight.state?.toLowerCase() === 'completed',
										'text-orange-500 font-bold': flight.state?.toLowerCase() === 'delayed'
									},
									!flight.state ? 'text-gray-500' : ''
								]"
						>
							{{ formatState(flight.state) }}
						</div>
						<button 
							class="px-4 py-2 rounded-full bg-[#000000] text-[#ffffff] focus:outline-none focus:ring focus:ring-blue-500 focus:border-blue-500 shadow-sm"
							@click="openModal(flight.price)"
							>
							Réserver
						</button>
					</div>
				</div>
			</div>
		</div>
		<Reservation v-if="showModal" :price="selectedPrice" @close="closeModal" />
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
		showModal: false,
		selectedPrice: 0
	};
  },
  computed: {
	sortedFlights() {
		const sorted = this.flights.sort((a, b) => {
			let modifier = this.sortOrder === 'asc' ? 1 : -1;
			if (this.sortBy === 'price') {
				return (a.price - b.price) * modifier;
			} else if (this.sortBy === 'duration') {
				let durationA = new Date(a.arrivalDate) - new Date(a.departureDate);
				let durationB = new Date(b.arrivalDate) - new Date(b.departureDate);
				return (durationA - durationB) * modifier;
			} else if (this.sortBy === 'departure') {
				return (new Date(a.departureDate) - new Date(b.departureDate)) * modifier;
			} else if (this.sortBy === 'arrival') {
				return (new Date(a.arrivalDate) - new Date(b.arrivalDate)) * modifier;
			}
		});
		console.log('Vols triés:', sorted);
		return sorted;
	}
  },
  methods: {
	generatedName(flight) {
		return `${flight.airport_fly_off.city} - ${flight.airport_landing.city}`;
	},
    formatState(state) {
  		if (!state) return 'unknown';
  		return state.charAt(0).toUpperCase() + state.slice(1).toLowerCase();
	},
	filterFlights(type) {
		if (type === 'departure') {
			const uniqueAirports = new Set();
			this.filteredDepartureFlights = this.flights
				.filter(flight => {
					const city = flight.airport_fly_off?.city;
					if (city) {
						console.log('Departure city:', city);
						return city.toLowerCase().startsWith(this.departureQuery.toLowerCase());
					}
					return false;
				})
				.map(flight => {
					const airport = {
						city: flight.airport_fly_off.city,
						name: flight.airport_fly_off.name
					};
					if (!uniqueAirports.has(airport.city + ' - ' + airport.name)) {
						uniqueAirports.add(airport.city + ' - ' + airport.name);
						return airport;
					}
				})
				.filter(Boolean);
		}

		if (type === 'arrival') {
			const uniqueAirports = new Set();
			this.filteredArrivalFlights = this.flights
				.filter(flight => {
					const city = flight.airport_landing?.city;
					if (city) {
						console.log('Arrival city:', city);
						return city.toLowerCase().startsWith(this.arrivalQuery.toLowerCase());
					}
					return false;
				})
				.map(flight => {
					const airport = {
						city: flight.airport_landing.city,
						name: flight.airport_landing.name
					};
					if (!uniqueAirports.has(airport.city + ' - ' + airport.name)) {
						uniqueAirports.add(airport.city + ' - ' + airport.name);
						return airport;
					}
				})
				.filter(Boolean);
		}
	},
	selectFlight(type, name) {
		if (type === 'departure') {
			this.departureQuery = name;
			this.showDepartureDropdown = false;
		}

		if (type === 'arrival') {
			this.arrivalQuery = name;
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
			const token = localStorage.getItem('token');
			const response = await axios.get('http://127.0.0.1:8000/api/flies', {
				headers: {
					Authorization: `Bearer ${token}`
				}
			});
			console.log('Réponse de l\'API:', response.data);
			this.flights = response.data.filter(flight => {
				const departureCity = flight.airport_fly_off?.city;
				const arrivalCity = flight.airport_landing?.city;
				if (departureCity && arrivalCity) {
					console.log('Departure city:', departureCity);
					console.log('Arrival city:', arrivalCity);
					return departureCity.toLowerCase().includes(this.departureQuery.toLowerCase()) &&
						   arrivalCity.toLowerCase().includes(this.arrivalQuery.toLowerCase());
				}
				return false;
			});
			console.log('Vol filtrés:', this.flights);
		} catch (error) {
			console.error('Erreur lors de la récupération des vols:', error);
		}
	},
	openModal(price) {
		this.selectedPrice = price;
		this.showModal = true;
	},
	closeModal() {
		this.showModal = false;
	},
	async getPlacesRemaining(flight) {
		const token = localStorage.getItem('token');
		const maxPlacesPlane = await axios.get('http://127.0.0.1:8000/api/planes', {
			headers: {
				Authorization: `Bearer ${token}`
			}
		});
		const response = await axios.get('http://127.0.0.1:8000/api/bookings', {
			headers: {
				Authorization: `Bearer ${token}`
			}
		});
		const flightId = flight.id;
		const bookingsForFlight = response.data.filter(booking => booking.fly_id === flightId);
		const placesTaken = bookingsForFlight.length;
		const placesRemaining = maxPlacesPlane.data.find(plane => plane.id === flight.plane_id).max_places - placesTaken;
		return placesRemaining;
	}
  },
  async mounted() {
	this.searchFlights();
  }
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