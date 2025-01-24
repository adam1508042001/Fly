<template>
  <div class="modal-overlay" @click.self="closeModal">
    <div class="modal-content relative">
      <h2 class="text-2xl font-bold mb-4">Réserver un vol</h2>
      <div class="mb-4">
        <label for="nbPassengers" class="block font-bold mb-2">Nombre de passagers</label>
        <input type="number" id="nbPassengers" v-model.number="nbPassengers" class="block w-full px-4 py-2 rounded border border-gray-300 focus:outline-none focus:ring focus:ring-blue-500 focus:border-blue-500 shadow-sm" />
      </div>

      <div class="mb-4 flex justify-center">
        <button @click="confirmReservation" class="bg-blue-500 text-white py-2 px-4 rounded-full font-bold hover:bg-blue-600 focus:outline-none focus:ring focus:ring-blue-300 transition-all">
          Confirmer la réservation
        </button>
      </div>

      <div class="mb-4">
        <p class="text-xl font-bold">{{ formattedTotalPrice }}</p>
      </div>
      <button @click="closeModal" class="absolute bottom-4 right-4 bg-red-500 text-white py-2 px-4 rounded-full font-bold hover:bg-red-600 focus:outline-none focus:ring focus:ring-red-300 transition-all">
        Fermer
      </button>
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
    }
  },
  data() {
    return {
      nbPassengers: 1
    };
  },
  computed: {
    formattedTotalPrice() {
      const totalPrice = this.nbPassengers * this.price;
      return isNaN(totalPrice) ? '0.00 €' : this.formatPrice(totalPrice);
    }
  },
  methods: {
    closeModal() {
      this.$emit('close');
    },
    async confirmReservation() {
      try {
        await axios.post('http://127.0.0.1:8000/api/send-confirmation-email', {
          nbPassengers: this.nbPassengers,
          totalPrice: this.nbPassengers * this.price
        });
        alert('Réservation confirmée ! Vous allez recevoir un mail de confirmation.');
        this.closeModal();
      } catch (error) {
        console.error('Erreur lors de l\'envoi de l\'email de confirmation:', error);
        alert('Une erreur est survenue lors de la confirmation de la réservation.');
      }
    },
    formatPrice(price) {
      return price.toFixed(2) + ' €';
    },
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
	position: relative;
}
</style>