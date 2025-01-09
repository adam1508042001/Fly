<template>
	<div class="flex flex-col min-h-screen">
		<!-- Navbar -->
		<NavBar />
		<!-- Search box -->
		<div class="grid items-center justify-center grid-cols-1 grid-rows-[150px_auto] bg-[#ffffff] bg-opacity-80 rounded-[30px] mx-10 my-5">
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
						class="block w-full px-4 py-2 mt-1 rounded-full border border-gray-300 focus:outline-none focus:ring focus:ring-blue-500 focus:border-blue-500 shadow-sm">
						<option value="price">Price</option>
						<option value="duration">Duration</option>
						<option value="departure">Departure</option>
						<option value="arrival">Arrival</option>
					</select>
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
					<div v-for="(flight, index) in flights" :key="index" class="grid grid-cols-7 gap-4 mb-6">
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
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
import NavBar from './NavBar.vue';
export default {
  components: {
	NavBar
  },
  data() {
	return {
	  flights: [
		{
		  name: 'Flight 1',
		  departureDate: '2022-12-12',
		  arrivalDate: '2022-12-12',
		  remainingPlaces: 100,
		  planeName: 'Boeing 747',
		  price: 1000,
		  status: 'Available'
		},
		{
		  name: 'Flight 2',
		  departureDate: '2022-12-12',
		  arrivalDate: '2022-12-12',
		  remainingPlaces: 100,
		  planeName: 'Boeing 747',
		  price: 1000,
		  status: 'Available'
		},
		{
		  name: 'Flight 3',
		  departureDate: '2022-12-12',
		  arrivalDate: '2022-12-12',
		  remainingPlaces: 100,
		  planeName: 'Boeing 747',
		  price: 1000,
		  status: 'Unavailable'
		},
		{
		  name: 'Flight 4',
		  departureDate: '2022-12-12',
		  arrivalDate: '2022-12-12',
		  remainingPlaces: 100,
		  planeName: 'Boeing 747',
		  price: 1000,
		  status: 'Flying'
		}
	  ]
	};
  },
  methods: {
    formatStatus(status) {
      return status.charAt(0).toUpperCase() + status.slice(1).toLowerCase();
    }
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