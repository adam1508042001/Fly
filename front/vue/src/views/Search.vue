<template>

<div>


    <div class="p-6 bg-gray-50 rounded-md shadow-md" @click="closeSuggestions">

        
      <!-- Header -->
      <div class="text-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Flight Informations</h1>
      </div>
  
      <!-- Filters -->
      <div class="flex flex-wrap gap-4 mb-6">
        <!-- Lieu de Départ -->
        <div class="relative w-full md:w-1/3" @click.stop>
          <label class="block text-sm text-gray-600 mb-1">Lieu de Départ</label>
          <input
            type="text"
            v-model="filters.departure"
            @input="updateDepartureSuggestions"
            placeholder="Entrez un lieu de départ"
            class="p-2 border rounded w-full focus:outline-none focus:ring-2 focus:ring-blue-500"
          />
          <!-- Suggestions -->
          <ul
            v-if="filteredDepartures.length > 0"
            class="absolute z-10 bg-white border rounded w-full mt-1 shadow-lg"
          >
            <li
              v-for="(item, index) in filteredDepartures"
              :key="index"
              @click="selectDeparture(item)"
              class="p-2 cursor-pointer hover:bg-blue-500 hover:text-white"
            >
              {{ item }}
            </li>
          </ul>
        </div>
  
        <!-- Lieu d'Arrivée -->
        <div class="relative w-full md:w-1/3" @click.stop>
          <label class="block text-sm text-gray-600 mb-1">Lieu d'Arrivée</label>
          <input
            type="text"
            v-model="filters.arrival"
            @input="updateArrivalSuggestions"
            placeholder="Entrez un lieu d'arrivée"
            class="p-2 border rounded w-full focus:outline-none focus:ring-2 focus:ring-blue-500"
          />
          <!-- Suggestions -->
          <ul
            v-if="filteredArrivals.length > 0"
            class="absolute z-10 bg-white border rounded w-full mt-1 shadow-lg"
          >
            <li
              v-for="(item, index) in filteredArrivals"
              :key="index"
              @click="selectArrival(item)"
              class="p-2 cursor-pointer hover:bg-blue-500 hover:text-white"
            >
              {{ item }}
            </li>
          </ul>
        </div>
  
        <!-- Date de Départ -->
        <div class="w-full md:w-1/3">
          <label class="block text-sm text-gray-600 mb-1">Date de Départ</label>
          <input
            type="date"
            v-model="filters.date"
            @change="applyFilters"
            class="p-2 border rounded w-full"
          />
        </div>
      </div>
  
      <!-- Table -->
      <div class="overflow-x-auto">
        <table class="table-auto w-full border-collapse bg-white rounded-lg shadow-md">
          <thead class="bg-gray-100">
            <tr class="text-gray-600 text-sm uppercase text-left">
              <th class="p-3">Lieu de Départ</th>
              <th class="p-3">Lieu d'Arrivée</th>
              <th class="p-3">Date de Départ</th>
              <th class="p-3">Date d'Arrivée</th>
              <th class="p-3">Places Restantes</th>
              <th class="p-3">Avion</th>
              <th class="p-3">Action</th>
            </tr>
          </thead>
          <tbody>
            <!-- Rows -->
            <tr
              v-for="(flight, index) in filteredFlights"
              :key="index"
              class="border-t hover:bg-gray-50"
            >
              <td class="p-3">{{ flight.departure }}</td>
              <td class="p-3">{{ flight.arrival }}</td>
              <td class="p-3">{{ flight.departureDate }}</td>
              <td class="p-3">{{ flight.arrivalDate }}</td>
              <td class="p-3">{{ flight.remainingPlaces }}</td>
              <td class="p-3">{{ flight.plane }}</td>
              <td class="p-3">
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Book
</button>
              </td>
            </tr>
            <!-- Empty State -->
            <tr v-if="filteredFlights.length === 0">
              <td colspan="7" class="p-4 text-center text-gray-500">
                Aucun vol ne correspond aux filtres sélectionnés.
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

</div>
  </template>
  
  <script>

  export default {
    name: "FlightInformations",
    data() {
      return {
        flights: [
          { departure: "Paris", arrival: "Londres", departureDate: "2024-12-11", arrivalDate: "2024-12-12", remainingPlaces: 16, plane: "Airbus A320", status: "Active" },
          { departure: "New York", arrival: "Tokyo", departureDate: "2024-12-15", arrivalDate: "2024-12-16", remainingPlaces: 8, plane: "Boeing 747", status: "Inactive" },
          { departure: "Berlin", arrival: "Madrid", departureDate: "2024-12-18", arrivalDate: "2024-12-19", remainingPlaces: 0, plane: "Boeing 737", status: "Inactive" },
          { departure: "Paris", arrival: "Londres", departureDate: "2024-12-11", arrivalDate: "2024-12-12", remainingPlaces: 16, plane: "Airbus A320", status: "Active" },
        { departure: "New York", arrival: "Tokyo", departureDate: "2024-12-15", arrivalDate: "2024-12-16", remainingPlaces: 8, plane: "Boeing 747", status: "Inactive" },
        { departure: "Berlin", arrival: "Madrid", departureDate: "2024-12-18", arrivalDate: "2024-12-19", remainingPlaces: 0, plane: "Boeing 737", status: "Active" },
        { departure: "Los Angeles", arrival: "Sydney", departureDate: "2024-12-20", arrivalDate: "2024-12-22", remainingPlaces: 5, plane: "Airbus A380", status: "Active" },
        { departure: "Dubai", arrival: "Rome", departureDate: "2024-12-12", arrivalDate: "2024-12-13", remainingPlaces: 20, plane: "Boeing 777", status: "Active" },
        { departure: "Tokyo", arrival: "Paris", departureDate: "2024-12-25", arrivalDate: "2024-12-26", remainingPlaces: 4, plane: "Airbus A350", status: "Inactive" },
        { departure: "Shanghai", arrival: "Berlin", departureDate: "2024-12-30", arrivalDate: "2025-01-01", remainingPlaces: 9, plane: "Boeing 787", status: "Active" },
        { departure: "Madrid", arrival: "London", departureDate: "2024-12-05", arrivalDate: "2024-12-06", remainingPlaces: 0, plane: "Boeing 737", status: "Inactive" },
        { departure: "San Francisco", arrival: "Beijing", departureDate: "2024-12-10", arrivalDate: "2024-12-12", remainingPlaces: 12, plane: "Boeing 747", status: "Active" },
        { departure: "Hong Kong", arrival: "Singapore", departureDate: "2024-12-15", arrivalDate: "2024-12-16", remainingPlaces: 15, plane: "Airbus A320", status: "Active" },
        { departure: "Rome", arrival: "Athens", departureDate: "2024-12-20", arrivalDate: "2024-12-21", remainingPlaces: 25, plane: "Boeing 737", status: "Active" },
        { departure: "Lisbon", arrival: "Berlin", departureDate: "2024-12-13", arrivalDate: "2024-12-14", remainingPlaces: 3, plane: "Airbus A320", status: "Active" },
        ],
        filters: { departure: "", arrival: "", date: "" },
        filteredFlights: [],
        filteredDepartures: [],
        filteredArrivals: [],
      };
    },
    computed: {
      uniqueDepartures() {
        return [...new Set(this.flights.map((flight) => flight.departure))];
      },
      uniqueArrivals() {
        return [...new Set(this.flights.map((flight) => flight.arrival))];
      },
    },
    methods: {
      updateDepartureSuggestions() {
        this.filteredDepartures = this.uniqueDepartures.filter((d) =>
          d.toLowerCase().includes(this.filters.departure.toLowerCase())
        );
        this.applyFilters();
      },
      updateArrivalSuggestions() {
        this.filteredArrivals = this.uniqueArrivals.filter((a) =>
          a.toLowerCase().includes(this.filters.arrival.toLowerCase())
        );
        this.applyFilters();
      },
      selectDeparture(departure) {
        this.filters.departure = departure;
        this.filteredDepartures = [];
      },
      selectArrival(arrival) {
        this.filters.arrival = arrival;
        this.filteredArrivals = [];
      },
      closeSuggestions() {
        this.filteredDepartures = [];
        this.filteredArrivals = [];
      },
      applyFilters() {
        this.filteredFlights = this.flights.filter((flight) => {
          return (
            (this.filters.departure === "" || flight.departure.toLowerCase().includes(this.filters.departure.toLowerCase())) &&
            (this.filters.arrival === "" || flight.arrival.toLowerCase().includes(this.filters.arrival.toLowerCase())) &&
            (this.filters.date === "" || flight.departureDate === this.filters.date)
          );
        });
      },
    },
    mounted() {
      this.filteredFlights = this.flights;
    },
  };
  </script>