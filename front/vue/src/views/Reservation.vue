<template>
  <div class="modal-overlay" @click.self="closeModal">
    <div class="modal-content">
		<h2>Réserver un vol</h2>
		<div>
			<label for="nbPassengers">Nombre de passagers</label>
			<input type="number" id="nbPassengers" v-model="nbPassengers" />
		</div>

		<div>
			<button @click="confirmReservation">
				Confirmer la réservation
			</button>
		</div>

		<div>
			<p>{{ nbPassengers * price }} €</p>
		</div>
      <button @click="closeModal">Fermer</button>
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
}
</style>