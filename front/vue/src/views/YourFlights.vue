<template>
	<div class="flex flex-col min-h-screen">
		<NavBar />

		<div class="grid items-center justify-center grid-cols-1 grid-rows-[150px_auto] bg-[#ffffff] bg-opacity-80 rounded-[30px] mx-10 my-5">
			<div
				class="text-start mb-6"
			>
				<h1
					class="text-4xl font-bold text-center text-[#000000]"
				>
					Your flights
				</h1>
			</div>
			
			<div class="mb-6">
				<div v-for="(flight, index) in sortedFlights" :key="index" class="grid grid-cols-7 gap-4 mb-6">
					<div>{{ generateFlightName(flight) }}</div>
					<div>{{ flight.departureDate }}</div>
					<div>{{ flight.arrivalDate }}</div>
					<div>{{ flight.remainingPlaces }}</div>
					<div>{{ flight.planeName }}</div>
					<div>{{ flight.price }}</div>
					<div 
						:class="{
						'text-green-600 font-bold': flight.status.toLowerCase() === 'confirmed',
						'text-yellow-600 font-bold': flight.status.toLowerCase() === 'pending',
						'text-red-600 font-bold': flight.status.toLowerCase() === 'cancelled'
						}"
					>
						{{ formatStatus(flight.status) }}
					</div>
					<button @click="openModal()">Modifier</button>
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
			flights: [],
			showModal: false
		};
	},
	methods: {
		async getFlights() {
			try {
				const response = await axios.get('http://127.0.0.1:8000/api/bookings');
				this.flights = response.data.filter(flight => flight.id_client === this.$store.state.user.id);
			} catch (error) {
				console.error(error);
			}
		},
		formatStatus(status) {
			if (status.toLowerCase() === 'confirmed') {
				return 'Confirmé';
			} else if (status.toLowerCase() === 'pending') {
				return 'En attente';
			} else if (status.toLowerCase() === 'cancelled') {
				return 'Annulé';
			} else {
				return 'Inconnu';
			}
		},
		openModal() {
			this.showModal = true;
		},
		generateFlightName(flight) {
			if (flight.airport_fly_off && flight.airport_landing) {
				return `${flight.airport_fly_off.city} ${flight.airport_fly_off.name} -> ${flight.airport_landing.city} ${flight.airport_landing.name}`;
			}
			return 'Unknown Flight';
		},
	},
	mounted() {
		this.getFlights();
	}
};
</script>

<style scoped>

</style>