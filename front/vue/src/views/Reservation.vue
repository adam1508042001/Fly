<template>
  <div class="modal-overlay" @click.self="closeModal">
    <div class="modal-content">
      <h2 class="text-2xl font-bold mb-4">Réserver un vol</h2>
      <div class="mb-4">
        <label for="nb_passenger" class="block font-bold mb-2">Nombre de passagers</label>
        <input 
          type="number" 
          id="nb_passenger" 
          v-model="nb_passenger" 
          class="w-full px-4 py-2 rounded-full border border-gray-300 focus:outline-none focus:ring focus:ring-blue-500 focus:border-blue-500 shadow-sm" 
        />
      </div>

      <div class="mb-4">
        <button 
          @click="confirmReservation" 
          class="w-full bg-[#0004ff] text-white py-2 rounded-full font-bold hover:bg-blue-600 focus:outline-none focus:ring focus:ring-blue-300 transition-all"
        >
          Confirmer la réservation
        </button>
      </div>

      <div class="mb-4 text-center text-xl font-bold">
        <p>Total: {{ isNaN(totalPrice) ? '0' : totalPrice }} €</p>
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

export default {
  props: {
    price: {
      type: Number,
      required: true
    },
    id_fly: {
      type: Number,
      required: true
    }
  },
  data() {
    return {
      nb_passenger: 1,
      userId: null
    };
  },
  computed: {
    totalPrice() {
      return this.nb_passenger * (this.price || 0);
    }
  },
  async created() {
    await this.getCurrentUser();
  },
  methods: {
    closeModal() {
      this.$emit('close');
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
      } catch (error) {
        console.error('Erreur lors de la récupération de l\'utilisateur:', error);
        alert('Erreur lors de la récupération de vos informations');
        this.closeModal();
      }
    },
    async confirmReservation() {
      try {
        const token = localStorage.getItem('token');
        const date = new Date();
        const dateHour = date.getFullYear() + '-' +
                         ('0' + (date.getMonth() + 1)).slice(-2) + '-' +
                         ('0' + date.getDate()).slice(-2) + ' ' +
                         ('0' + date.getHours()).slice(-2) + ':' +
                         ('0' + date.getMinutes()).slice(-2) + ':' +
                         ('0' + date.getSeconds()).slice(-2);

        const bookingData = {
          date_hour: dateHour,
          place_reserved: this.nb_passenger,
          state: 'confirmed',
          suitcase_authorized: 0,
          weight_authorized: 0,
          id_fly: this.id_fly,
          id_client: this.userId
        };

        console.log('Booking data:', bookingData);

        const bookingResponse = await axios.post('http://127.0.0.1:8000/api/bookings', bookingData, {
          headers: {
            Authorization: `Bearer ${token}`,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
          }
        });

        const userResponse = await axios.get(`http://127.0.0.1:8000/api/clients/${this.userId}`, {
          headers: {
            Authorization: `Bearer ${token}`
          }
        });

        const emailData = {
          nb_passengers: this.nb_passenger,
          total_price: this.totalPrice || 0,
          email: userResponse.data.email,
        };

        console.log('Email data:', emailData);

        await axios.post('http://127.0.0.1:8000/api/mail/send', emailData, {
          headers: {
            Authorization: `Bearer ${token}`
          }
        });

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