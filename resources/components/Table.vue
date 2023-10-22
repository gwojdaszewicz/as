<template>
  <div>
    <v-btn color="primary" @click="createNewItem">Nowy pojazd</v-btn>
    <v-data-table
        :headers="headers"
        :items="vehicles"
        :items-per-page="10"
      class="elevation-1"
    >
      <template v-slot:item.createdAt="{ item }">
        {{ formatDate(item.createdAt) }}
      </template>
      <template v-slot:item.updatedAt="{ item }">
        {{ formatDate(item.updatedAt) }}
      </template>
      <template v-slot:item.actions="{ item }">
        <v-icon small class="mr-2" @click="openEdit(item)">mdi-pencil</v-icon>
        <v-icon small @click="deleteItem(item)">mdi-delete</v-icon>
      </template>
    </v-data-table>
    <v-dialog v-model="editDialog" max-width="600px">
      <v-card>
        <v-card-title>
          {{ editedItem.id ? 'Edycja' : 'Nowy' }} Pojazd
        </v-card-title>
        <v-card-text>
          <v-form ref="editForm">
            <v-text-field v-model="editedItem.registrationNumber" label="Numer rejestracyjny"></v-text-field>
            <v-text-field v-model="editedItem.brand" label="Marka"></v-text-field>
            <v-text-field v-model="editedItem.model" label="Model"></v-text-field>
            <v-text-field v-model="editedItem.type" label="Typ pojazdu"></v-text-field>
          </v-form>
        </v-card-text>
        <v-card-actions>
          <v-btn color="primary" @click="saveEdit">{{ editedItem.id ? 'Zapisz' : 'Dodaj' }}</v-btn>
          <v-btn color="error" @click="closeEdit">Anuluj</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      vehicles: [],
      headers: [
        { text: 'Lp.', value: 'id' },
        { text: 'Nr rejestracyjny', value: 'registrationNumber' },
        { text: 'Marka', value: 'brand' },
        { text: 'Model', value: 'model' },
        { text: 'Typ pojazdu', value: 'type' },
        { text: 'Data utworzenia', value: 'createdAt' },
        { text: 'Data modyfikacji', value: 'updatedAt' },
        { text: 'Akcje', sortable: false }
      ],
      editDialog: false,
      editedIndex: -1,
      editedItem: {
        id: 0,
        registrationNumber: "",
        brand: "",
        model: "",
        type: ""
      }
    };
  },
  created() {
    this.fetchData();
  },
  methods: {
    fetchData() {
      axios.get('/vehicles/list')
          .then((response) => {
            this.vehicles = response.data.results;
          })
          .catch((error) => {
            console.error('Fetching error:', error);
          });
    },
    openEdit(item) {
      this.editedIndex = this.vehicles.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.editDialog = true;
    },
    closeEdit() {
      this.editedIndex = -1;
      this.editedItem = {
        id: 0,
        registrationNumber: "",
        brand: "",
        model: "",
        type: ""
      };
      this.editDialog = false;
    },
    saveEdit() {
      if (this.editedItem.id) {
        axios.post(`/vehicles/save/${this.editedItem.id}`, this.editedItem)
            .then((response) => {
              this.vehicles[this.editedIndex] = response.data.vehicle;
              this.closeEdit();
            })
            .catch((error) => {
              console.error('Błąd edycji danych:', error);
            });
      } else {
        axios.post(`/vehicles/save`, this.editedItem)
            .then((response) => {
              this.vehicles.push(response.data.vehicle);
              this.closeEdit();
            })
            .catch((error) => {
              console.error('Błąd tworzenia nowego pojazdu:', error);
            });
      }
    },
    deleteItem(id) {
      const confirmed = confirm('Czy na pewno chcesz usunąć ten pojazd?');
      if (!confirmed) {
        return;
      }

      axios.post(`/vehicles/delete/${id}`)
          .then(() => {
            this.fetchData();
          })
          .catch((error) => {
            console.error('Błąd usuwania pojazdu:', error);
          });
    },
    createNewItem() {
      this.editedItem = {
        id: 0,
        registrationNumber: "",
        brand: "",
        model: "",
        type: ""
      };
      this.editDialog = true;
    },
    formatDate(dateString) {
      const date = new Date(dateString);
      const year = date.getFullYear();
      const month = String(date.getMonth() + 1).padStart(2, '0');
      const day = String(date.getDate()).padStart(2, '0');
      const hours = String(date.getHours()).padStart(2, '0');
      const minutes = String(date.getMinutes()).padStart(2, '0');
      return `${year}-${month}-${day} ${hours}:${minutes}`;
    },
  }
};
</script>

<script setup>
</script>
