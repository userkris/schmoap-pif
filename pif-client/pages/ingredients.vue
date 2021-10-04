<template lang="html">
  <v-card>

    <!-- Card Title -->
    <v-card-title>
      <!-- Search -->
      <v-text-field
        v-model="search"
        append-icon="mdi-magnify"
        label="Search"
        single-line
        hide-details
      ></v-text-field>
    </v-card-title>

    <!-- Table -->
    <v-data-table
      v-if="list"
      :headers="headers"
      :items="list"
      :search="search"
      :items-per-page="50"
      group-by="Category"
      show-group-by
      :loading="loading"
      loading-text="Loading... Please wait"
      class="elevation-1"
    >

      <!-- Toolbar -->
      <template v-slot:top>
        <v-toolbar flat>
          <v-toolbar-title>Ingredients</v-toolbar-title>
          <v-divider
            class="mx-4"
            inset
            vertical
          ></v-divider>
          <v-spacer></v-spacer>

          <!-- Add New -->
          <v-dialog
            v-model="dialog"
            max-width="500px"
          >

            <template v-slot:activator="{ on, attrs }">
              <v-btn
                color="primary"
                dark
                class="mb-2"
                v-bind="attrs"
                v-on="on"
              >
                New Ingredient
              </v-btn>
            </template>

            <v-card>
              <v-card-title>
                <span class="text-h5">{{ formTitle }}</span>
              </v-card-title>

              <v-card-text>
                <v-container>

                  <!-- Name -->
                  <v-row>
                    <v-text-field
                      v-model="editedItem.name"
                      label="Name"
                    ></v-text-field>
                  </v-row>

                  <!-- INCI -->
                  <v-row>
                    <v-text-field
                      v-model="editedItem.inci"
                      label="INCI"
                    ></v-text-field>
                  </v-row>

                  <!-- Category -->
                  <v-row>
                    <v-text-field
                      v-model="editedItem.category"
                      label="Category"
                    ></v-text-field>
                  </v-row>

                  <!-- Supplier -->
                  <v-row>
                    <v-text-field
                      v-model="editedItem.supplier"
                      label="Supplier"
                    ></v-text-field>
                  </v-row>

                  <!-- Supplier URL -->
                  <v-row>
                    <v-text-field
                      v-model="editedItem.supplier_url"
                      label="Supplier URL"
                      placeholder="https://"
                    ></v-text-field>
                  </v-row>

                </v-container>
              </v-card-text>

              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn
                  color="blue darken-1"
                  text
                  @click="close"
                >
                  Cancel
                </v-btn>
                <v-btn
                  color="blue darken-1"
                  text
                  @click="save"
                >
                  Save
                </v-btn>
              </v-card-actions>
            </v-card>

          </v-dialog>

          <!-- Delete -->
          <v-dialog v-model="dialogDelete" max-width="500px">
          <v-card>
            <v-card-title class="text-h5">Are you sure you want to delete this item?</v-card-title>
            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn color="blue darken-1" text @click="closeDelete">Cancel</v-btn>
              <v-btn color="blue darken-1" text @click="deleteItemConfirm">OK</v-btn>
              <v-spacer></v-spacer>
            </v-card-actions>
          </v-card>
        </v-dialog>
        </v-toolbar>
      </template>

      <template v-slot:item.supplier="{ item }">
        <a
          v-if="item.supplier_url"
          :href="item.supplier_url"
        >
          {{ item.supplier }}
        </a>
        <span
          v-else
        >
          {{ item.supplier }}
        </span>
      </template>

      <template v-slot:item.actions="{ item }">
        <v-icon
          small
          class="mr-2"
          @click="editItem(item)"
        >
          mdi-pencil
        </v-icon>
        <v-icon
          small
          @click="deleteItem(item)"
        >
          mdi-delete
        </v-icon>
      </template>
      <template v-slot:no-data>
        <v-btn
          color="primary"
          @click="initialize"
        >
          Reset
        </v-btn>
      </template>

    </v-data-table>
  </v-card>
</template>

<script>
export default {
  data () {
    return {
      search: '',
      loading: false,

      headers: [
        { text: 'Name', align: 'start', value: 'name', groupable: false },
        { text: 'Category', value: 'category' },
        { text: 'INCI', value: 'inci', groupable: false },
        { text: 'Supplier', value: 'supplier', groupable: false },
        { text: 'Actions', value: 'actions', sortable: false, groupable: false }
      ],
      list: [],

      editedIndex: -1,
      editedId: -1,
      editedItem: {
        name: '',
        inci: '',
        supplier: '',
        supplier_url: '',
        category: '',
      },
      defaultItem: {
        name: '',
        inci: '',
        supplier: '',
        supplier_url: '',
        category: '',
      },
      dialog: false,
      dialogDelete: false,
    }
  },

  created () {
    this.initialize()
  },

  computed: {
    formTitle () {
      return this.editedIndex === -1 ? 'New Item' : 'Edit Item'
    },
  },

  watch: {
    dialog (val) {
      val || this.close()
    },
    dialogDelete (val) {
      val || this.closeDelete()
    },
  },

  methods: {
    async test () {
      // const { data } = await this.$axios.post('/test', {
      const { data } = await this.$axios.post('/ingredient-insert', {
        name: 'Olive Pomace Oil',
        inci: 'Olea europaea (Olive) Fruit Oil',
        supplier: 'KTC',
        supplier_url: 'https://google.com',
        category: 'Oil'
      });

      console.log(data)
    },

    snackbar () {
      this.$store.dispatch('snackbar', '123');
    },



    /* initialize */
    async initialize () {
      this.loadingState()

      const { data } = await this.$axios.get('/ingredients')

      this.list = data.data

      this.loadingState()
      this.$ui(data)
    },

    loadingState (bool = null) {
      if (bool === false) {
        return this.loading = false
      }
      if (bool === true) {
        return this.loading = true
      }
      return this.loading = !this.loading
    },




    editItem (item) {
      this.editedIndex = this.list.indexOf(item)
      this.editedItem = Object.assign({}, item)
      this.editedId = item.id
      this.dialog = true
    },

    deleteItem (item) {
      this.editedIndex = this.list.indexOf(item)
      this.editedId = item.id
      this.editedItem = Object.assign({}, item)
      this.dialogDelete = true
    },

    async deleteItemConfirm () {
      this.loadingState()
      const { data } = await this.$axios.post('/ingredient-delete', { id: this.editedId });
      this.list.splice(this.editedIndex, 1)
      this.closeDelete()
      this.$ui(data)
      this.loadingState()
    },

    close () {
      this.dialog = false
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem)
        this.editedIndex = -1
        this.editedId = -1
        this.initialize()
      })
    },

    closeDelete () {
      this.dialogDelete = false
      this.$nextTick(() => {
        this.editedItem = Object.assign({}, this.defaultItem)
        this.editedIndex = -1
        this.editedId = -1
      })
    },

    async save () {
      if (this.editedIndex > -1) {
        /* Edit item */
        this.loadingState()
        const { data } = await this.$axios.post('/ingredient-update', this.editedItem)
        Object.assign(this.list[this.editedIndex], this.editedItem)
        this.loadingState()
        this.$ui(data)
      } else {
        /* Add item */
        this.loadingState()
        this.list.push(this.editedItem)
        const { data } = await this.$axios.post('/ingredient-insert', this.editedItem)
        this.loadingState()
        this.$ui(data)
      }
      this.close()
    },





  }
}
</script>
