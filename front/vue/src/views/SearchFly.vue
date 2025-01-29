<template>
	<div class="flex flex-col min-h-screen">
	  <NavBar />
	  <div class="grid items-center justify-center grid-cols-1 grid-rows-[150px_100px_auto] bg-[#ffffff] bg-opacity-80 rounded-[30px] mx-10 my-5">
		<!-- head -->
		<div class="grid grid-cols-[5fr_1fr] grid-rows-1 p-8">
		  <div class="text-start mb-6">
			<h1 class="text-4xl font-bold text-center text-[#000000]">
			  Search for a flight
			</h1>
		  </div>
  
		  <!-- Sort -->
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
  
		<!-- Search fields -->
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
			<ul v-if="showDepartureDropdown" class="absolute bg-white border border-gray-300 rounded-md shadow-lg z-10">
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
			<ul v-if="showArrivalDropdown" class="absolute bg-white border border-gray-300 rounded-md shadow-lg z-10">
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
		  
		  <div class="flex justify-center items-center">
			<button
			  @click="searchFlights"
			  class="px-4 py-2 mt-1 rounded-full bg-[#000000] text-[#ffffff] focus:outline-none focus:ring focus:ring-blue-500 focus:border-blue-500 shadow-sm"
			>
			  Search
			</button>
		  </div>
		</div>
  
		<!-- Results grid -->
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
			  <div>{{ remainingPlacesMap[flight.id] }}</div>
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
	  <Reservation 
		v-if="showModal" 
		:price="selectedPrice" 
		:id_fly="selectedFlyId" 
		@close="closeModal" 
	  />
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
		remainingPlacesMap: {},
		showModal: false,
		selectedPrice: 0,
		selectedFlyId: null,
		userId: null
	  };
	},
	computed: {
	  sortedFlights() {
		return [...this.flights].sort((a, b) => {
		  const modifier = this.sortOrder === 'asc' ? 1 : -1;
		  
		  switch(this.sortBy) {
			case 'price':
			  return (a.price - b.price) * modifier;
			case 'duration':
			  const durationA = new Date(a.date_hour_landing) - new Date(a.date_hour_fly_off);
			  const durationB = new Date(b.date_hour_landing) - new Date(b.date_hour_fly_off);
			  return (durationA - durationB) * modifier;
			case 'departure':
			  return (new Date(a.date_hour_fly_off) - new Date(b.date_hour_fly_off)) * modifier;
			case 'arrival':
			  return (new Date(a.date_hour_landing) - new Date(b.date_hour_landing)) * modifier;
			default:
			  return 0;
		  }
		});
	  }
	},
	methods: {
		generatedName(flight) {
			return `${flight.airport_fly_off?.city || 'Unknown'} - ${flight.airport_landing?.city || 'Unknown'}`;
		},
		formatState(state) {
			if (!state) return 'Unknown';
			return state.charAt(0).toUpperCase() + state.slice(1).toLowerCase();
		},
		filterFlights(type) {
			const query = type === 'departure' ? this.departureQuery : this.arrivalQuery;
			const airportKey = type === 'departure' ? 'airport_fly_off' : 'airport_landing';
			const uniqueAirports = new Set();
	
			const filtered = this.flights
			.filter(flight => {
				const airport = flight[airportKey];
				return airport?.city?.toLowerCase().includes(query.toLowerCase());
			})
			.map(flight => {
				const airport = flight[airportKey];
				const key = `${airport.city} - ${airport.name}`;
				if (!uniqueAirports.has(key)) {
				uniqueAirports.add(key);
				return { city: airport.city, name: airport.name };
				}
				return null;
			})
			.filter(Boolean);
	
			if (type === 'departure') {
			this.filteredDepartureFlights = filtered;
			} else {
			this.filteredArrivalFlights = filtered;
			}
		},
		selectFlight(type, name) {
			if (type === 'departure') {
			this.departureQuery = name;
			this.showDepartureDropdown = false;
			} else {
			this.arrivalQuery = name;
			this.showArrivalDropdown = false;
			}
		},
		hideDropdown(type) {
			setTimeout(() => {
			if (type === 'departure') {
				this.showDepartureDropdown = false;
			} else {
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
				headers: { Authorization: `Bearer ${token}` }
			});
	
			this.flights = response.data.filter(flight => {

				const departureCityQuery = this.departureQuery.split(' - ')[0].toLowerCase();
				const arrivalCityQuery = this.arrivalQuery.split(' - ')[0].toLowerCase();

				const departureMatch = !this.departureQuery || 
				flight.airport_fly_off?.city?.toLowerCase().includes(departureCityQuery);
				const arrivalMatch = !this.arrivalQuery || 
				flight.airport_landing?.city?.toLowerCase().includes(arrivalCityQuery);

				return departureMatch && arrivalMatch;
			});

			this.flights.forEach(flight => {
				this.remainingPlacesMap[flight.id_fly] = 'Loading...';
			});

			await this.loadAllRemainingPlaces();
			
			} catch (error) {
			console.error('Error fetching flights:', error);
			alert('Error fetching flights. Please try again.');
			}
		},
		async getCurrentUser() {
			try {
			const token = localStorage.getItem('token');
			const response = await axios.get('http://127.0.0.1:8000/api/auth/user', {
				headers: {
					Authorization: `Bearer ${token}`
				}
			});
			this.userId = response.data.id_client;
			console.log('Current user:', response.data.id_client);
			} catch (error) {
			console.error('Erreur lors de la récupération de l\'utilisateur:', error);
			}
		},
		async getPlacesRemaining(flight) {
			try {
				const token = localStorage.getItem('token');
				const planesResponse = await axios.get('http://127.0.0.1:8000/api/planes', {
				headers: {
					Authorization: `Bearer ${token}`
				}
				});
				const bookingsResponse = await axios.get('http://127.0.0.1:8000/api/bookings', {
				headers: {
					Authorization: `Bearer ${token}`
				}
				});
				
				const plane = planesResponse.data.find(p => p.id_plane == flight.plane.id_plane);

				if (!plane) {
					this.$set(this.remainingPlacesMap, flight.id_fly, 'N/A');
					return;
				}
				
				const bookingsForFlight = bookingsResponse.data.filter(booking => 
					booking.id_fly === flight.id_fly
				);

				const maxPlaces = plane.max_place;
				const placesTaken = bookingsForFlight.length;
				const placesRemaining = maxPlaces - placesTaken;

				this.remainingPlacesMap[flight.id_fly] = placesRemaining;

				return placesRemaining;
			} catch (error) {
				console.error('Error getting remaining places:', error);
        		this.remainingPlacesMap[flight.id_fly] = 'Error';
			}
		},
		async loadAllRemainingPlaces() {
			const promises = this.flights.map(flight => this.getPlacesRemaining(flight));
      		await Promise.all(promises);
		},
		openModal(price, fly_id) {
			console.log('Opening modal with:', { price, fly_id });
			if (!fly_id) {
				console.error('No fly ID provided');
				return;
			}
			this.selectedPrice = price;
			this.selectedFlyId = fly_id;
			this.showModal = true;
		},
		closeModal() {
			this.showModal = false;
			this.searchFlights();
		}
	},
	async mounted() {
		await this.searchFlights();
		await this.getCurrentUser();
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