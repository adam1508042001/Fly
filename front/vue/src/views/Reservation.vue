<template>
  <div class="modal-overlay" @click.self="closeModal">
    <div class="modal-content">
		<h2 class="text-2xl font-bold mb-4">Réserver un vol</h2>
		<div class="mb-4">
			<label for="nbPassengers" class="block font-bold mb-2">Nombre de passagers</label>
			<input type="number" id="nbPassengers" v-model="nbPassengers" class="w-full px-4 py-2 rounded-full border border-gray-300 focus:outline-none focus:ring focus:ring-blue-500 focus:border-blue-500 shadow-sm" />
		</div>

		<div class="mb-4">
			<button @click="confirmReservation" class="w-full bg-[#0004ff] text-white py-2 rounded-full font-bold hover:bg-blue-600 focus:outline-none focus:ring focus:ring-blue-300 transition-all">
				Confirmer la réservation
			</button>
		</div>

		<div class="mb-4 text-center text-xl font-bold">
			<p>Total: {{ isNaN(totalPrice) ? '0' : totalPrice }} €</p>
		</div>
      <button @click="closeModal" class="w-full bg-gray-300 text-black py-2 rounded-full font-bold hover:bg-gray-400 focus:outline-none focus:ring focus:ring-gray-300 transition-all">Fermer</button>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  props: {
    show: {
      type: Boolean,
      default: false
    },
    price: {
      type: Number,
      required: true
    },
    idFly: {
      type: Number,
      required: true
    },
    idClient: {
      type: Number,
      required: true
    }
  },
  data() {
    return {
      nbPassengers: 1
    };
  },
  computed: {
    totalPrice() {
      return this.nbPassengers * this.price;
    }
  },
  methods: {
    closeModal() {
      this.$emit('close');
    },
    async confirmReservation() {
      try {
        const token = localStorage.getItem('token');
        const dateHour = new Date().toISOString();
        const bookingData = {
          date_hour: dateHour,
          place_reserved: this.nbPassengers,
          state: 'confirmed',
          suitcase_authorized: 0,
          weight_authorized: 0,
          id_fly: this.idFly,
          id_client: this.idClient
        };
        console.log('Booking data:', bookingData);
        const bookingResponse = await axios.post('http://127.0.0.1:8000/api/bookings', bookingData, {
          headers: {
            Authorization: `Bearer ${token}`
          }
        });
        console.log('Booking response:', bookingResponse.data);
        const emailData = {
          nbPassengers: this.nbPassengers,
          totalPrice: this.totalPrice
        };
        console.log('Email data:', emailData);
        const emailResponse = await axios.post('http://127.0.0.1:8000/api/mail/send', emailData, {
          headers: {
            Authorization: `Bearer ${token}`
          }
        });
        console.log('Email response:', emailResponse.data); // Ajoutez cette ligne pour vérifier la réponse de la requête d'email
        alert('Réservation confirmée ! Vous allez recevoir un mail de confirmation.');
        this.closeModal();
      } catch (error) {
        console.error('Erreur lors de la confirmation de la réservation:', error);
        alert('Une erreur est survenue lors de la confirmation de la réservation.');
      }
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
	max-width: 600px;
	box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}
</style>