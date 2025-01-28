<template>
	<div class="modal-overlay" @click.self="closeModal">
	  	<div class="modal-content">
			<div class="grid items-center justify-center grid-cols-1 grid-rows-[150px_auto] bg-[#ffffff] bg-opacity-80 rounded-[30px] mx-10 my-5">
				<div
					class="text-start mb-6"
				>
					<h1
						class="text-4xl font-bold text-center text-[#000000] grid grid-cols-2 gap-4"
					>
						Your flights
					</h1>
					<RouterLink to="/return-flights">
						<button 
							class="w-[150px] bg-[#0004ff] text-white py-2 rounded-full font-bold hover:bg-blue-600 focus:outline-none focus:ring focus:ring-blue-300 transition-all"
						>
							Return Flights
						</button>
					</RouterLink>
				</div>

				
				<div class="grid grid-cols-7 gap-4 font-opacity-80 mb-6 pb-2 text-gray-500 border-b-2 border-gray-400">
					<h3>Flight</h3>
					<h3>Departure Date</h3>
					<h3>Arrival Date</h3>
					<h3>Place reserved</h3>
					<h3>Plane Name</h3>
					<h3>Status</h3>
				</div>
				
				<div class="mb-6">
					<div v-for="(flight, index) in flights" :key="index" class="grid grid-cols-7 gap-4 mb-6">
						<div>{{ generatedName(flight) 			}}</div>
						<div>{{ flight.id_fly.date_hour_fly_off }}</div>
						<div>{{ flight.id_fly.date_hour_landing }}</div>
						<div>{{ flight.place_reserved 			}}</div>
						<div>{{ flight.id_fly.plane.model 		}}</div>
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
							class="px-4 py-2 rounded-full bg-[red] text-[#ffffff] focus:outline-none focus:ring focus:ring-blue-500 focus:border-blue-500 shadow-sm"
							@click="cancelBooking(flight)"
							>
							Cancel
						</button>
					</div>
				</div>
			</div>
			<button 
				@click="closeModal" 
				class="w-full bg-gray-300 text-black py-2 rounded-full font-bold hover:bg-gray-400 focus:outline-none focus:ring focus:ring-gray-300 transition-all"
			>
				Fermer
			</button>
		</div>
	</div>
</template>

<script>
import axios from 'axios';
import { ref } from 'vue';
import { RouterLink } from 'vue-router';

export default {
	data() {
		return {
			flights: [],
			userId: null,
			showModal: false
		};
	},
	async created() {
		await this.getCurrentUser();
		await this.getFlights();
	},
	methods: {
		async getCurrentUser() {
			try {
				const token = localStorage.getItem('token');
				const response = await axios.get('http://127.0.0.1:8000/api/auth/user', {
				headers: {
					Authorization: `Bearer ${token}`
				}
				});
				this.userId = response.data.id_client;
			} catch (error) {
				console.error('Erreur lors de la récupération de l\'utilisateur:', error);
				alert('Erreur lors de la récupération de vos informations');
				this.closeModal();
			}
		},
		async getFlights() {
			try {
				const token = localStorage.getItem('token');
				const response = await axios.get('http://127.0.0.1:8000/api/bookings', {
					headers: {
						Authorization: `Bearer ${token}`
					}
				});
				const bookings = response.data.filter(flight => flight.id_client == this.userId);
				const flights = await Promise.all(bookings.map(async (booking) => {
					const flightInfo = await this.getFlightInfo(booking.id_fly);
					return { ...booking, id_fly: flightInfo };
				}));
				this.flights = flights;
			} catch (error) {
				console.error('Error fetching flights:', error);
			}
		},
		async getFlightInfo(flightId) {
			try {
				const token = localStorage.getItem('token');
				const response = await axios.get(`http://127.0.0.1:8000/api/flies/${flightId}`, {
					headers: {
						Authorization: `Bearer ${token}`
					}
				});
				return response.data;
			} catch (error) {
				console.error('Error fetching flight info:', error);
				return null;
			}
		},
		generatedName(flight) {
			return `${flight.id_fly.airport_fly_off?.city || 'Unknown'} - ${flight.id_fly.airport_landing?.city || 'Unknown'}`;
		},
		formatState(state) {
			if (!state) return 'Unknown';
			return state.charAt(0).toUpperCase() + state.slice(1).toLowerCase();
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
			const id_fly = flight.id;
			const bookingsForFlight = response.data.filter(booking => booking.id_fly === id_fly);
			const placesTaken = bookingsForFlight.length;
			const placesRemaining = maxPlacesPlane.data.find(plane => plane.id === flight.plane_id).max_places - placesTaken;
			return placesRemaining;
		},
		async cancelBooking(flight) {
			try {
				const token = localStorage.getItem('token');
				await axios.patch(`http://localhost:8000/api/bookings/${flight.id_booking}`, {
					state: 'cancelled'
				}, {
					headers: {
						Authorization: `Bearer ${token}`
					}
				});
				await this.getFlights();
				console.log('Booking cancelled:', flight);
			} catch (error) {
				console.error('Error cancelling booking:', error);
			}
		},
		openModal() {
			this.showModal = true;
		},
		closeModal() {
			this.$emit('close');
		}
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
  max-width: 1200px;
  max-height: 80vh;
  overflow-y: auto;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}
</style>